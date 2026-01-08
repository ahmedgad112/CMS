<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.patient', 'receiver']);

        // Filter by invoice
        if ($request->filled('invoice_id')) {
            $query->where('invoice_id', $request->invoice_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by invoice number or patient name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('invoice', function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q2) use ($search) {
                      $q2->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(20);

        // Calculate statistics
        $statsQuery = Payment::query();
        if ($request->filled('invoice_id')) {
            $statsQuery->where('invoice_id', $request->invoice_id);
        }
        if ($request->filled('payment_method')) {
            $statsQuery->where('payment_method', $request->payment_method);
        }
        if ($request->filled('date_from')) {
            $statsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $statsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->whereHas('invoice', function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q2) use ($search) {
                      $q2->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $stats = [
            'total_payments' => $statsQuery->count(),
            'total_amount' => $statsQuery->sum('amount'),
        ];

        // Get invoices for filter
        $invoices = Invoice::with('patient')->latest()->get();

        return view('payments.index', compact('payments', 'stats', 'invoices'));
    }

    public function create(Request $request)
    {
        $invoice = null;
        if ($request->has('invoice_id')) {
            $invoice = Invoice::with('patient')->findOrFail($request->invoice_id);
        }

        $invoices = Invoice::where('status', 'unpaid')
            ->with('patient')
            ->latest()
            ->get();

        return view('payments.create', compact('invoice', 'invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,credit_card',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        
        // Calculate current paid amount
        $paidAmount = $invoice->paid_amount;
        $remainingAmount = $invoice->remaining_amount;

        if ($validated['amount'] > $remainingAmount) {
            return back()->withErrors(['amount' => 'المبلغ لا يمكن أن يتجاوز المبلغ المتبقي.'])->withInput();
        }

        $validated['received_by'] = auth()->id();
        $validated['discount'] = 0; // Set discount to 0

        Payment::create($validated);

        // Update invoice status if fully paid
        $newPaidAmount = $paidAmount + $validated['amount'];
        
        if ($newPaidAmount >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        }

        return redirect()->route('payments.index')
            ->with('success', 'تم تسجيل الدفعة بنجاح.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice.patient', 'receiver']);
        return view('payments.show', compact('payment'));
    }
}

