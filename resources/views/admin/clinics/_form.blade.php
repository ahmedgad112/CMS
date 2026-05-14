<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">
            <i class="fas fa-tag text-primary me-1"></i>
            اسم العيادة <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
               id="name" name="name"
               value="{{ old('name', $clinic->name ?? '') }}"
               placeholder="مثال: عيادة المعادي" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="name_en" class="form-label fw-semibold">
            <i class="fas fa-tag text-info me-1"></i>
            اسم العيادة (إنجليزي)
        </label>
        <input type="text" class="form-control form-control-lg @error('name_en') is-invalid @enderror"
               id="name_en" name="name_en"
               value="{{ old('name_en', $clinic->name_en ?? '') }}"
               placeholder="Example: Maadi Clinic">
        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label fw-semibold">
            <i class="fas fa-phone text-success me-1"></i>
            رقم الهاتف
        </label>
        <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror"
               id="phone" name="phone"
               value="{{ old('phone', $clinic->phone ?? '') }}"
               placeholder="01XXXXXXXXX">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">
            <i class="fas fa-envelope text-info me-1"></i>
            البريد الإلكتروني
        </label>
        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
               id="email" name="email"
               value="{{ old('email', $clinic->email ?? '') }}"
               placeholder="clinic@example.com">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="city" class="form-label fw-semibold">
            <i class="fas fa-city text-warning me-1"></i>
            المدينة
        </label>
        <input type="text" class="form-control form-control-lg @error('city') is-invalid @enderror"
               id="city" name="city"
               value="{{ old('city', $clinic->city ?? '') }}"
               placeholder="مثال: القاهرة">
        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="working_hours" class="form-label fw-semibold">
            <i class="fas fa-clock text-secondary me-1"></i>
            مواعيد العمل
        </label>
        <input type="text" class="form-control form-control-lg @error('working_hours') is-invalid @enderror"
               id="working_hours" name="working_hours"
               value="{{ old('working_hours', $clinic->working_hours ?? '') }}"
               placeholder="مثال: من 9 صباحاً إلى 10 مساءً">
        @error('working_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="address" class="form-label fw-semibold">
            <i class="fas fa-map-marker-alt text-danger me-1"></i>
            العنوان
        </label>
        <input type="text" class="form-control form-control-lg @error('address') is-invalid @enderror"
               id="address" name="address"
               value="{{ old('address', $clinic->address ?? '') }}"
               placeholder="العنوان التفصيلي للعيادة">
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label fw-semibold">
            <i class="fas fa-align-right text-secondary me-1"></i>
            الوصف
        </label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="3"
                  placeholder="أدخل وصفاً مختصراً عن العيادة...">{{ old('description', $clinic->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <div class="card bg-light border-0 p-3 h-100">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox"
                       id="is_active" name="is_active" value="1"
                       {{ old('is_active', $clinic->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="is_active">
                    <i class="fas fa-toggle-on text-success me-2"></i>
                    عيادة نشطة
                </label>
                <small class="d-block text-muted mt-1">
                    العيادة النشطة فقط ستظهر في قوائم الاختيار
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card bg-light border-0 p-3 h-100">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox"
                       id="is_main" name="is_main" value="1"
                       {{ old('is_main', $clinic->is_main ?? false) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="is_main">
                    <i class="fas fa-star text-warning me-2"></i>
                    العيادة الرئيسية
                </label>
                <small class="d-block text-muted mt-1">
                    العيادة الرئيسية هي الافتراضية عند إنشاء المواعيد
                </small>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-user-md text-info me-2"></i>
                    الأطباء المتعاقدون مع العيادة
                </h6>
                <small class="text-muted">اختر الأطباء الذين يعملون في هذه العيادة (يمكن اختيار أكثر من طبيب)</small>
            </div>
            <div class="card-body">
                @if($doctors->count() > 0)
                <div class="row g-2">
                    @foreach($doctors as $doctor)
                    <div class="col-md-6 col-lg-4">
                        <div class="form-check doctor-check-card">
                            <input class="form-check-input" type="checkbox"
                                   id="doctor_{{ $doctor->id }}"
                                   name="doctors[]"
                                   value="{{ $doctor->id }}"
                                   @if(in_array($doctor->id, old('doctors', $assignedDoctorIds))) checked @endif>
                            <label class="form-check-label w-100" for="doctor_{{ $doctor->id }}">
                                <i class="fas fa-user-md text-primary me-1"></i> {{ $doctor->name }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    لا يوجد أطباء مسجلين حالياً. يمكنك إضافة أطباء من إدارة المستخدمين.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    transition: all 0.2s ease;
}

.form-control-lg:focus {
    border-color: #0d9488;
    box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.1);
}

.form-check-input {
    cursor: pointer;
}

.doctor-check-card {
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.2s;
    cursor: pointer;
}

.doctor-check-card:hover {
    border-color: #0d9488;
    background: #f8fafc;
}

.doctor-check-card .form-check-input:checked ~ .form-check-label {
    color: #0d9488;
    font-weight: 600;
}

.doctor-check-card:has(.form-check-input:checked) {
    border-color: #0d9488;
    background: #f0fdfa;
}
</style>
