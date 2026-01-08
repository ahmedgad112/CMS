<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Patient::query();

        if ($request->has('search')) {
            $search = $request->search;
            $baseQuery->where(function($q) use ($search) {
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

        $patients = $baseQuery->with('creator')->latest()->paginate(15);

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
                Rule::unique('patients', 'phone_number')
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
            $chronicDiseasesArray = array_filter($chronicDiseasesArray, function($item) {
                return $item !== 'other';
            });
            $chronicDiseasesArray[] = $request->other_disease;
        }
        $validated['chronic_diseases'] = !empty($chronicDiseasesArray) ? json_encode($chronicDiseasesArray) : null;
        unset($validated['other_disease']);

        $validated['created_by'] = auth()->id();

        $patient = Patient::create($validated);

        // Check if there's a return_to parameter
        if ($request->has('return_to')) {
            $returnUrl = $request->return_to;
            // Add patient_id to the return URL
            $separator = strpos($returnUrl, '?') !== false ? '&' : '?';
            return redirect($returnUrl . $separator . 'patient_id=' . $patient->id)
                ->with('success', 'تم إضافة المريض بنجاح. يمكنك الآن إضافة الموعد.');
        }

        return redirect()->route('patients.index')->with('success', 'تم إضافة المريض بنجاح.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments.doctor', 'prescriptions.doctor', 'invoices']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                Rule::unique('patients', 'phone_number')->ignore($patient->id)
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
            $chronicDiseasesArray = array_filter($chronicDiseasesArray, function($item) {
                return $item !== 'other';
            });
            $chronicDiseasesArray[] = $request->other_disease;
        }
        $validated['chronic_diseases'] = !empty($chronicDiseasesArray) ? json_encode($chronicDiseasesArray) : null;
        unset($validated['other_disease']);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'تم تحديث بيانات المريض بنجاح.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}
