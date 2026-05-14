<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Models\Patient;
use App\Models\User;
use App\Support\ClinicContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_appointment_requests')->only(['index', 'show']);
        $this->middleware('permission:process_appointment_requests')->only(['process', 'cancel']);
    }

    /**
     * Display a listing of pending/processed appointment requests.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = AppointmentRequest::with(['patient', 'specialization', 'preferredDoctor', 'preferredClinic', 'processor', 'appointment']);

        // Scope appointment requests by the active clinic (preferred clinic OR processed into one)
        if ($clinicId = ClinicContext::currentId()) {
            $query->where(function ($q) use ($clinicId) {
                $q->where('preferred_clinic_id', $clinicId)
                    ->orWhereHas('appointment', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
        }

        if (in_array($status, ['pending', 'processed', 'canceled'], true)) {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $like = "%{$search}%";
            $query->where(function ($q) use ($like) {
                $q->whereHas('patient', function ($pq) use ($like) {
                    $pq->where('full_name', 'like', $like)
                        ->orWhere('phone_number', 'like', $like);
                })->orWhere(function ($gq) use ($like) {
                    $gq->where('guest_payload->full_name', 'like', $like)
                        ->orWhere('guest_payload->phone_number', 'like', $like);
                });
            });
        }

        $appointmentRequests = $query->latest()->paginate(15)->withQueryString();

        $countsBase = AppointmentRequest::query();
        if ($clinicId = ClinicContext::currentId()) {
            $countsBase->where(function ($q) use ($clinicId) {
                $q->where('preferred_clinic_id', $clinicId)
                    ->orWhereHas('appointment', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
        }
        $counts = [
            'pending' => (clone $countsBase)->where('status', 'pending')->count(),
            'processed' => (clone $countsBase)->where('status', 'processed')->count(),
            'canceled' => (clone $countsBase)->where('status', 'canceled')->count(),
        ];

        return view('appointment-requests.index', compact('appointmentRequests', 'counts', 'status'));
    }

    /**
     * Show a single request and the form to process it into an appointment.
     */
    public function show(AppointmentRequest $appointmentRequest)
    {
        $appointmentRequest->load(['patient', 'specialization.department', 'preferredDoctor.specialization', 'preferredDoctor.department', 'preferredClinic', 'processor', 'appointment']);

        $doctorsQuery = User::query()
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->with(['department:id,name', 'specialization:id,name', 'clinics']);

        if ($appointmentRequest->specialization_id) {
            $doctorsQuery->where('specialization_id', $appointmentRequest->specialization_id);
        }

        $doctors = $doctorsQuery->orderBy('name')->get();

        if ($doctors->isEmpty() && $appointmentRequest->specialization_id) {
            $doctors = User::query()
                ->where('role', 'doctor')
                ->where('is_active', true)
                ->with(['department:id,name', 'specialization:id,name', 'clinics'])
                ->orderBy('name')
                ->get();
        }

        $clinics = \App\Models\Clinic::query()->where('is_active', true)->orderBy('name')->get();

        return view('appointment-requests.show', compact('appointmentRequest', 'doctors', 'clinics'));
    }

    /**
     * Convert a pending request into a real appointment.
     */
    public function process(Request $request, AppointmentRequest $appointmentRequest)
    {
        if ($appointmentRequest->status !== 'pending') {
            return redirect()->route('appointment-requests.show', $appointmentRequest->id)
                ->withErrors(['status' => 'لا يمكن معالجة هذا الطلب لأنه ليس قيد الانتظار.']);
        }

        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'clinic_id' => 'nullable|exists:clinics,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        $appointmentDateTime = $validated['appointment_date'].' '.$validated['appointment_time'].':00';
        $appointmentDateTimeObj = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointmentDateTime);

        if ($appointmentDateTimeObj->isPast()) {
            return back()->withErrors(['appointment_time' => 'يجب أن يكون الموعد في المستقبل.'])->withInput();
        }

        $conflict = Appointment::query()
            ->where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $appointmentDateTimeObj)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'هذا الموعد محجوز مسبقاً، اختر وقتاً آخر.'])->withInput();
        }

        $guest = $appointmentRequest->guest_payload;
        $patientId = $appointmentRequest->patient_id;

        if (! $patientId) {
            if (! is_array($guest) || empty($guest['phone_number'])) {
                return back()->withErrors(['error' => 'بيانات المريض غير مكتملة في الطلب.'])->withInput();
            }

            $clinicId = $validated['clinic_id'] ?? $appointmentRequest->preferred_clinic_id;

            $patient = Patient::query()->firstOrCreate(
                ['phone_number' => $guest['phone_number']],
                [
                    'full_name' => $guest['full_name'],
                    'gender' => $guest['gender'],
                    'age' => (int) ($guest['age'] ?? 0),
                    'medical_history' => $guest['medical_history'] ?? null,
                    'chronic_diseases' => $guest['chronic_diseases'] ?? null,
                    'created_by' => Auth::id(),
                    'clinic_id' => $clinicId,
                ]
            );

            if (! $patient->wasRecentlyCreated) {
                $updates = [];
                if (empty($patient->clinic_id) && $clinicId) {
                    $updates['clinic_id'] = $clinicId;
                }
                if (! empty($updates)) {
                    $patient->update($updates);
                }
            }

            $patientId = $patient->id;
            $appointmentRequest->patient_id = $patientId;
            $appointmentRequest->save();
        }

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $validated['doctor_id'],
            'clinic_id' => $validated['clinic_id'] ?? $appointmentRequest->preferred_clinic_id,
            'appointment_date' => $appointmentDateTimeObj,
            'appointment_type' => $appointmentRequest->service_type,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? $appointmentRequest->notes,
            'created_by' => Auth::id(),
        ]);

        $appointmentRequest->update([
            'status' => 'processed',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'appointment_id' => $appointment->id,
        ]);

        return redirect()->route('appointments.show', $appointment->id)
            ->with('success', 'تم تأكيد طلب الحجز وإنشاء الموعد بنجاح.');
    }

    /**
     * Cancel a pending request.
     */
    public function cancel(AppointmentRequest $appointmentRequest)
    {
        if ($appointmentRequest->status !== 'pending') {
            return redirect()->route('appointment-requests.show', $appointmentRequest->id)
                ->withErrors(['status' => 'لا يمكن إلغاء هذا الطلب.']);
        }

        $appointmentRequest->update([
            'status' => 'canceled',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('appointment-requests.index')
            ->with('success', 'تم إلغاء طلب الحجز.');
    }
}
