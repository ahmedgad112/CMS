<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Support\ClinicContext;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_patients')->only(['index', 'show']);
        $this->middleware('permission:create_patients')->only(['create', 'store']);
        $this->middleware('permission:edit_patients')->only(['edit', 'update']);
        $this->middleware('permission:delete_patients')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $baseQuery = Patient::query();

        // Scope by clinic context: include patients registered at this clinic OR
        // who have at least one appointment in it (so the receptionist still sees them).
        if ($clinicId = ClinicContext::currentId()) {
            $baseQuery->where(function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId)
                    ->orWhereHas('appointments', function ($qq) use ($clinicId) {
                        $qq->where('clinic_id', $clinicId);
                    });
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Get statistics from base query (before pagination)
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'male' => (clone $baseQuery)->where('gender', 'male')->count(),
            'female' => (clone $baseQuery)->where('gender', 'female')->count(),
        ];

        $patients = $baseQuery->with('creator')->latest()->paginate(15)->withQueryString();

        return view('patients.index', compact('patients', 'stats'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                Rule::unique('patients', 'phone_number'),
            ],
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:0|max:150',
            'medical_history' => 'nullable|string',
            'chronic_diseases' => 'nullable|array',
            'chronic_diseases.*' => 'string',
            'other_disease' => 'nullable|string|max:255',
        ], [
            'phone_number.unique' => 'رقم الهاتف موجود مسبقاً. يرجى التحقق من رقم الهاتف أو البحث عن المريض.',
            'phone_number.required' => 'رقم الهاتف مطلوب.',
        ]);

        // Process chronic diseases
        $chronicDiseasesArray = $request->chronic_diseases ?? [];
        if (in_array('other', $chronicDiseasesArray) && $request->other_disease) {
            // Remove 'other' and add the custom disease
            $chronicDiseasesArray = array_filter($chronicDiseasesArray, function ($item) {
                return $item !== 'other';
            });
            $chronicDiseasesArray[] = $request->other_disease;
        }
        $validated['chronic_diseases'] = ! empty($chronicDiseasesArray) ? json_encode($chronicDiseasesArray) : null;
        unset($validated['other_disease']);

        $validated['created_by'] = auth()->id();
        $validated['clinic_id'] = ClinicContext::currentId() ?? auth()->user()?->clinic_id;

        $patient = Patient::create($validated);

        // Check if there's a return_to parameter
        if ($request->has('return_to')) {
            $returnUrl = $request->return_to;
            // Add patient_id to the return URL
            $separator = strpos($returnUrl, '?') !== false ? '&' : '?';

            return redirect($returnUrl.$separator.'patient_id='.$patient->id)
                ->with('success', 'تم إضافة المريض بنجاح. يمكنك الآن إضافة الموعد.');
        }

        return redirect()->route('patients.index')->with('success', 'تم إضافة المريض بنجاح.');
    }

    public function show(Patient $patient)
    {
        $this->assertPatientInScope($patient);

        $patient->load([
            'clinic',
            'appointments' => fn ($q) => $q->with('doctor')->latest('appointment_date'),
            'prescriptions.doctor',
            'invoices',
        ]);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $this->assertPatientInScope($patient);

        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                Rule::unique('patients', 'phone_number')->ignore($patient->id),
            ],
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:0|max:150',
            'medical_history' => 'nullable|string',
            'chronic_diseases' => 'nullable|array',
            'chronic_diseases.*' => 'string',
            'other_disease' => 'nullable|string|max:255',
        ], [
            'phone_number.unique' => 'رقم الهاتف موجود مسبقاً. يرجى التحقق من رقم الهاتف أو البحث عن المريض.',
            'phone_number.required' => 'رقم الهاتف مطلوب.',
        ]);

        // Process chronic diseases
        $chronicDiseasesArray = $request->chronic_diseases ?? [];
        if (in_array('other', $chronicDiseasesArray) && $request->other_disease) {
            // Remove 'other' and add the custom disease
            $chronicDiseasesArray = array_filter($chronicDiseasesArray, function ($item) {
                return $item !== 'other';
            });
            $chronicDiseasesArray[] = $request->other_disease;
        }
        $validated['chronic_diseases'] = ! empty($chronicDiseasesArray) ? json_encode($chronicDiseasesArray) : null;
        unset($validated['other_disease']);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'تم تحديث بيانات المريض بنجاح.');
    }

    public function destroy(Patient $patient)
    {
        $this->assertPatientInScope($patient);

        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'تم حذف المريض بنجاح.');
    }

    /**
     * Block access if the current user is scoped to a clinic and the
     * patient neither belongs to it nor has any appointment in it.
     */
    private function assertPatientInScope(Patient $patient): void
    {
        $clinicId = ClinicContext::currentId();
        if (! $clinicId) {
            return;
        }

        $belongs = (int) $patient->clinic_id === $clinicId
            || $patient->appointments()->where('clinic_id', $clinicId)->exists();

        if (! $belongs) {
            abort(403, 'هذا المريض ينتمي إلى فرع آخر.');
        }
    }
}
