@extends('layouts.app')

@section('title', 'تسجيل مريض جديد')

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

    .reg-step:not(.active) .step-num {
        background: #f1f5f9;
        color: #94a3b8;
    }

    .reg-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .reg-card-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
        border-bottom: 1px solid var(--border-color);
    }

    .reg-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .reg-card-body {
        padding: 1.75rem 1.5rem;
    }

    .gender-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .gender-option input[type="radio"] {
        display: none;
    }

    .gender-option label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.85rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        color: #475569;
        transition: all 0.2s;
        margin: 0;
    }

    .gender-option label:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .gender-option input[type="radio"]:checked + label {
        border-color: var(--primary-color);
        background: rgba(13, 148, 136, 0.08);
        color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.18);
    }

    .yes-no-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .yes-no-options input[type="radio"] {
        display: none;
    }

    .yes-no-options label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.85rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        color: #475569;
        margin: 0;
        transition: all 0.2s;
    }

    .yes-no-options label:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .yes-no-options input[type="radio"]:checked + label.yes-option {
        border-color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
    }

    .yes-no-options input[type="radio"]:checked + label.no-option {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        color: #047857;
    }

    .chronic-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.5rem;
        padding: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background: var(--primary-soft);
    }

    .chronic-item input[type="checkbox"] {
        display: none;
    }

    .chronic-item label {
        display: block;
        padding: 0.55rem 0.75rem;
        border: 1px solid var(--border-color);
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        color: #475569;
        text-align: center;
        margin: 0;
        transition: all 0.2s;
    }

    .chronic-item label:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .chronic-item input[type="checkbox"]:checked + label {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border-color: var(--primary-color);
        color: white;
        font-weight: 600;
        box-shadow: 0 3px 8px rgba(13, 148, 136, 0.2);
    }

    .reg-card-footer {
        padding: 1.25rem 1.5rem;
        background: var(--primary-soft);
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .help-link {
        color: #64748b;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .help-link:hover {
        color: var(--primary-color);
    }

    @media (max-width: 575.98px) {
        .reg-page { padding: 1rem 0.5rem; }
        .reg-card-body { padding: 1.25rem 1rem; }
        .reg-header h1 { font-size: 1.35rem; }
    }
</style>

<div class="reg-page">
    <div class="reg-wrapper">
        <div class="reg-header">
            <div class="brand-icon">
                <i class="fas fa-hospital-user"></i>
            </div>
            <p class="mb-1 small text-muted fw-semibold">{{ $platformOrganizationName }}</p>
            <h1>تسجيل مريض جديد</h1>
            <p>سجّل بياناتك للحجز في العيادة</p>
        </div>

        <div class="reg-stepper" aria-label="خطوات التسجيل">
            <div class="reg-step active">
                <span class="step-num">1</span>
                <span>البيانات الشخصية</span>
            </div>
            <div class="reg-step">
                <span class="step-num">2</span>
                <span>اختيار الخدمة</span>
            </div>
            <div class="reg-step">
                <span class="step-num">3</span>
                <span>تأكيد الطلب</span>
            </div>
        </div>

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

        <form method="POST" action="{{ route('registration.register') }}" novalidate>
            @csrf

            <div class="reg-card mb-4">
                <div class="reg-card-header">
                    <h5><i class="fas fa-id-card text-primary"></i> البيانات الأساسية</h5>
                </div>
                <div class="reg-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-semibold">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                                   id="full_name"
                                   name="full_name"
                                   value="{{ old('full_name') }}"
                                   placeholder="مثال: أحمد محمد علي"
                                   required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone_number" class="form-label fw-semibold">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="tel"
                                   class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                                   id="phone_number"
                                   name="phone_number"
                                   value="{{ old('phone_number') }}"
                                   placeholder="01xxxxxxxxx"
                                   inputmode="numeric"
                                   pattern="[0-9+\-\s]{8,20}"
                                   required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="age" class="form-label fw-semibold">السن <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control form-control-lg @error('age') is-invalid @enderror"
                                   id="age"
                                   name="age"
                                   value="{{ old('age') }}"
                                   min="0"
                                   max="150"
                                   placeholder="مثال: 30"
                                   required>
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-block">النوع <span class="text-danger">*</span></label>
                            <div class="gender-options">
                                <div class="gender-option">
                                    <input type="radio" id="gender_male" name="gender" value="male"
                                           {{ old('gender') === 'male' ? 'checked' : '' }} required>
                                    <label for="gender_male">
                                        <i class="fas fa-mars"></i> ذكر
                                    </label>
                                </div>
                                <div class="gender-option">
                                    <input type="radio" id="gender_female" name="gender" value="female"
                                           {{ old('gender') === 'female' ? 'checked' : '' }}>
                                    <label for="gender_female">
                                        <i class="fas fa-venus"></i> أنثى
                                    </label>
                                </div>
                            </div>
                            @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="reg-card mb-4">
                <div class="reg-card-header">
                    <h5><i class="fas fa-notes-medical text-danger"></i> التاريخ الطبي</h5>
                </div>
                <div class="reg-card-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold d-block">هل سبق أن أجريت عمليات جراحية؟ <span class="text-danger">*</span></label>
                        <div class="yes-no-options">
                            <div>
                                <input type="radio" id="op_yes" name="has_operations" value="yes"
                                       {{ old('has_operations') === 'yes' ? 'checked' : '' }} required>
                                <label for="op_yes" class="yes-option">
                                    <i class="fas fa-procedures"></i> نعم
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="op_no" name="has_operations" value="no"
                                       {{ old('has_operations', '') === 'no' ? 'checked' : '' }}>
                                <label for="op_no" class="no-option">
                                    <i class="fas fa-check-circle"></i> لا
                                </label>
                            </div>
                        </div>
                        @error('has_operations')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <div id="operations_details_wrapper" class="mt-3" style="display: {{ old('has_operations') === 'yes' ? 'block' : 'none' }};">
                            <label for="operations_details" class="form-label">تفاصيل العمليات السابقة</label>
                            <textarea class="form-control @error('operations_details') is-invalid @enderror"
                                      id="operations_details"
                                      name="operations_details"
                                      rows="3"
                                      placeholder="اذكر نوع العملية والتاريخ التقريبي إن أمكن...">{{ old('operations_details') }}</textarea>
                            @error('operations_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-semibold d-block">
                            <i class="fas fa-heartbeat text-danger me-1"></i>
                            هل لديك أمراض مزمنة؟ <span class="text-muted small fw-normal">(اختياري - يمكن اختيار أكثر من مرض)</span>
                        </label>
                        @php
                            $chronicDiseases = [
                                'diabetes' => 'السكري',
                                'hypertension' => 'ضغط الدم',
                                'asthma' => 'الربو',
                                'heart_disease' => 'أمراض القلب',
                                'kidney_disease' => 'أمراض الكلى',
                                'liver_disease' => 'أمراض الكبد',
                                'arthritis' => 'التهاب المفاصل',
                                'thyroid' => 'الغدة الدرقية',
                                'anemia' => 'فقر الدم',
                                'epilepsy' => 'الصرع',
                                'other' => 'أخرى',
                            ];
                            $oldChronic = old('chronic_diseases', []);
                            if (is_string($oldChronic)) {
                                $oldChronic = json_decode($oldChronic, true) ?? [];
                            }
                        @endphp
                        <div class="chronic-grid mt-2">
                            @foreach($chronicDiseases as $key => $label)
                                <div class="chronic-item">
                                    <input type="checkbox"
                                           id="chronic_{{ $key }}"
                                           name="chronic_diseases[]"
                                           value="{{ $key }}"
                                           {{ in_array($key, $oldChronic, true) ? 'checked' : '' }}>
                                    <label for="chronic_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div id="other_disease_wrapper" class="mt-3" style="display: {{ in_array('other', $oldChronic, true) ? 'block' : 'none' }};">
                            <label for="other_disease" class="form-label">اذكر المرض الآخر</label>
                            <input type="text"
                                   class="form-control @error('other_disease') is-invalid @enderror"
                                   id="other_disease"
                                   name="other_disease"
                                   value="{{ old('other_disease') }}"
                                   placeholder="أدخل اسم المرض المزمن">
                            @error('other_disease')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="reg-card-footer">
                    <a href="{{ route('login') }}" class="help-link">
                        <i class="fas fa-sign-in-alt me-1"></i> أنا موظف بالعيادة - تسجيل الدخول
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-4 fw-semibold">
                        التالي - اختيار الخدمة
                        <i class="fas fa-arrow-left ms-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const opYes = document.getElementById('op_yes');
    const opNo = document.getElementById('op_no');
    const opDetailsWrap = document.getElementById('operations_details_wrapper');
    const opDetails = document.getElementById('operations_details');

    function toggleOperations() {
        if (opYes.checked) {
            opDetailsWrap.style.display = 'block';
        } else {
            opDetailsWrap.style.display = 'none';
            opDetails.value = '';
        }
    }

    opYes.addEventListener('change', toggleOperations);
    opNo.addEventListener('change', toggleOperations);

    const otherCheckbox = document.getElementById('chronic_other');
    const otherWrap = document.getElementById('other_disease_wrapper');
    const otherInput = document.getElementById('other_disease');

    if (otherCheckbox) {
        otherCheckbox.addEventListener('change', function () {
            if (this.checked) {
                otherWrap.style.display = 'block';
            } else {
                otherWrap.style.display = 'none';
                otherInput.value = '';
            }
        });
    }
});
</script>
@endpush
@endsection
