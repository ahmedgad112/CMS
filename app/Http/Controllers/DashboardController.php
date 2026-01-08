<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        // Common stats for all users
        $data['today_appointments'] = Appointment::whereDate('appointment_date', today())->count();
        $data['pending_appointments'] = Appointment::where('status', 'pending')->count();
        $data['total_patients'] = Patient::count();

        // Role-specific data
        if ($user->isDoctor()) {
            $data['my_appointments'] = Appointment::where('doctor_id', $user->id)
                ->whereDate('appointment_date', today())
                ->count();
            $data['my_pending_appointments'] = Appointment::where('doctor_id', $user->id)
                ->where('status', 'confirmed')
                ->whereDate('appointment_date', '<=', today())
                ->count();
        }

        if ($user->isAccountant() || $user->isAdmin()) {
            $data['today_revenue'] = Invoice::whereDate('created_at', today())
                ->where('status', 'paid')
                ->sum('total_amount');
            $data['unpaid_invoices'] = Invoice::where('status', 'unpaid')->count();
            $data['monthly_revenue'] = Invoice::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'paid')
                ->sum('total_amount');
        }

        if ($user->isStorekeeper() || $user->isAdmin()) {
        }

        return view('dashboard', compact('data'));
    }
}
