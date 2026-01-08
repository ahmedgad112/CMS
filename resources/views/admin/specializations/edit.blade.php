@extends('layouts.app')

@section('title', 'تعديل التخصص')
@section('page-title', 'تعديل التخصص')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon me-3">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">تعديل التخصص</h5>
                        <small class="opacity-75">تعديل معلومات التخصص: {{ $specialization->name }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.specializations.update', $specialization->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="department_id" class="form-label fw-semibold">
                                <i class="fas fa-building text-primary me-1"></i>
                                القسم <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('department_id') is-invalid @enderror" 
                                    id="department_id" 
                                    name="department_id" 
                                    required>
                                <option value="">اختر القسم</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $specialization->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اختر القسم الذي ينتمي إليه هذا التخصص</small>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-tag text-info me-1"></i>
                                اسم التخصص <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $specialization->name) }}" 
                                   placeholder="مثال: جراحة القلب"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اسم التخصص باللغة العربية</small>
                        </div>

                        <div class="col-md-6">
                            <label for="name_en" class="form-label fw-semibold">
                                <i class="fas fa-tag text-secondary me-1"></i>
                                اسم التخصص (إنجليزي)
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name_en') is-invalid @enderror" 
                                   id="name_en" 
                                   name="name_en" 
                                   value="{{ old('name_en', $specialization->name_en) }}"
                                   placeholder="Example: Cardiac Surgery">
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اسم التخصص باللغة الإنجليزية (اختياري)</small>
                        </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fas fa-align-right text-secondary me-1"></i>
                                الوصف
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="أدخل وصفاً مختصراً عن التخصص...">{{ old('description', $specialization->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">وصف مختصر عن التخصص ووظيفته</small>
                        </div>

                        <div class="col-md-12">
                            <div class="card bg-light border-0 p-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', $specialization->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">
                                        <i class="fas fa-toggle-on text-success me-2"></i>
                                        تخصص نشط
                                    </label>
                                    <small class="d-block text-muted mt-1">
                                        التخصص النشط سيظهر في قوائم الاختيار عند إضافة أطباء
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.specializations.index') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-times me-2"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-2"></i> حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.form-control-lg, .form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    transition: all 0.2s ease;
}

.form-control-lg:focus, .form-select-lg:focus {
    border-color: #06b6d4;
    box-shadow: 0 0 0 0.2rem rgba(6, 182, 212, 0.1);
}

.form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #10b981;
    border-color: #10b981;
}

.card.bg-light {
    background-color: #f8fafc !important;
}
</style>
@endsection

