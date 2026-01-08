<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::with(['patient', 'appointment', 'doctor']);

        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        } elseif (auth()->user()->isDoctor() && !auth()->user()->isAdmin()) {
            // If user is a doctor (not admin), show only their prescriptions
            $query->where('doctor_id', auth()->id());
        }

        $prescriptions = $query->latest()->paginate(15);
        
        // Get all doctors for filter
        $doctors = \App\Models\User::where('role', 'doctor')->where('is_active', true)->get();

        return view('doctor.prescriptions.index', compact('prescriptions', 'doctors'));
    }

    public function create(Request $request)
    {
        $appointment = null;
        
        // Support appointment_id to create prescription directly from appointment
        if ($request->has('appointment_id')) {
            $appointment = Appointment::with(['patient'])->findOrFail($request->appointment_id);
            
            // Verify doctor owns the appointment
            if ($appointment->doctor_id !== auth()->id() && !auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized.');
            }

            // Check if prescription already exists
            if ($appointment->prescription) {
                return redirect()->route('doctor.prescriptions.show', $appointment->prescription->id)
                    ->with('info', 'الروشته موجودة بالفعل لهذا الموعد.');
            }
        }

        return view('doctor.prescriptions.create', compact('appointment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id|unique:prescriptions,appointment_id',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_name' => 'required|string',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.frequency' => 'required|string',
            'medicines.*.duration' => 'required|string',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        if ($appointment->doctor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $prescription = Prescription::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['medicines'] as $medicine) {
            $prescription->items()->create([
                'medicine_name' => $medicine['medicine_name'],
                'dosage' => $medicine['dosage'],
                'frequency' => $medicine['frequency'],
                'duration' => $medicine['duration'],
                'instructions' => $medicine['instructions'] ?? null,
            ]);
        }

        // Update appointment status to completed
        $appointment->update(['status' => 'completed']);

        // Create invoice automatically if it doesn't exist
        if (!$appointment->invoice) {
            $doctor = $appointment->doctor;
            $fee = $appointment->appointment_type == 'checkup' 
                ? ($doctor->checkup_fee ?? 0) 
                : ($doctor->consultation_fee ?? 0);
            
            \App\Models\Invoice::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'consultation_fee' => $fee,
                'total_amount' => $fee,
                'status' => 'unpaid',
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('doctor.prescriptions.show', $prescription->id)
            ->with('success', 'تم إنشاء الروشته بنجاح.');
    }

    public function show(Prescription $prescription)
    {
        if ($prescription->doctor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $prescription->load(['patient', 'doctor', 'appointment', 'items']);
        return view('doctor.prescriptions.show', compact('prescription'));
    }

    public function print(Prescription $prescription)
    {
        if ($prescription->doctor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $prescription->load(['patient', 'doctor', 'items']);
        return view('doctor.prescriptions.print', compact('prescription'));
    }
}

