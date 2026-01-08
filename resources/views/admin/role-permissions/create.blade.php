@extends('layouts.app')

@section('title', 'إضافة دور جديد')
@section('page-title', 'إضافة دور جديد')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon me-3">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">إضافة دور جديد</h5>
                        <small class="opacity-75">إنشاء دور جديد مع الصلاحيات</small>
                    </div>
                </div>
                <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-right me-2"></i> رجوع
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.role-permissions.store') }}">
                    @csrf

                    <div class="row g-4">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6 class="fw-bold mb-3 text-primary">
                                <i class="fas fa-info-circle me-2"></i>معلومات أساسية
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                اسم الدور <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   required
                                   placeholder="مثال: مدير النظام">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                المعرف (Slug) <span class="text-danger">*</span>
                                <small class="text-muted">(أحرف صغيرة وشرطات سفلية فقط)</small>
                            </label>
                            <input type="text" 
                                   name="slug" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   value="{{ old('slug') }}" 
                                   required
                                   pattern="[a-z_]+"
                                   placeholder="مثال: admin_role">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">الوصف</label>
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="3"
                                      placeholder="وصف الدور...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_system" 
                                       id="is_system" 
                                       value="1"
                                       {{ old('is_system') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_system">
                                    دور نظام (لا يمكن حذفه)
                                </label>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold mb-3 text-primary">
                                <i class="fas fa-key me-2"></i>الصلاحيات
                            </h6>
                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPermissions()">
                                    <i class="fas fa-check-double me-1"></i>تحديد الكل
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllPermissions()">
                                    <i class="fas fa-times me-1"></i>إلغاء التحديد
                                </button>
                            </div>

                            <div class="permissions-container">
                                @foreach($permissions as $category => $categoryPermissions)
                                <div class="permission-category mb-4">
                                    <div class="category-header bg-light p-3 rounded-top">
                                        <h6 class="mb-0 fw-bold text-primary">
                                            <i class="fas fa-folder me-2"></i>{{ $category }}
                                        </h6>
                                    </div>
                                    <div class="category-permissions bg-white p-3 rounded-bottom border">
                                        <div class="row g-2">
                                            @foreach($categoryPermissions as $permission)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}">
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                        @if($permission->description)
                                                            <small class="text-muted d-block">{{ $permission->description }}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ الدور
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function deselectAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    }
</script>
@endpush

@push('styles')
<style>
    .page-header-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .permission-category {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .category-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .permission-checkbox {
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
        user-select: none;
    }
</style>
@endpush
@endsection

