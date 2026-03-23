@extends('layouts.app')

@section('title', 'صلاحيات المستخدم')
@section('page-title', 'صلاحيات المستخدم')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-user me-2"></i>معلومات المستخدم
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <div class="user-avatar-large mx-auto mb-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="fw-bold">{{ $user->name }}</h5>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                </div>
                <hr>
                <div class="info-item mb-3">
                    <div class="info-label">
                        <i class="fas fa-user-tag me-2 text-primary"></i>الدور الحالي
                    </div>
                    <div class="info-value">
                        <span class="badge bg-primary px-3 py-2">{{ $user->role }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-toggle-on me-2 text-success"></i>الحالة
                    </div>
                    <div class="info-value">
                        @if($user->is_active)
                            <span class="badge bg-success px-3 py-2">نشط</span>
                        @else
                            <span class="badge bg-danger px-3 py-2">غير نشط</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>عرض تفاصيل المستخدم
                    </a>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>تعديل المستخدم
                    </a>
                    <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>رجوع للأدوار
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon me-3">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">صلاحيات المستخدم</h5>
                        <small class="opacity-75">الصلاحيات المرتبطة بدور المستخدم</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.permissions.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user-tag me-2 text-primary"></i>تغيير الدور
                        </label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror">
                            @php
                                $roles = \App\Models\Role::all();
                            @endphp
                            @foreach($roles as $roleOption)
                                <option value="{{ $roleOption->slug }}" {{ $user->role == $roleOption->slug ? 'selected' : '' }}>
                                    {{ $roleOption->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">تغيير الدور سيؤثر على جميع الصلاحيات المرتبطة به</small>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <h6 class="fw-bold mb-3 text-primary">
                            <i class="fas fa-key me-2"></i>الصلاحيات الحالية
                        </h6>
                        <p class="text-muted">
                            الصلاحيات التالية مرتبطة بدور <strong>{{ $user->role }}</strong>
                        </p>
                    </div>

                    <div class="permissions-container">
                        @foreach($permissions as $category => $categoryPermissions)
                        <div class="permission-category mb-4">
                            <div class="category-header bg-light p-3 rounded-top">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-folder me-2"></i>{{ $category }}
                                    <span class="badge bg-primary ms-2">{{ $categoryPermissions->count() }}</span>
                                </h6>
                            </div>
                            <div class="category-permissions bg-white p-3 rounded-bottom border">
                                <div class="row g-2">
                                    @foreach($categoryPermissions as $permission)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="permission-item d-flex align-items-center p-2 rounded {{ in_array($permission->id, $userRolePermissions) ? 'bg-success-subtle' : 'bg-light' }}">
                                            @if(in_array($permission->id, $userRolePermissions))
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                            @else
                                                <i class="fas fa-times-circle text-muted me-2"></i>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $permission->name }}</div>
                                                @if($permission->description)
                                                    <small class="text-muted">{{ $permission->description }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>ملاحظة:</strong> الصلاحيات مرتبطة بالأدوار. لتغيير صلاحيات المستخدم، قم بتغيير دوره أو عدّل صلاحيات الدور من صفحة إدارة الأدوار.
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

    .user-avatar-large {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .info-item {
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        color: #212529;
        font-weight: 500;
    }

    .permission-category {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .category-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .permission-item {
        transition: all 0.2s;
    }

    .bg-success-subtle {
        background-color: #d1fae5 !important;
        border: 1px solid #10b981;
    }
</style>
@endpush
@endsection

