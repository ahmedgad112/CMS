<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function financial(Request $request)
    {
        $query = Invoice::with(['patient']);

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

        if ($request->has('doctor_id')) {
            $query->where('id', $request->doctor_id);
        }

        $doctors = $query->get();

        return view('reports.doctor-performance', compact('doctors'));
    }

}
