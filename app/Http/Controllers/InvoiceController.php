<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Support\ClinicContext;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_invoices')->only(['index', 'show']);
        $this->middleware('permission:create_invoices')->only(['create', 'store']);
        $this->middleware('permission:edit_invoices')->only(['edit', 'update']);
        $this->middleware('permission:print_invoices')->only(['print']);
    }

    public function index(Request $request)
    {
        $query = Invoice::with(['patient', 'appointment', 'creator', 'payments']);

        // Scope by current clinic context
        if ($clinicId = ClinicContext::currentId()) {
            $query->where(function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId)
                    ->orWhereHas('appointment', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
        }

        // البحث برقم الفاتورة أو اسم المريض
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%'.$search.'%')
                    ->orWhereHas('patient', function ($q2) use ($search) {
                        $q2->where('full_name', 'like', '%'.$search.'%')
                            ->orWhere('phone_number', 'like', '%'.$search.'%');
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

        $invoices = $query->latest()->paginate(15)->withQueryString();

        $patientsQuery = Patient::latest();
        if ($clinicId = ClinicContext::currentId()) {
            $patientsQuery->where(function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId)
                    ->orWhereHas('appointments', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
        }
        $patients = $patientsQuery->get();

        // إحصائيات بناءً على الفلاتر المطبقة
        $statsQuery = Invoice::query();

        if ($clinicId = ClinicContext::currentId()) {
            $statsQuery->where(function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId)
                    ->orWhereHas('appointment', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%'.$search.'%')
                    ->orWhereHas('patient', function ($q2) use ($search) {
                        $q2->where('full_name', 'like', '%'.$search.'%')
                            ->orWhere('phone_number', 'like', '%'.$search.'%');
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
            'paid_amount' => $filteredInvoices->sum(function ($invoice) {
                return $invoice->paid_amount;
            }),
            'unpaid_amount' => $filteredInvoices->sum(function ($invoice) {
                return $invoice->remaining_amount;
            }),
        ];

        return view('invoices.index', compact('invoices', 'patients', 'stats'));
    }

    public function create()
    {
        $patientsQuery = Patient::latest();
        $appointmentsQuery = Appointment::whereDoesntHave('invoice')
            ->where('status', 'completed')
            ->with('patient')
            ->latest();

        if ($clinicId = ClinicContext::currentId()) {
            $patientsQuery->where(function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId)
                    ->orWhereHas('appointments', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
            $appointmentsQuery->where('clinic_id', $clinicId);
        }

        $patients = $patientsQuery->get();
        $appointments = $appointmentsQuery->get();

        return view('invoices.create', compact('patients', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => ['nullable', 'exists:appointments,id', \Illuminate\Validation\Rule::unique('invoices', 'appointment_id')],
            'consultation_fee' => 'required|numeric|min:0',
        ]);

        if (! empty($validated['appointment_id'])) {
            $appointment = Appointment::findOrFail($validated['appointment_id']);
            if ((int) $appointment->patient_id !== (int) $validated['patient_id']) {
                return back()->withErrors(['appointment_id' => 'الموعد المحدد لا يخص هذا المريض.'])->withInput();
            }
        }

        $validated['total_amount'] = $validated['consultation_fee'];
        $validated['status'] = 'unpaid';
        $validated['created_by'] = auth()->id();

        // Auto-assign clinic from the linked appointment, branch context, or assigned clinic
        if (! empty($validated['appointment_id'])) {
            $apt = Appointment::find($validated['appointment_id']);
            $validated['clinic_id'] = $apt?->clinic_id;
        }
        if (empty($validated['clinic_id'])) {
            $validated['clinic_id'] = ClinicContext::currentId() ?? auth()->user()?->clinic_id;
        }

        $invoice = Invoice::create($validated);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $this->assertInvoiceInScope($invoice);

        $invoice->load(['patient', 'appointment', 'creator', 'payments.receiver']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->assertInvoiceInScope($invoice);

        if ($invoice->status === 'paid') {
            return back()->withErrors(['error' => 'Cannot edit a paid invoice.']);
        }

        $patientsQuery = Patient::latest();
        $appointmentsQuery = Appointment::with('patient')->latest();

        if ($clinicId = ClinicContext::currentId()) {
            $patientsQuery->where(function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId)
                    ->orWhereHas('appointments', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
            $appointmentsQuery->where('clinic_id', $clinicId);
        }

        $patients = $patientsQuery->get();
        $appointments = $appointmentsQuery->get();

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

        if (! empty($validated['appointment_id'])) {
            $appointment = Appointment::findOrFail($validated['appointment_id']);
            if ((int) $appointment->patient_id !== (int) $validated['patient_id']) {
                return back()->withErrors(['appointment_id' => 'الموعد المحدد لا يخص هذا المريض.'])->withInput();
            }
        }

        $validated['total_amount'] = $validated['consultation_fee'];

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice updated successfully.');
    }

    public function print(Invoice $invoice)
    {
        $this->assertInvoiceInScope($invoice);

        $invoice->load(['patient', 'appointment', 'payments']);

        return view('invoices.print', compact('invoice'));
    }

    private function assertInvoiceInScope(Invoice $invoice): void
    {
        $clinicId = ClinicContext::currentId();
        if (! $clinicId) {
            return;
        }

        $belongs = (int) $invoice->clinic_id === $clinicId
            || ($invoice->appointment && (int) $invoice->appointment->clinic_id === $clinicId);

        if (! $belongs) {
            abort(403, 'هذه الفاتورة تنتمي إلى فرع آخر.');
        }
    }
}
