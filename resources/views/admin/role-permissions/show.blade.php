@extends('layouts.app')

@section('title', 'تفاصيل الدور')
@section('page-title', 'تفاصيل الدور')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon me-3">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $role->name }}</h5>
                        <small class="opacity-75">تفاصيل الدور والصلاحيات</small>
                    </div>
                </div>
                <a href="{{ route('admin.role-permissions.edit', $role) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-edit me-2"></i> تعديل
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-tag me-2 text-primary"></i>اسم الدور
                            </div>
                            <div class="info-value">{{ $role->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-code me-2 text-info"></i>المعرف (Slug)
                            </div>
                            <div class="info-value">
                                <code class="bg-light px-2 py-1 rounded">{{ $role->slug }}</code>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-info-circle me-2 text-primary"></i>النوع
                            </div>
                            <div class="info-value">
                                @if($role->is_system)
                                    <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 rounded-pill">
                                        <i class="fas fa-shield-alt me-1"></i>نظام
                                    </span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info px-3 py-2 rounded-pill">
                                        <i class="fas fa-user me-1"></i>مخصص
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-users me-2 text-success"></i>عدد المستخدمين
                            </div>
                            <div class="info-value">
                                <span class="fw-bold text-primary fs-5">{{ $role->users_count }}</span>
                            </div>
                        </div>
                    </div>
                    @if($role->description)
                    <div class="col-md-12">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-align-right me-2 text-muted"></i>الوصف
                            </div>
                            <div class="info-value">{{ $role->description }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Permissions -->
        @if($permissions->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon me-3">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">الصلاحيات الممنوحة</h5>
                        <small class="opacity-75">جميع الصلاحيات المرتبطة بهذا الدور</small>
                    </div>
                </div>
                <span class="badge bg-light text-primary px-3 py-2">
                    {{ $permissions->flatten()->count() }} صلاحية
                </span>
            </div>
            <div class="card-body p-0">
                <div class="permissions-list p-4">
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
                                    <div class="permission-item d-flex align-items-center p-2 bg-light rounded">
                                        <i class="fas fa-check-circle text-success me-2"></i>
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
            </div>
        </div>
        @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-key fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد صلاحيات</h5>
                <p class="text-muted">لم يتم ربط أي صلاحيات بهذا الدور</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Users with this role -->
        @if($role->users->count() > 0)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-users me-2"></i>المستخدمون
                    <span class="badge bg-light text-info ms-2">{{ $role->users->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($role->users as $user)
                    <a href="{{ route('admin.users.show', $user->id) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <i class="fas fa-arrow-left text-muted"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center py-4">
                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">لا يوجد مستخدمون بهذا الدور</p>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.role-permissions.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>تعديل الدور
                    </a>
                    @if(!$role->is_system && $role->users_count == 0)
                    <form action="{{ route('admin.role-permissions.destroy', $role) }}" 
                          method="POST" 
                          onsubmit="return confirm('هل أنت متأكد من حذف الدور {{ $role->name }}؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>حذف الدور
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>رجوع للقائمة
                    </a>
                </div>
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

    .info-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        transition: all 0.2s;
    }

    .info-item:hover {
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1.05rem;
        color: #212529;
        font-weight: 500;
    }

    .permission-category {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .category-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .permission-item {
        transition: all 0.2s;
    }

    .permission-item:hover {
        background: #e9ecef !important;
        transform: translateX(-4px);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        flex-shrink: 0;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #f1f3f5;
        transition: all 0.2s;
    }

    .list-group-item:hover {
        background: #f8f9fa;
        transform: translateX(-4px);
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush
@endsection

