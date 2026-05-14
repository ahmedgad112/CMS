<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_clinics');
    }

    public function index()
    {
        $clinics = Clinic::withCount(['doctors', 'appointments'])
            ->latest()
            ->paginate(15);

        return view('admin.clinics.index', compact('clinics'));
    }

    public function create()
    {
        $doctors = User::query()
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.clinics.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'working_hours' => 'nullable|string|max:255',
            'is_main' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'doctors' => 'nullable|array',
            'doctors.*' => 'exists:users,id',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_main'] = $request->has('is_main');

        if ($validated['is_main']) {
            Clinic::query()->where('is_main', true)->update(['is_main' => false]);
        }

        $doctors = $validated['doctors'] ?? [];
        unset($validated['doctors']);

        $clinic = Clinic::create($validated);

        if (! empty($doctors)) {
            $clinic->doctors()->sync($doctors);
        }

        return redirect()->route('admin.clinics.index')
            ->with('success', 'تم إضافة العيادة بنجاح.');
    }

    public function show(Clinic $clinic)
    {
        $clinic->load(['doctors.specialization', 'doctors.department']);
        $clinic->loadCount(['appointments', 'doctors']);

        $todayAppointments = $clinic->appointments()
            ->whereDate('appointment_date', today())
            ->count();

        return view('admin.clinics.show', compact('clinic', 'todayAppointments'));
    }

    public function edit(Clinic $clinic)
    {
        $doctors = User::query()
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $assignedDoctorIds = $clinic->doctors()->pluck('users.id')->toArray();

        return view('admin.clinics.edit', compact('clinic', 'doctors', 'assignedDoctorIds'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'working_hours' => 'nullable|string|max:255',
            'is_main' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'doctors' => 'nullable|array',
            'doctors.*' => 'exists:users,id',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_main'] = $request->has('is_main');

        if ($validated['is_main']) {
            Clinic::query()
                ->where('is_main', true)
                ->where('id', '!=', $clinic->id)
                ->update(['is_main' => false]);
        }

        $doctors = $validated['doctors'] ?? [];
        unset($validated['doctors']);

        $clinic->update($validated);
        $clinic->doctors()->sync($doctors);

        return redirect()->route('admin.clinics.index')
            ->with('success', 'تم تحديث العيادة بنجاح.');
    }

    public function destroy(Clinic $clinic)
    {
        if ($clinic->appointments()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف العيادة لأنها مرتبطة بمواعيد.']);
        }

        $clinic->doctors()->detach();
        Clinic::destroy($clinic->id);

        return redirect()->route('admin.clinics.index')
            ->with('success', 'تم حذف العيادة بنجاح.');
    }
}
