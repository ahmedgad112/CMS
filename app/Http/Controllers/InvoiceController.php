<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['patient', 'appointment', 'creator', 'payments']);

        // البحث برقم الفاتورة أو اسم المريض
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhereHas('patient', function($q2) use ($search) {
                      $q2->where('full_name', 'like', '%' . $search . '%')
                         ->orWhere('phone_number', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $invoices = $query->latest()->paginate(15);
        $patients = Patient::latest()->get();

        // إحصائيات بناءً على الفلاتر المطبقة
        $statsQuery = Invoice::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhereHas('patient', function($q2) use ($search) {
                      $q2->where('full_name', 'like', '%' . $search . '%')
                         ->orWhere('phone_number', 'like', '%' . $search . '%');
                  });
            });
        }
        
        if ($request->filled('status')) {
            $statsQuery->where('status', $request->status);
        }
        
        if ($request->filled('patient_id')) {
            $statsQuery->where('patient_id', $request->patient_id);
        }

        $filteredInvoices = $statsQuery->get();
        
        $stats = [
            'total_invoices' => $filteredInvoices->count(),
            'total_amount' => $filteredInvoices->sum('total_amount'),
            'paid_amount' => $filteredInvoices->sum(function($invoice) {
                return $invoice->paid_amount;
            }),
            'unpaid_amount' => $filteredInvoices->sum(function($invoice) {
                return $invoice->remaining_amount;
            }),
        ];

        return view('invoices.index', compact('invoices', 'patients', 'stats'));
    }

    public function create()
    {
        $patients = Patient::latest()->get();
        $appointments = Appointment::whereDoesntHave('invoice')
            ->where('status', 'completed')
            ->with('patient')
            ->latest()
            ->get();

        return view('invoices.create', compact('patients', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => ['nullable', 'exists:appointments,id', \Illuminate\Validation\Rule::unique('invoices', 'appointment_id')],
            'consultation_fee' => 'required|numeric|min:0',
        ]);

        $validated['total_amount'] = $validated['consultation_fee'];
        $validated['status'] = 'unpaid';
        $validated['created_by'] = auth()->id();

        $invoice = Invoice::create($validated);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'appointment', 'creator', 'payments.receiver']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->withErrors(['error' => 'Cannot edit a paid invoice.']);
        }

        $patients = Patient::latest()->get();
        $appointments = Appointment::with('patient')->latest()->get();

        return view('invoices.edit', compact('invoice', 'patients', 'appointments'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->withErrors(['error' => 'Cannot update a paid invoice.']);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => ['nullable', 'exists:appointments,id', \Illuminate\Validation\Rule::unique('invoices', 'appointment_id')->ignore($invoice->id)],
            'consultation_fee' => 'required|numeric|min:0',
        ]);

        $validated['total_amount'] = $validated['consultation_fee'];

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice updated successfully.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['patient', 'appointment', 'payments']);
        return view('invoices.print', compact('invoice'));
    }
}
