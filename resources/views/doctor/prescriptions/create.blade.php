@extends('layouts.app')

@section('title', 'إنشاء وصفة طبية')
@section('page-title', 'إنشاء وصفة طبية')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-prescription me-2"></i> إنشاء وصفة طبية جديدة
        </h5>
    </div>
    <div class="card-body">
        @if($appointment)
        <div class="alert alert-info border-start border-4 border-info mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-check fs-4 me-3"></i>
                <div>
                    <strong class="d-block mb-1">معلومات الموعد</strong>
                    <div class="mb-1">
                        <i class="fas fa-user me-1"></i>
                        <strong>المريض:</strong> {{ $appointment->patient->full_name }}
                    </div>
                    <div class="text-muted mb-1">
                        <i class="fas fa-calendar me-1"></i>
                        <strong>تاريخ الموعد:</strong> {{ $appointment->appointment_date->format('Y-m-d H:i') }}
                    </div>
                    <div class="text-muted">
                        <i class="fas fa-user-md me-1"></i>
                        <strong>الطبيب:</strong> {{ $appointment->doctor->name }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('doctor.prescriptions.store') }}" id="prescriptionForm">
            @csrf

            @if($appointment)
            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
            @else
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <label for="appointment_id" class="form-label">
                        <i class="fas fa-calendar-check me-1 text-muted"></i>
                        الموعد <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('appointment_id') is-invalid @enderror" 
                            id="appointment_id" 
                            name="appointment_id" 
                            required>
                        <option value="">اختر الموعد</option>
                        <!-- سيتم ملؤها من Controller -->
                    </select>
                    @error('appointment_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">اختر الموعد المرتبط بهذه الوصفة</small>
                </div>
            </div>
            @endif

            <div class="row g-4 mb-4">
                <div class="col-12">
                    <label for="notes" class="form-label">
                        <i class="fas fa-sticky-note me-1 text-muted"></i>
                        ملاحظات إضافية
                    </label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" 
                              name="notes" 
                              rows="4"
                              placeholder="أدخل أي ملاحظات إضافية للوصفة...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">ملاحظات عامة للوصفة الطبية</small>
                </div>
            </div>

            <!-- Medicines Section -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">
                        <i class="fas fa-pills me-2 text-primary"></i>
                        الأدوية الموصوفة
                    </h6>
                    <button type="button" class="btn btn-success" id="addMedicine">
                        <i class="fas fa-plus me-2"></i> إضافة دواء
                    </button>
                </div>

                <div id="medicinesContainer">
                    <div class="medicine-item card mb-3 border-primary">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <span class="fw-bold">
                                <i class="fas fa-capsules me-2 text-primary"></i>
                                دواء #<span class="medicine-number">1</span>
                            </span>
                            <button type="button" class="btn btn-sm btn-danger remove-medicine" title="حذف الدواء">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        <i class="fas fa-pills me-1 text-muted"></i>
                                        اسم الدواء <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="medicines[0][medicine_name]" 
                                           placeholder="مثال: باراسيتامول، إيبوبروفين..."
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="fas fa-syringe me-1 text-muted"></i>
                                        الجرعة <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="medicines[0][dosage]" 
                                           placeholder="مثال: 500 مجم"
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="fas fa-redo me-1 text-muted"></i>
                                        التكرار <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="medicines[0][frequency]" 
                                           placeholder="مثال: كل 8 ساعات"
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                        المدة <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="medicines[0][duration]" 
                                           placeholder="مثال: 5 أيام"
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="fas fa-info-circle me-1 text-muted"></i>
                                        تعليمات خاصة
                                    </label>
                                    <textarea class="form-control" 
                                              name="medicines[0][instructions]" 
                                              rows="3"
                                              placeholder="تعليمات خاصة للدواء..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                @if($appointment)
                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times me-2"></i> إلغاء
                </a>
                @else
                <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times me-2"></i> إلغاء
                </a>
                @endif
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i> حفظ الوصفة
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .medicine-item {
        transition: all 0.3s ease;
    }
    
    .medicine-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .medicine-item .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
</style>
@endpush

@push('scripts')
<script>
    let medicineIndex = 1;

    document.getElementById('addMedicine').addEventListener('click', function() {
        const container = document.getElementById('medicinesContainer');
        const newItem = document.createElement('div');
        newItem.className = 'medicine-item card mb-3 border-primary';
        newItem.innerHTML = `
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <span class="fw-bold">
                    <i class="fas fa-capsules me-2 text-primary"></i>
                    دواء #<span class="medicine-number">${medicineIndex + 1}</span>
                </span>
                <button type="button" class="btn btn-sm btn-danger remove-medicine" title="حذف الدواء">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="fas fa-pills me-1 text-muted"></i>
                            اسم الدواء <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="medicines[${medicineIndex}][medicine_name]" 
                               placeholder="مثال: باراسيتامول، إيبوبروفين..."
                               required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fas fa-syringe me-1 text-muted"></i>
                            الجرعة <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="medicines[${medicineIndex}][dosage]" 
                               placeholder="مثال: 500 مجم"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fas fa-redo me-1 text-muted"></i>
                            التكرار <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="medicines[${medicineIndex}][frequency]" 
                               placeholder="مثال: كل 8 ساعات"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fas fa-calendar-alt me-1 text-muted"></i>
                            المدة <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="medicines[${medicineIndex}][duration]" 
                               placeholder="مثال: 5 أيام"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fas fa-info-circle me-1 text-muted"></i>
                            تعليمات خاصة
                        </label>
                        <textarea class="form-control" 
                                  name="medicines[${medicineIndex}][instructions]" 
                                  rows="3"
                                  placeholder="تعليمات خاصة للدواء..."></textarea>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        medicineIndex++;
        updateMedicineNumbers();
    });

    function updateMedicineNumbers() {
        const items = document.querySelectorAll('.medicine-item');
        items.forEach((item, index) => {
            const numberSpan = item.querySelector('.medicine-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-medicine')) {
            const item = e.target.closest('.medicine-item');
            const totalItems = document.querySelectorAll('.medicine-item').length;
            
            if (totalItems > 1) {
                item.remove();
                updateMedicineNumbers();
            } else {
                alert('يجب أن يكون هناك دواء واحد على الأقل في الوصفة');
            }
        }
    });

    // Form validation
    document.getElementById('prescriptionForm').addEventListener('submit', function(e) {
        const medicines = document.querySelectorAll('.medicine-item');
        let isValid = true;
        
        medicines.forEach((medicine, index) => {
            const name = medicine.querySelector('input[name*="[medicine_name]"]');
            const dosage = medicine.querySelector('input[name*="[dosage]"]');
            const frequency = medicine.querySelector('input[name*="[frequency]"]');
            const duration = medicine.querySelector('input[name*="[duration]"]');
            
            if (!name.value.trim() || !dosage.value.trim() || !frequency.value.trim() || !duration.value.trim()) {
                isValid = false;
                medicine.classList.add('border-danger');
            } else {
                medicine.classList.remove('border-danger');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة للأدوية');
        }
    });
</script>
@endpush
@endsection

