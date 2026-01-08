<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // البحث بالاسم
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $departments = \App\Models\Department::where('is_active', true)->get();
        $specializations = \App\Models\Specialization::where('is_active', true)->get();
        return view('admin.users.create', compact('departments', 'specializations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,doctor,receptionist,call_center,accountant,storekeeper',
            'specialization' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'checkup_fee' => 'nullable|numeric|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        DB::transaction(function () use ($validated, $request) {
            $user = User::create($validated);

            // Save doctor schedules if role is doctor
            if ($validated['role'] === 'doctor' && $request->has('schedules')) {
                foreach ($request->schedules as $schedule) {
                    if (!empty($schedule['day_of_week']) && !empty($schedule['start_time']) && !empty($schedule['end_time'])) {
                        DoctorSchedule::create([
                            'doctor_id' => $user->id,
                            'day_of_week' => $schedule['day_of_week'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load('schedules');
        $departments = \App\Models\Department::where('is_active', true)->get();
        $specializations = \App\Models\Specialization::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'departments', 'specializations'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,doctor,receptionist,call_center,accountant,storekeeper',
            'specialization' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'checkup_fee' => 'nullable|numeric|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        DB::transaction(function () use ($user, $validated, $request) {
            $user->update($validated);

            // Update doctor schedules if role is doctor
            if ($validated['role'] === 'doctor' && $request->has('schedules')) {
                // Delete existing schedules
                $user->schedules()->delete();

                // Create new schedules
                foreach ($request->schedules as $schedule) {
                    if (!empty($schedule['day_of_week']) && !empty($schedule['start_time']) && !empty($schedule['end_time'])) {
                        DoctorSchedule::create([
                            'doctor_id' => $user->id,
                            'day_of_week' => $schedule['day_of_week'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                        ]);
                    }
                }
            } elseif ($validated['role'] !== 'doctor') {
                // Delete schedules if role changed from doctor
                $user->schedules()->delete();
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
