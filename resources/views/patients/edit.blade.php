@extends('layouts.app')

@section('title', 'تعديل مريض')
@section('page-title', 'تعديل مريض')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">تعديل بيانات المريض</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('patients.update', $patient->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="full_name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('full_name') is-invalid @enderror" 
                           id="full_name" 
                           name="full_name" 
                           value="{{ old('full_name', $patient->full_name) }}" 
                           required>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="national_id" class="form-label">رقم الهوية <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('national_id') is-invalid @enderror" 
                           id="national_id" 
                           name="national_id" 
                           value="{{ old('national_id', $patient->national_id) }}" 
                           required>
                    @error('national_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone_number" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('phone_number') is-invalid @enderror" 
                           id="phone_number" 
                           name="phone_number" 
                           value="{{ old('phone_number', $patient->phone_number) }}" 
                           required>
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="gender" class="form-label">الجنس <span class="text-danger">*</span></label>
                    <select class="form-select @error('gender') is-invalid @enderror" 
                            id="gender" 
                            name="gender" 
                            required>
                        <option value="">اختر الجنس</option>
                        <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="age" class="form-label">العمر <span class="text-danger">*</span></label>
                    <input type="number" 
                           class="form-control @error('age') is-invalid @enderror" 
                           id="age" 
                           name="age" 
                           value="{{ old('age', $patient->age) }}" 
                           min="0" 
                           max="150" 
                           required>
                    @error('age')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label">العنوان</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" 
                              name="address" 
                              rows="2">{{ old('address', $patient->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="medical_history" class="form-label">التاريخ الطبي</label>
                    <textarea class="form-control @error('medical_history') is-invalid @enderror" 
                              id="medical_history" 
                              name="medical_history" 
                              rows="3">{{ old('medical_history', $patient->medical_history) }}</textarea>
                    @error('medical_history')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label mb-3">
                        <i class="fas fa-heartbeat text-danger me-1"></i> الأمراض المزمنة
                    </label>
                    <div class="border rounded p-3 bg-light">
                        <div class="row g-3">
                            @php
                                $chronicDiseases = [
                                    'diabetes' => 'السكري',
                                    'hypertension' => 'ضغط الدم',
                                    'asthma' => 'الربو',
                                    'heart_disease' => 'أمراض القلب',
                                    'kidney_disease' => 'أمراض الكلى',
                                    'liver_disease' => 'أمراض الكبد',
                                    'arthritis' => 'التهاب المفاصل',
                                    'osteoporosis' => 'هشاشة العظام',
                                    'epilepsy' => 'الصرع',
                                    'thyroid' => 'أمراض الغدة الدرقية',
                                    'anemia' => 'فقر الدم',
                                    'copd' => 'مرض الانسداد الرئوي المزمن',
                                    'depression' => 'الاكتئاب',
                                    'anxiety' => 'القلق',
                                    'other' => 'أخرى'
                                ];
                                $patientChronicDiseases = [];
                                if ($patient->chronic_diseases) {
                                    $decoded = json_decode($patient->chronic_diseases, true);
                                    if (is_array($decoded)) {
                                        $patientChronicDiseases = $decoded;
                                    } else {
                                        // Handle old text format
                                        $patientChronicDiseases = [];
                                    }
                                }
                                $oldChronicDiseases = old('chronic_diseases', $patientChronicDiseases);
                                if (is_string($oldChronicDiseases)) {
                                    $oldChronicDiseases = json_decode($oldChronicDiseases, true) ?? [];
                                }
                                // Check if there's a custom disease (not in the predefined list)
                                $hasCustomDisease = false;
                                $customDisease = '';
                                foreach ($oldChronicDiseases as $disease) {
                                    if (!isset($chronicDiseases[$disease])) {
                                        $hasCustomDisease = true;
                                        $customDisease = $disease;
                                        break;
                                    }
                                }
                            @endphp
                            @foreach($chronicDiseases as $key => $disease)
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="chronic_diseases[]" 
                                           value="{{ $key }}" 
                                           id="chronic_{{ $key }}"
                                           {{ in_array($key, $oldChronicDiseases) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chronic_{{ $key }}">
                                        {{ $disease }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3" id="other_disease_container" style="display: {{ ($hasCustomDisease || in_array('other', $oldChronicDiseases)) ? 'block' : 'none' }};">
                            <label for="other_disease" class="form-label">اذكر المرض الآخر:</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="other_disease" 
                                   name="other_disease" 
                                   value="{{ old('other_disease', $customDisease) }}"
                                   placeholder="أدخل المرض المزمن الآخر">
                        </div>
                    </div>
                    @error('chronic_diseases')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        اختر الأمراض المزمنة إن وجدت
                    </small>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const otherCheckbox = document.getElementById('chronic_other');
    const otherContainer = document.getElementById('other_disease_container');
    
    if (otherCheckbox) {
        // Toggle on change
        otherCheckbox.addEventListener('change', function() {
            if (this.checked) {
                otherContainer.style.display = 'block';
            } else {
                otherContainer.style.display = 'none';
                document.getElementById('other_disease').value = '';
            }
        });
    }
});
</script>
@endpush
@endsection

