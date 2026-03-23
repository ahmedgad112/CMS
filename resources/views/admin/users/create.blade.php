@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')
@section('page-title', 'إضافة مستخدم جديد')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">إضافة مستخدم جديد</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required>
                </div>

                <div class="col-md-6">
                    <label for="role" class="form-label">الدور <span class="text-danger">*</span></label>
                    <select class="form-select @error('role') is-invalid @enderror" 
                            id="role" 
                            name="role" 
                            required>
                        <option value="">اختر الدور</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                        <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>طبيب</option>
                        <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}>موظف استقبال</option>
                        <option value="call_center" {{ old('role') == 'call_center' ? 'selected' : '' }}>مركز اتصال</option>
                        <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>محاسب</option>
                        <option value="storekeeper" {{ old('role') == 'storekeeper' ? 'selected' : '' }}>مخزن</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6" id="department-field" style="display: none;">
                    <label for="department_id" class="form-label">
                        <i class="fas fa-building text-primary me-1"></i>
                        القسم <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('department_id') is-invalid @enderror" 
                            id="department_id" 
                            name="department_id">
                        <option value="">اختر القسم</option>
                        @foreach($departments ?? [] as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6" id="specialization-field" style="display: none;">
                    <label for="specialization_id" class="form-label">
                        <i class="fas fa-stethoscope text-info me-1"></i>
                        التخصص <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('specialization_id') is-invalid @enderror" 
                            id="specialization_id" 
                            name="specialization_id">
                        <option value="">اختر القسم أولاً</option>
                    </select>
                    @error('specialization_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">اختر القسم أولاً لعرض التخصصات المتاحة</small>
                </div>

                <div class="col-md-6" id="specialization-text-field" style="display: none;">
                    <label for="specialization" class="form-label">التخصص (نص حر)</label>
                    <input type="text" 
                           class="form-control @error('specialization') is-invalid @enderror" 
                           id="specialization" 
                           name="specialization" 
                           value="{{ old('specialization') }}"
                           placeholder="مثال: طب القلب، طب الأطفال، إلخ">
                    @error('specialization')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">يمكنك إدخال تخصص نصي بدلاً من الاختيار من القائمة</small>
                </div>

                <div class="col-md-6" id="checkup-fee-field" style="display: none;">
                    <label for="checkup_fee" class="form-label">
                        <i class="fas fa-money-bill-wave text-success me-1"></i>
                        سعر الكشف (ج.م)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           class="form-control @error('checkup_fee') is-invalid @enderror" 
                           id="checkup_fee" 
                           name="checkup_fee" 
                           value="{{ old('checkup_fee') }}"
                           placeholder="0.00">
                    @error('checkup_fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6" id="consultation-fee-field" style="display: none;">
                    <label for="consultation_fee" class="form-label">
                        <i class="fas fa-money-bill-wave text-info me-1"></i>
                        سعر الاستشارة (ج.م)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           class="form-control @error('consultation_fee') is-invalid @enderror" 
                           id="consultation_fee" 
                           name="consultation_fee" 
                           value="{{ old('consultation_fee') }}"
                           placeholder="0.00">
                    @error('consultation_fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            حساب نشط
                        </label>
                    </div>
                </div>
            </div>

            <!-- Doctor Schedule Section -->
            <div id="schedule-section" style="display: none;" class="mt-4 pt-4 border-top">
                <h6 class="mb-3">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    مواعيد الطبيب
                </h6>
                <div id="schedules-container">
                    <!-- Schedules will be added here dynamically -->
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-schedule-btn">
                    <i class="fas fa-plus me-2"></i> إضافة موعد
                </button>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times me-2"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i> حفظ
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const specializationField = document.getElementById('specialization-field');
        const scheduleSection = document.getElementById('schedule-section');
        const schedulesContainer = document.getElementById('schedules-container');
        const addScheduleBtn = document.getElementById('add-schedule-btn');
        let scheduleIndex = 0;

        const days = {
            'saturday': 'السبت',
            'sunday': 'الأحد',
            'monday': 'الإثنين',
            'tuesday': 'الثلاثاء',
            'wednesday': 'الأربعاء',
            'thursday': 'الخميس',
            'friday': 'الجمعة'
        };

        const departmentField = document.getElementById('department-field');
        const specializationTextField = document.getElementById('specialization-text-field');
        const checkupFeeField = document.getElementById('checkup-fee-field');
        const consultationFeeField = document.getElementById('consultation-fee-field');
        const departmentSelect = document.getElementById('department_id');
        const specializationSelect = document.getElementById('specialization_id');

        function toggleSpecializationField() {
            if (roleSelect.value === 'doctor') {
                departmentField.style.display = 'block';
                specializationField.style.display = 'block';
                specializationTextField.style.display = 'block';
                checkupFeeField.style.display = 'block';
                consultationFeeField.style.display = 'block';
                scheduleSection.style.display = 'block';
            } else {
                departmentField.style.display = 'none';
                specializationField.style.display = 'none';
                specializationTextField.style.display = 'none';
                checkupFeeField.style.display = 'none';
                consultationFeeField.style.display = 'none';
                scheduleSection.style.display = 'none';
                schedulesContainer.innerHTML = '';
                scheduleIndex = 0;
            }
        }

        // Load specializations based on selected department
        if (departmentSelect) {
            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;
                specializationSelect.innerHTML = '<option value="">جاري التحميل...</option>';
                
                if (departmentId) {
                    fetch(`/api/departments/${departmentId}/specializations`)
                        .then(response => response.json())
                        .then(data => {
                            specializationSelect.innerHTML = '<option value="">اختر التخصص</option>';
                            if (data.specializations && data.specializations.length > 0) {
                                data.specializations.forEach(spec => {
                                    const option = document.createElement('option');
                                    option.value = spec.id;
                                    option.textContent = spec.name;
                                    specializationSelect.appendChild(option);
                                });
                            } else {
                                specializationSelect.innerHTML = '<option value="">لا توجد تخصصات متاحة</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            specializationSelect.innerHTML = '<option value="">حدث خطأ في التحميل</option>';
                        });
                } else {
                    specializationSelect.innerHTML = '<option value="">اختر القسم أولاً</option>';
                }
            });
        }

        function addScheduleRow() {
            const scheduleRow = document.createElement('div');
            scheduleRow.className = 'row g-3 mb-3 p-3 border rounded';
            scheduleRow.setAttribute('data-index', scheduleIndex);
            
            scheduleRow.innerHTML = `
                <div class="col-md-3">
                    <label class="form-label">اليوم <span class="text-danger">*</span></label>
                    <select name="schedules[${scheduleIndex}][day_of_week]" class="form-select" required>
                        <option value="">اختر اليوم</option>
                        ${Object.entries(days).map(([value, label]) => 
                            `<option value="${value}">${label}</option>`
                        ).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">من <span class="text-danger">*</span></label>
                    <input type="time" name="schedules[${scheduleIndex}][start_time]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">إلى <span class="text-danger">*</span></label>
                    <input type="time" name="schedules[${scheduleIndex}][end_time]" class="form-control" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100 remove-schedule">
                        <i class="fas fa-trash me-1"></i> حذف
                    </button>
                </div>
            `;

            schedulesContainer.appendChild(scheduleRow);
            scheduleIndex++;

            // Add remove event listener
            scheduleRow.querySelector('.remove-schedule').addEventListener('click', function() {
                scheduleRow.remove();
            });
        }

        addScheduleBtn.addEventListener('click', addScheduleRow);
        roleSelect.addEventListener('change', toggleSpecializationField);
        toggleSpecializationField(); // Check on page load
    });
</script>
@endpush
@endsection

