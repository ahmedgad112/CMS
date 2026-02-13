<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor', 'creator']);

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Filter by specialization
        if ($request->filled('specialization_id')) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('specialization_id', $request->specialization_id);
            });
        }

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by appointment type
        if ($request->filled('appointment_type')) {
            $appointmentType = $request->appointment_type;
            // Handle both new appointments with type and old ones without type (default to checkup)
            if ($appointmentType == 'checkup') {
                $query->where(function ($q) {
                    $q->where('appointment_type', 'checkup')
                        ->orWhereNull('appointment_type');
                });
            } else {
                $query->where('appointment_type', $appointmentType);
            }
        }

        // If user is a doctor, show only their appointments
        $user = Auth::user();
        if ($user instanceof User && $user->isDoctor()) {
            $query->where('doctor_id', Auth::id());
        }

        $appointments = $query->latest('appointment_date')->paginate(15);
        $doctors = User::where('role', 'doctor')->where('is_active', true)->get();
        $departments = \App\Models\Department::where('is_active', true)->get();
        $specializations = \App\Models\Specialization::where('is_active', true)->get();

        return view('appointments.index', compact('appointments', 'doctors', 'departments', 'specializations'));
    }

    public function create()
    {
        $patients = Patient::latest()->get();
        $doctors = User::where('role', 'doctor')
            ->where('is_active', true)
            ->with(['department', 'specialization'])
            ->get();

        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|in:checkup,consultation',
            'notes' => 'nullable|string',
        ]);

        // Combine date and time
        $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'] . ':00';
        $appointmentDateTimeObj = Carbon::createFromFormat('Y-m-d H:i:s', $appointmentDateTime);

        // Validate that the appointment is in the future
        if ($appointmentDateTimeObj->isPast()) {
            return back()->withErrors(['appointment_time' => 'يجب أن يكون الموعد في المستقبل.'])->withInput();
        }

        $validated['appointment_date'] = $appointmentDateTimeObj;

        // Check for conflicts
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'هذا الموعد محجوز مسبقاً.'])->withInput();
        }

        unset($validated['appointment_time']);
        $validated['status'] = 'pending';
        $validated['created_by'] = Auth::id();

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('success', 'تم إضافة الموعد بنجاح.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeDoctorAppointment($appointment);
        $appointment->load(['patient', 'doctor', 'creator', 'prescription', 'invoice']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorizeDoctorAppointment($appointment);
        $patients = Patient::latest()->get();
        $doctors = User::where('role', 'doctor')->where('is_active', true)->get();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeDoctorAppointment($appointment);

        // If only status is being updated (quick action)
        if ($request->has('status') && !$request->has('patient_id') && !$request->has('doctor_id') && !$request->has('appointment_date')) {
            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,completed,canceled',
            ]);

            $appointment->update($validated);

            return redirect()->route('appointments.show', $appointment->id)
                ->with('success', 'تم تحديث حالة الموعد بنجاح.');
        }

        // Full update (appointment_date comes as datetime-local: Y-m-d\TH:i)
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_type' => 'required|in:checkup,consultation',
            'status' => 'required|in:pending,confirmed,completed,canceled',
            'notes' => 'nullable|string',
        ]);

        $appointmentDateTimeObj = Carbon::parse($validated['appointment_date']);
        $validated['appointment_date'] = $appointmentDateTimeObj;

        // Check for conflicts (excluding current appointment)
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $appointmentDateTimeObj)
            ->where('id', '!=', $appointment->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_date' => 'هذا الموعد محجوز مسبقاً.'])->withInput();
        }

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment->id)
            ->with('success', 'تم تحديث الموعد بنجاح.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorizeDoctorAppointment($appointment);

        if ($appointment->status === 'completed') {
            return back()->withErrors(['error' => 'لا يمكن حذف موعد مكتمل.']);
        }

        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'تم حذف الموعد بنجاح.');
    }

    /**
     * Ensure doctors can only access their own appointments; admins/others can access any.
     */
    private function authorizeDoctorAppointment(Appointment $appointment): void
    {
        $user = Auth::user();
        if ($user instanceof User && $user->isDoctor() && $appointment->doctor_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا الموعد.');
        }
    }
}
