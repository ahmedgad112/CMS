<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Support\ClinicContext;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_dashboard');
    }

    public function index()
    {
        $user = auth()->user();
        $clinicId = ClinicContext::currentId();
        $data = [];

        $appointmentScope = fn ($q) => $clinicId ? $q->where('clinic_id', $clinicId) : $q;
        $invoiceScope = fn ($q) => $clinicId
            ? $q->where(function ($qq) use ($clinicId) {
                $qq->where('clinic_id', $clinicId)
                    ->orWhereHas('appointment', function ($a) use ($clinicId) {
                        $a->where('clinic_id', $clinicId);
                    });
            })
            : $q;
        $patientScope = fn ($q) => $clinicId
            ? $q->where(function ($qq) use ($clinicId) {
                $qq->where('clinic_id', $clinicId)
                    ->orWhereHas('appointments', function ($a) use ($clinicId) {
                        $a->where('clinic_id', $clinicId);
                    });
            })
            : $q;

        // Common stats for all users (scoped to active clinic)
        $data['today_appointments'] = $appointmentScope(Appointment::query())
            ->whereDate('appointment_date', today())->count();
        $data['pending_appointments'] = $appointmentScope(Appointment::query())
            ->where('status', 'pending')->count();
        $data['total_patients'] = $patientScope(Patient::query())->count();

        // Role-specific data
        if ($user->isDoctor()) {
            $data['my_appointments'] = $appointmentScope(Appointment::query())
                ->where('doctor_id', $user->id)
                ->whereDate('appointment_date', today())
                ->count();
            $data['my_pending_appointments'] = $appointmentScope(Appointment::query())
                ->where('doctor_id', $user->id)
                ->where('status', 'confirmed')
                ->whereDate('appointment_date', '<=', today())
                ->count();
        }

        if ($user->hasPermission('financial_reports')) {
            $data['today_revenue'] = $invoiceScope(Invoice::query())
                ->whereDate('created_at', today())
                ->where('status', 'paid')
                ->sum('total_amount');
            $data['monthly_revenue'] = $invoiceScope(Invoice::query())
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'paid')
                ->sum('total_amount');
        }

        if ($user->hasPermission('view_invoices')) {
            $data['unpaid_invoices'] = $invoiceScope(Invoice::query())
                ->where('status', 'unpaid')->count();
        }

        return view('dashboard', compact('data'));
    }
}
