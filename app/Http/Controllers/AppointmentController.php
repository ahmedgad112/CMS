<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Support\ClinicContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_appointments')->only(['index', 'show']);
        $this->middleware('permission:create_appointments')->only(['create', 'store']);
        $this->middleware('permission:edit_appointments')->only(['edit', 'update']);
        $this->middleware('permission:delete_appointments')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor', 'clinic', 'creator']);

        // Scope by current clinic context (branch users + admin clinic switcher)
        if ($clinicId = ClinicContext::currentId()) {
            $query->where('clinic_id', $clinicId);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', '=', $request->doctor_id, 'and');
        }

        // Filter by clinic
        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', '=', $request->clinic_id, 'and');
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('department_id', '=', $request->department_id, 'and');
            });
        }

        // Filter by specialization
        if ($request->filled('specialization_id')) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('specialization_id', '=', $request->specialization_id, 'and');
            });
        }

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', '=', $request->patient_id, 'and');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', '=', $request->status, 'and');
        }

        // Filter by appointment type
        if ($request->filled('appointment_type')) {
            $appointmentType = $request->appointment_type;
            // Handle both new appointments with type and old ones without type (default to checkup)
            if ($appointmentType == 'checkup') {
                $query->where(function ($q) {
                    $q->where('appointment_type', '=', 'checkup', 'and')
                        ->orWhereNull('appointment_type');
                });
            } else {
                $query->where('appointment_type', '=', $appointmentType, 'and');
            }
        }

        // If user is a doctor, show only their appointments
        $user = Auth::user();
        if ($user && method_exists($user, 'isDoctor') && $user->isDoctor()) {
            $query->where('doctor_id', '=', $user->getKey(), 'and');
        }

        $appointments = $query->latest('appointment_date')->paginate(15)->withQueryString();

        // Restrict doctors list to those linked to the active clinic when scoped
        $doctorQuery = User::where('role', '=', 'doctor', 'and')->where('is_active', '=', true, 'and');
        if ($clinicScopeId = ClinicContext::currentId()) {
            $doctorQuery->whereHas('clinics', function ($q) use ($clinicScopeId) {
                $q->where('clinics.id', $clinicScopeId);
            });
        }
        $doctors = $doctorQuery->get();

        $departments = \App\Models\Department::where('is_active', '=', true, 'and')->get();
        $specializations = \App\Models\Specialization::where('is_active', '=', true, 'and')->get();
        $clinics = \App\Models\Clinic::query()->where('is_active', true)->orderBy('name')->get();

        return view('appointments.index', compact('appointments', 'doctors', 'departments', 'specializations', 'clinics'));
    }

    public function create()
    {
        $doctorQuery = User::where('role', '=', 'doctor', 'and')
            ->where('is_active', '=', true, 'and')
            ->with(['department', 'specialization', 'clinics']);

        if ($clinicScopeId = ClinicContext::currentId()) {
            $doctorQuery->whereHas('clinics', function ($q) use ($clinicScopeId) {
                $q->where('clinics.id', $clinicScopeId);
            });
        }

        $doctors = $doctorQuery->get();
        $clinics = \App\Models\Clinic::query()->where('is_active', true)->orderBy('name')->get();

        return view('appointments.create', compact('doctors', 'clinics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'clinic_id' => 'nullable|exists:clinics,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|in:checkup,consultation',
            'notes' => 'nullable|string',
        ]);

        // Combine date and time
        $appointmentDateTime = $validated['appointment_date'].' '.$validated['appointment_time'].':00';
        $appointmentDateTimeObj = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointmentDateTime);

        // Validate that the appointment is in the future
        if ($appointmentDateTimeObj->isPast()) {
            return back()->withErrors(['appointment_time' => 'يجب أن يكون الموعد في المستقبل.'])->withInput();
        }

        $validated['appointment_date'] = $appointmentDateTimeObj;

        // Check for conflicts
        $conflict = Appointment::where('doctor_id', '=', $validated['doctor_id'], 'and')
            ->where('appointment_date', '=', $validated['appointment_date'], 'and')
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'هذا الموعد محجوز مسبقاً.'])->withInput();
        }

        unset($validated['appointment_time']);
        $validated['status'] = 'pending';

        $currentUser = Auth::user();
        $validated['created_by'] = $currentUser ? $currentUser->getKey() : null;

        // Auto-assign clinic to current branch when none provided and user is locked to a clinic
        if (empty($validated['clinic_id']) && ($lockedClinic = ClinicContext::currentId())) {
            $validated['clinic_id'] = $lockedClinic;
        }

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('success', 'تم إضافة الموعد بنجاح.');
    }

    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        if ($user && $user->isDoctor() && (int) $appointment->doctor_id !== (int) $user->getKey()) {
            abort(403, 'Unauthorized access.');
        }

        if (($scopeId = ClinicContext::currentId()) && (int) $appointment->clinic_id !== $scopeId) {
            abort(403, 'هذا الموعد ينتمي إلى فرع آخر.');
        }

        $appointment->load(['patient', 'doctor', 'clinic', 'creator', 'prescription', 'invoice']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        if (($scopeId = ClinicContext::currentId()) && (int) $appointment->clinic_id !== $scopeId) {
            abort(403, 'هذا الموعد ينتمي إلى فرع آخر.');
        }

        $doctorQuery = User::where('role', '=', 'doctor', 'and')
            ->where('is_active', '=', true, 'and')
            ->with(['department', 'specialization', 'clinics']);

        if ($scopeId = ClinicContext::currentId()) {
            $doctorQuery->whereHas('clinics', function ($q) use ($scopeId) {
                $q->where('clinics.id', $scopeId);
            });
        }

        $doctors = $doctorQuery->get();
        $clinics = \App\Models\Clinic::query()->where('is_active', true)->orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'doctors', 'clinics'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        // If only status is being updated (quick action)
        if (
            $request->has('status') &&
            ! $request->has('patient_id') &&
            ! $request->has('doctor_id') &&
            ! $request->has('appointment_date')
        ) {
            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,completed,canceled',
            ]);

            $appointment->update($validated);

            return redirect()->route('appointments.show', $appointment->id)
                ->with('success', 'تم تحديث حالة الموعد بنجاح.');
        }

        // Full update: same date + time fields as store
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'clinic_id' => 'nullable|exists:clinics,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|in:checkup,consultation',
            'status' => 'required|in:pending,confirmed,completed,canceled',
            'notes' => 'nullable|string',
        ]);

        $appointmentDateTime = $validated['appointment_date'].' '.$validated['appointment_time'].':00';
        $appointmentDateTimeObj = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointmentDateTime);

        if (
            in_array($validated['status'], ['pending', 'confirmed'], true) &&
            $appointmentDateTimeObj->isPast()
        ) {
            return back()->withErrors(['appointment_time' => 'يجب أن يكون الموعد في المستقبل.'])->withInput();
        }

        $validated['appointment_date'] = $appointmentDateTimeObj;
        unset($validated['appointment_time']);

        // Check for conflicts (excluding current appointment)
        $conflict = Appointment::where('doctor_id', '=', $validated['doctor_id'], 'and')
            ->where('appointment_date', '=', $validated['appointment_date'], 'and')
            ->where('id', '!=', $appointment->id, 'and')
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'هذا الموعد محجوز مسبقاً.'])->withInput();
        }

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment->id)
            ->with('success', 'تم تحديث الموعد بنجاح.');
    }

    public function destroy(Appointment $appointment)
    {
        if (($scopeId = ClinicContext::currentId()) && (int) $appointment->clinic_id !== $scopeId) {
            abort(403, 'هذا الموعد ينتمي إلى فرع آخر.');
        }

        if ($appointment->status === 'completed') {
            return back()->withErrors(['error' => 'لا يمكن حذف موعد مكتمل.']);
        }

        Appointment::destroy($appointment->getKey());

        return redirect()->route('appointments.index')->with('success', 'تم حذف الموعد بنجاح.');
    }
}
