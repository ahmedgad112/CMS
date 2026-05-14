@extends('layouts.app')

@section('title', 'اختيار نوع الخدمة')

@section('content')
<style>
    .reg-page {
        min-height: 100vh;
        padding: 2rem 1rem;
        background:
            radial-gradient(circle at 15% 15%, rgba(13, 148, 136, 0.1) 0%, transparent 45%),
            radial-gradient(circle at 85% 85%, rgba(6, 182, 212, 0.08) 0%, transparent 45%),
            linear-gradient(135deg, #f0fdfa 0%, #ecfeff 100%);
    }

    .reg-wrapper {
        max-width: 880px;
        margin: 0 auto;
    }

    .reg-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .reg-header .brand-icon {
        width: 76px;
        height: 76px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.75rem;
        box-shadow: 0 12px 28px rgba(13, 148, 136, 0.3);
    }

    .reg-header h1 {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.75rem;
        margin-bottom: 0.25rem;
    }

    .reg-header p {
        color: #64748b;
        margin: 0;
    }

    .reg-stepper {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin: 1.5rem 0;
        flex-wrap: wrap;
    }

    .reg-step {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 1rem;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 999px;
        font-size: 0.875rem;
        color: #94a3b8;
    }

    .reg-step.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border-color: var(--primary-color);
        color: white;
        box-shadow: 0 6px 16px rgba(13, 148, 136, 0.3);
    }

    .reg-step.done {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
        color: white;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
    }

    .reg-step .step-num {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .reg-step:not(.active):not(.done) .step-num {
        background: #f1f5f9;
        color: #94a3b8;
    }

    .patient-summary {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .patient-summary .avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .patient-summary .info {
        flex: 1;
        min-width: 220px;
    }

    .patient-summary .info strong {
        display: block;
        font-size: 1.05rem;
        color: var(--text-color);
    }

    .patient-summary .info small {
        color: #64748b;
    }

    .section-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }

    .section-card-header {
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-card-header h6 {
        margin: 0;
        font-weight: 700;
        color: var(--text-color);
    }

    .section-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(13, 148, 136, 0.25);
    }

    .section-card-body {
        padding: 1.25rem;
    }

    .service-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 0.85rem;
    }

    .service-option input[type="radio"] {
        display: none;
    }

    .service-option label {
        display: block;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 14px;
        padding: 1.25rem;
        cursor: pointer;
        height: 100%;
        margin: 0;
        transition: all 0.25s;
        position: relative;
    }

    .service-option label:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(13, 148, 136, 0.15);
    }

    .service-option input[type="radio"]:checked + label {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.08) 0%, rgba(6, 182, 212, 0.03) 100%);
        box-shadow: 0 8px 22px rgba(13, 148, 136, 0.2);
    }

    .service-option input[type="radio"]:checked + label .service-check {
        opacity: 1;
        transform: scale(1);
    }

    .service-check {
        position: absolute;
        top: 0.85rem;
        left: 0.85rem;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.2s;
        font-size: 0.8rem;
        box-shadow: 0 3px 8px rgba(13, 148, 136, 0.3);
    }

    .service-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        color: white;
    }

    .service-icon.checkup {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        box-shadow: 0 6px 14px rgba(13, 148, 136, 0.3);
    }

    .service-icon.consultation {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 6px 14px rgba(245, 158, 11, 0.3);
    }

    .service-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 0.4rem;
    }

    .service-desc {
        color: #64748b;
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.55;
    }

    .pretty-select {
        padding: 0.75rem 0.85rem;
        font-size: 0.95rem;
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .pretty-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        outline: none;
    }

    .reg-footer {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    @media (max-width: 575.98px) {
        .reg-page { padding: 1rem 0.5rem; }
        .section-card-body { padding: 1rem; }
        .reg-header h1 { font-size: 1.35rem; }
    }
</style>

<div class="reg-page">
    <div class="reg-wrapper">
        <div class="reg-header">
            <div class="brand-icon">
                <i class="fas fa-stethoscope"></i>
            </div>
            <p class="mb-1 small text-muted fw-semibold">{{ $platformOrganizationName }}</p>
            <h1>اختر الخدمة والطبيب</h1>
            <p>حدد نوع الخدمة والتخصص والطبيب المفضل (إن أمكن)</p>
        </div>

        <div class="reg-stepper" aria-label="خطوات التسجيل">
            <div class="reg-step done">
                <span class="step-num"><i class="fas fa-check"></i></span>
                <span>البيانات الشخصية</span>
            </div>
            <div class="reg-step active">
                <span class="step-num">2</span>
                <span>اختيار الخدمة</span>
            </div>
            <div class="reg-step">
                <span class="step-num">3</span>
                <span>تأكيد الطلب</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>برجاء تصحيح الأخطاء التالية:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="patient-summary">
            <div class="avatar">
                <i class="fas fa-{{ ($guest['gender'] ?? 'male') === 'male' ? 'mars' : 'venus' }}"></i>
            </div>
            <div class="info">
                <strong>{{ $guest['full_name'] ?? '' }}</strong>
                <small>
                    <i class="fas fa-phone me-1"></i>{{ $guest['phone_number'] ?? '' }}
                    <span class="ms-3"><i class="fas fa-birthday-cake me-1"></i>{{ $guest['age'] ?? '' }} سنة</span>
                </small>
            </div>
        </div>

        <form method="POST" action="{{ route('registration.service.submit') }}">
            @csrf

            <div class="section-card">
                <div class="section-card-header">
                    <span class="section-num">1</span>
                    <h6>اختر نوع الخدمة</h6>
                </div>
                <div class="section-card-body">
                    <div class="service-grid">
                        <div class="service-option">
                            <input type="radio" id="service_checkup" name="service_type" value="checkup"
                                   {{ old('service_type', 'checkup') === 'checkup' ? 'checked' : '' }} required>
                            <label for="service_checkup">
                                <div class="service-check"><i class="fas fa-check"></i></div>
                                <div class="service-icon checkup"><i class="fas fa-user-md"></i></div>
                                <div class="service-title">كشف جديد</div>
                                <p class="service-desc">كشف طبي كامل مع طبيب مختص يشمل الفحص الإكلينيكي ووصف العلاج.</p>
                            </label>
                        </div>

                        <div class="service-option">
                            <input type="radio" id="service_consultation" name="service_type" value="consultation"
                                   {{ old('service_type') === 'consultation' ? 'checked' : '' }}>
                            <label for="service_consultation">
                                <div class="service-check"><i class="fas fa-check"></i></div>
                                <div class="service-icon consultation"><i class="fas fa-comments"></i></div>
                                <div class="service-title">استشارة</div>
                                <p class="service-desc">استشارة طبية لمتابعة حالة سابقة أو مناقشة نتائج تحاليل وأشعة.</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-card-header">
                    <span class="section-num">2</span>
                    <h6>التخصص الذي جئت من أجله</h6>
                </div>
                <div class="section-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="specialization_id" class="form-label fw-semibold">
                                <i class="fas fa-stethoscope text-primary me-1"></i> التخصص
                            </label>
                            <select id="specialization_id"
                                    name="specialization_id"
                                    class="form-select pretty-select @error('specialization_id') is-invalid @enderror">
                                <option value="">-- أي تخصص (يرشحه الاستقبال) --</option>
                                @foreach($specializations as $spec)
                                    <option value="{{ $spec->id }}"
                                            data-department="{{ $spec->department->name ?? '' }}"
                                            {{ old('specialization_id') == $spec->id ? 'selected' : '' }}>
                                        {{ $spec->name }}@if($spec->department) — {{ $spec->department->name }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('specialization_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                لو مش متأكد من التخصص، اتركه فاضي وموظف الاستقبال هيساعدك.
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label for="preferred_doctor_id" class="form-label fw-semibold">
                                <i class="fas fa-user-md text-primary me-1"></i> الطبيب المفضل
                            </label>
                            <select id="preferred_doctor_id"
                                    name="preferred_doctor_id"
                                    class="form-select pretty-select @error('preferred_doctor_id') is-invalid @enderror">
                                <option value="">-- أي طبيب متاح --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                            data-specialization="{{ $doctor->specialization_id }}"
                                            data-department="{{ $doctor->department->name ?? '' }}"
                                            data-spec-name="{{ $doctor->specialization->name ?? '' }}"
                                            {{ old('preferred_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        د. {{ $doctor->name }}@if($doctor->specialization) — {{ $doctor->specialization->name }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('preferred_doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted" id="doctor_hint">
                                <i class="fas fa-info-circle me-1"></i>
                                اختر التخصص أولاً لعرض الأطباء التابعين له.
                            </small>
                        </div>

                        @if(($clinics ?? collect())->count() > 0)
                        <div class="col-12">
                            <label for="preferred_clinic_id" class="form-label fw-semibold">
                                <i class="fas fa-hospital text-info me-1"></i> العيادة المفضلة <small class="text-muted fw-normal">(اختياري)</small>
                            </label>
                            <select id="preferred_clinic_id"
                                    name="preferred_clinic_id"
                                    class="form-select pretty-select @error('preferred_clinic_id') is-invalid @enderror">
                                <option value="">-- أي عيادة قريبة --</option>
                                @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}"
                                            {{ old('preferred_clinic_id') == $clinic->id ? 'selected' : '' }}>
                                        {{ $clinic->name }}
                                        @if($clinic->is_main) (الرئيسية)@endif
                                        @if($clinic->city) — {{ $clinic->city }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('preferred_clinic_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                لو عندك تفضيل لعيادة معينة اختارها، لو لأ سيبها فاضية.
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-card-header">
                    <span class="section-num">3</span>
                    <h6>ملاحظات إضافية <small class="text-muted fw-normal">(اختياري)</small></h6>
                </div>
                <div class="section-card-body">
                    <textarea class="form-control @error('notes') is-invalid @enderror"
                              id="notes"
                              name="notes"
                              rows="3"
                              placeholder="مثال: أعراض حالية، توقيت مناسب، أي ملاحظة تريد إضافتها...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="reg-footer">
                <a href="{{ route('registration.form') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i> رجوع
                </a>
                <button type="submit" class="btn btn-primary btn-lg px-4 fw-semibold">
                    <i class="fas fa-paper-plane me-2"></i> إرسال طلب الحجز
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const specSelect = document.getElementById('specialization_id');
    const doctorSelect = document.getElementById('preferred_doctor_id');
    const doctorHint = document.getElementById('doctor_hint');

    const allDoctorOptions = Array.from(doctorSelect.querySelectorAll('option')).map(opt => ({
        element: opt,
        value: opt.value,
        specialization: opt.dataset.specialization || '',
        specName: opt.dataset.specName || ''
    }));

    function filterDoctors() {
        const selectedSpec = specSelect.value;
        const currentDoctor = doctorSelect.value;
        const fragment = document.createDocumentFragment();

        doctorSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = '-- أي طبيب متاح --';
        fragment.appendChild(placeholder);

        let visibleCount = 0;
        let currentStillValid = false;

        allDoctorOptions.forEach(opt => {
            if (!opt.value) return;
            const matches = !selectedSpec || String(opt.specialization) === String(selectedSpec);
            if (matches) {
                const clone = opt.element.cloneNode(true);
                fragment.appendChild(clone);
                visibleCount++;
                if (clone.value === currentDoctor) currentStillValid = true;
            }
        });

        doctorSelect.appendChild(fragment);

        if (currentStillValid) {
            doctorSelect.value = currentDoctor;
        } else {
            doctorSelect.value = '';
        }

        if (selectedSpec) {
            doctorHint.innerHTML = `<i class="fas fa-info-circle me-1"></i> ${visibleCount} طبيب متاح في هذا التخصص.`;
        } else {
            doctorHint.innerHTML = `<i class="fas fa-info-circle me-1"></i> اختر التخصص أولاً لعرض الأطباء التابعين له.`;
        }
    }

    doctorSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        if (selected && selected.dataset.specialization && !specSelect.value) {
            specSelect.value = selected.dataset.specialization;
            filterDoctors();
            doctorSelect.value = this.value;
        }
    });

    specSelect.addEventListener('change', filterDoctors);

    filterDoctors();
});
</script>
@endpush
@endsection
