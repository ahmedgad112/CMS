<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_users')->only(['index', 'show']);
        $this->middleware('permission:create_users')->only(['create', 'store']);
        $this->middleware('permission:edit_users')->only(['edit', 'update']);
        $this->middleware('permission:delete_users')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = User::query();

        // البحث بالاسم
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
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
        $departments = \App\Models\Department::where('is_active', '=', true, 'and')->get();
        $specializations = \App\Models\Specialization::where('is_active', '=', true, 'and')->get();
        $clinics = \App\Models\Clinic::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.users.create', compact('departments', 'specializations', 'clinics'));
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
            'clinic_id' => 'nullable|exists:clinics,id',
            'checkup_fee' => 'nullable|numeric|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'clinics' => 'nullable|array',
            'clinics.*' => 'exists:clinics,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $clinicIds = $validated['clinics'] ?? [];
        unset($validated['clinics']);

        // Doctors use Many-to-Many `clinic_user`; non-doctor branch staff use `clinic_id`
        if ($validated['role'] === 'doctor') {
            $validated['clinic_id'] = null;
        } elseif ($validated['role'] === 'admin') {
            $validated['clinic_id'] = null;
        }

        DB::transaction(function () use ($validated, $request, $clinicIds) {
            $user = User::create($validated);

            // Save doctor schedules if role is doctor
            if ($validated['role'] === 'doctor' && $request->has('schedules')) {
                foreach ($request->schedules as $schedule) {
                    if (! empty($schedule['day_of_week']) && ! empty($schedule['start_time']) && ! empty($schedule['end_time'])) {
                        DoctorSchedule::create([
                            'doctor_id' => $user->id,
                            'day_of_week' => $schedule['day_of_week'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                        ]);
                    }
                }
            }

            if ($validated['role'] === 'doctor' && ! empty($clinicIds)) {
                $user->clinics()->sync($clinicIds);
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
        $departments = \App\Models\Department::where('is_active', '=', true, 'and')->get();
        $specializations = \App\Models\Specialization::where('is_active', '=', true, 'and')->get();
        $clinics = \App\Models\Clinic::query()->where('is_active', true)->orderBy('name')->get();
        $assignedClinicIds = $user->clinics()->pluck('clinics.id')->toArray();

        return view('admin.users.edit', compact('user', 'departments', 'specializations', 'clinics', 'assignedClinicIds'));
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
            'clinic_id' => 'nullable|exists:clinics,id',
            'checkup_fee' => 'nullable|numeric|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'clinics' => 'nullable|array',
            'clinics.*' => 'exists:clinics,id',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $clinicIds = $validated['clinics'] ?? [];
        unset($validated['clinics']);

        // Doctors are linked via Many-to-Many; admins see all clinics; others use single clinic_id
        if (in_array($validated['role'], ['doctor', 'admin'], true)) {
            $validated['clinic_id'] = null;
        }

        DB::transaction(function () use ($user, $validated, $request, $clinicIds) {
            $user->update($validated);

            // Update doctor schedules if role is doctor
            if ($validated['role'] === 'doctor' && $request->has('schedules')) {
                // Delete existing schedules
                $user->schedules()->delete();

                // Create new schedules
                foreach ($request->schedules as $schedule) {
                    if (! empty($schedule['day_of_week']) && ! empty($schedule['start_time']) && ! empty($schedule['end_time'])) {
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

            // Sync clinic assignments for doctors; clear for non-doctors
            if ($validated['role'] === 'doctor') {
                $user->clinics()->sync($clinicIds);
            } else {
                $user->clinics()->sync([]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        User::destroy($user->getKey());

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
