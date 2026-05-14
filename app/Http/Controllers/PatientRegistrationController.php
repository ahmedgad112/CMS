<?php

namespace App\Http\Controllers;

use App\Models\AppointmentRequest;
use App\Models\Clinic;
use App\Models\PlatformSetting;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class PatientRegistrationController extends Controller
{
    private const SESSION_GUEST_KEY = 'registration.guest';

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|null
     */
    private function blockIfOnlineBookingClosed()
    {
        if (PlatformSetting::getBool('public_online_booking_enabled', true)) {
            return null;
        }

        $message = PlatformSetting::getValue('public_portal_disabled_notice')
            ?: 'حجز المواعيد عبر الموقع غير متاح حالياً. نرجو التواصل مع العيادة.';

        return response()->view('registration.disabled', [
            'message' => $message,
        ], 503);
    }

    /**
     * Show the public patient self-registration form.
     */
    public function showForm()
    {
        if ($blocked = $this->blockIfOnlineBookingClosed()) {
            return $blocked;
        }

        return view('registration.form');
    }

    /**
     * Store applicant data in session only (no patient row until staff confirms the request).
     */
    public function register(Request $request)
    {
        if ($blocked = $this->blockIfOnlineBookingClosed()) {
            return $blocked;
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('patients', 'phone_number'),
            ],
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:0|max:150',
            'has_operations' => 'required|in:yes,no',
            'operations_details' => 'nullable|string|max:1000',
            'chronic_diseases' => 'nullable|array',
            'chronic_diseases.*' => 'string',
            'other_disease' => 'nullable|string|max:255',
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب.',
            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'phone_number.unique' => 'رقم الهاتف ده مسجل قبل كده. لو حضرتك مسجل من قبل تواصل مع الاستقبال.',
            'gender.required' => 'النوع مطلوب.',
            'age.required' => 'السن مطلوب.',
            'has_operations.required' => 'من فضلك حدد إذا كان عندك عمليات سابقة أم لا.',
        ]);

        $chronicDiseasesArray = $request->input('chronic_diseases', []);
        if (in_array('other', $chronicDiseasesArray, true) && $request->filled('other_disease')) {
            $chronicDiseasesArray = array_values(array_filter($chronicDiseasesArray, fn ($item) => $item !== 'other'));
            $chronicDiseasesArray[] = $request->input('other_disease');
        }

        $medicalHistoryParts = [];
        if ($validated['has_operations'] === 'yes') {
            $details = $request->input('operations_details');
            $medicalHistoryParts[] = 'العمليات السابقة: '.(! empty($details) ? $details : 'نعم (بدون تفاصيل إضافية)');
        } else {
            $medicalHistoryParts[] = 'لا توجد عمليات سابقة.';
        }

        Session::put(self::SESSION_GUEST_KEY, [
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'gender' => $validated['gender'],
            'age' => $validated['age'],
            'medical_history' => implode("\n", $medicalHistoryParts),
            'chronic_diseases' => ! empty($chronicDiseasesArray)
                ? json_encode($chronicDiseasesArray, JSON_UNESCAPED_UNICODE)
                : null,
        ]);

        return redirect()->route('registration.service')
            ->with('success', 'تم حفظ بياناتك مؤقتاً. اختار نوع الخدمة المطلوبة.');
    }

    /**
     * Show the service selection page (data still in session only).
     */
    public function showService()
    {
        if ($blocked = $this->blockIfOnlineBookingClosed()) {
            return $blocked;
        }

        $guest = Session::get(self::SESSION_GUEST_KEY);
        if (! is_array($guest) || empty($guest['phone_number'])) {
            return redirect()->route('registration.form')
                ->withErrors(['session' => 'انتهت جلسة التسجيل. من فضلك سجل بياناتك من جديد.']);
        }

        $specializations = Specialization::query()
            ->where('is_active', true)
            ->with('department')
            ->orderBy('name')
            ->get(['id', 'name', 'department_id']);

        $doctors = User::query()
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->with(['department:id,name', 'specialization:id,name', 'clinics:id,name'])
            ->orderBy('name')
            ->get(['id', 'name', 'department_id', 'specialization_id']);

        $clinics = Clinic::query()
            ->where('is_active', true)
            ->orderBy('is_main', 'desc')
            ->orderBy('name')
            ->get(['id', 'name', 'city', 'address', 'is_main']);

        return view('registration.service', compact('guest', 'specializations', 'doctors', 'clinics'));
    }

    /**
     * Store the service selection as a pending appointment request (patient_id null until staff confirms).
     */
    public function submitService(Request $request)
    {
        if ($blocked = $this->blockIfOnlineBookingClosed()) {
            return $blocked;
        }

        $guest = Session::get(self::SESSION_GUEST_KEY);
        if (! is_array($guest) || empty($guest['phone_number'])) {
            return redirect()->route('registration.form')
                ->withErrors(['session' => 'انتهت جلسة التسجيل. من فضلك سجل بياناتك من جديد.']);
        }

        $validated = $request->validate([
            'service_type' => 'required|in:checkup,consultation',
            'specialization_id' => 'nullable|exists:specializations,id',
            'preferred_doctor_id' => 'nullable|exists:users,id',
            'preferred_clinic_id' => 'nullable|exists:clinics,id',
            'notes' => 'nullable|string|max:1000',
        ], [
            'service_type.required' => 'من فضلك اختار نوع الخدمة.',
            'specialization_id.exists' => 'التخصص المختار غير صحيح.',
            'preferred_doctor_id.exists' => 'الطبيب المختار غير صحيح.',
            'preferred_clinic_id.exists' => 'العيادة المختارة غير صحيحة.',
        ]);

        if (! empty($validated['preferred_doctor_id'])) {
            $doctor = User::query()
                ->where('id', $validated['preferred_doctor_id'])
                ->where('role', 'doctor')
                ->where('is_active', true)
                ->first();

            if (! $doctor) {
                return back()->withErrors(['preferred_doctor_id' => 'الطبيب المختار غير متاح حالياً.'])->withInput();
            }

            if (! empty($validated['specialization_id']) && (int) $doctor->specialization_id !== (int) $validated['specialization_id']) {
                return back()->withErrors(['preferred_doctor_id' => 'الطبيب المختار لا ينتمي لهذا التخصص.'])->withInput();
            }
        }

        $appointmentRequest = AppointmentRequest::create([
            'patient_id' => null,
            'guest_payload' => $guest,
            'service_type' => $validated['service_type'],
            'specialization_id' => $validated['specialization_id'] ?? null,
            'preferred_doctor_id' => $validated['preferred_doctor_id'] ?? null,
            'preferred_clinic_id' => $validated['preferred_clinic_id'] ?? null,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        Session::forget(self::SESSION_GUEST_KEY);
        Session::put('registration.request_id', $appointmentRequest->id);

        return redirect()->route('registration.done', ['request' => $appointmentRequest->id]);
    }

    /**
     * Show confirmation/thank-you page.
     */
    public function done(AppointmentRequest $request)
    {
        if (Session::get('registration.request_id') !== $request->id) {
            return redirect()->route('registration.form');
        }

        $request->load(['specialization', 'preferredDoctor']);

        return view('registration.done', ['appointmentRequest' => $request]);
    }
}
