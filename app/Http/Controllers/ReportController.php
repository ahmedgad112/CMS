<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Support\ClinicContext;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_reports')->only(['index']);
        $this->middleware('permission:financial_reports')->only(['financial']);
        $this->middleware('permission:doctor_reports')->only(['doctorPerformance']);
    }

    public function index()
    {
        return view('reports.index');
    }

    public function financial(Request $request)
    {
        $query = Invoice::with(['patient']);

        if ($clinicId = ClinicContext::currentId()) {
            $query->where(function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId)
                    ->orWhereHas('appointment', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
        }

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $invoices = $query->latest()->get();
        $totalRevenue = $invoices->where('status', 'paid')->sum('total_amount');
        $unpaidAmount = $invoices->where('status', 'unpaid')->sum('total_amount');

        return view('reports.financial', compact('invoices', 'totalRevenue', 'unpaidAmount'));
    }

    public function doctorPerformance(Request $request)
    {
        $query = User::where('role', 'doctor')
            ->withCount(['prescriptions']);

        // Limit doctors list to the active clinic when scoped
        if ($clinicId = ClinicContext::currentId()) {
            $query->whereHas('clinics', function ($q) use ($clinicId) {
                $q->where('clinics.id', $clinicId);
            });
        }

        if ($request->has('doctor_id')) {
            $query->where('id', $request->doctor_id);
        }

        $doctors = $query->get();

        return view('reports.doctor-performance', compact('doctors'));
    }
}
