@extends('layouts.app')

@section('title', 'إدارة الأدوار والصلاحيات')
@section('page-title', 'إدارة الأدوار والصلاحيات')

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
                        <h5 class="mb-0 fw-bold">قائمة الأدوار</h5>
                        <small class="opacity-75">إدارة جميع الأدوار والصلاحيات في النظام</small>
                    </div>
                </div>
                <a href="{{ route('admin.role-permissions.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-2"></i> إضافة دور جديد
                </a>
            </div>
            <div class="card-body p-4">
                <!-- Statistics Cards -->
                @php
                    $totalRoles = $roles->total();
                    $systemRoles = $roles->where('is_system', true)->count();
                    $customRoles = $roles->where('is_system', false)->count();
                    $totalUsers = $roles->sum('users_count');
                @endphp
                <div class="row g-3 mb-4">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">إجمالي الأدوار</div>
                                <div class="stat-card-value">{{ $totalRoles }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-warning">
                            <div class="stat-card-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">أدوار النظام</div>
                                <div class="stat-card-value">{{ $systemRoles }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-info">
                            <div class="stat-card-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">أدوار مخصصة</div>
                                <div class="stat-card-value">{{ $customRoles }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-success">
                            <div class="stat-card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">إجمالي المستخدمين</div>
                                <div class="stat-card-value">{{ $totalUsers }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                </div>

                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.role-permissions.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-10 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-search me-1 text-muted"></i> البحث
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control border-start-0" 
                                       placeholder="ابحث بالاسم أو الوصف..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-12 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-search d-md-inline d-none me-md-2"></i>
                                <span>بحث</span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Roles Table -->
                @if($roles->count() > 0)
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle roles-table mb-0">
                        <thead>
                            <tr>
                                <th width="70" class="text-center">#</th>
                                <th>اسم الدور</th>
                                <th width="150" class="text-center">النوع</th>
                                <th width="150" class="text-center">عدد المستخدمين</th>
                                <th width="260" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark fw-bold px-3 py-2">
                                        {{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.role-permissions.show', $role) }}" class="text-decoration-none fw-bold text-dark role-name">
                                        {{ $role->name }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if($role->is_system)
                                        <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 rounded-pill">
                                            <i class="fas fa-shield-alt me-1"></i>نظام
                                        </span>
                                    @else
                                        <span class="badge bg-info-subtle text-info border border-info px-3 py-2 rounded-pill">
                                            <i class="fas fa-user me-1"></i>مخصص
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="users-count-wrapper">
                                        <span class="fw-bold text-primary">{{ $role->users_count }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="{{ route('admin.role-permissions.show', $role) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-lg-inline ms-1">عرض</span>
                                        </a>
                                        <a href="{{ route('admin.role-permissions.edit', $role) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                            <span class="d-none d-lg-inline ms-1">تعديل</span>
                                        </a>
                                        @if(!$role->is_system && $role->users_count == 0)
                                        <form action="{{ route('admin.role-permissions.destroy', $role) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف الدور {{ $role->name }}؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                                <span class="d-none d-lg-inline ms-1">حذف</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $roles->links() }}
                </div>
                @else
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-3">
                        <i class="fas fa-user-shield fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-2">لا توجد أدوار مسجلة</h5>
                    <p class="text-muted mb-4">
                        @if(request('search'))
                            لم يتم العثور على أدوار تطابق البحث "{{ request('search') }}"
                        @else
                            لا توجد أدوار مسجلة حالياً في النظام
                        @endif
                    </p>
                    <a href="{{ route('admin.role-permissions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> إضافة دور جديد
                    </a>
                </div>
                @endif
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

    .stat-card {
        position: relative;
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: white;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card-primary .stat-card-icon {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    
    .stat-card-warning .stat-card-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .stat-card-info .stat-card-icon {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    }
    
    .stat-card-success .stat-card-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .stat-card-content {
        position: relative;
        z-index: 2;
    }
    
    .stat-card-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.4rem;
    }
    
    .stat-card-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }
    
    .stat-card-primary .stat-card-value {
        color: #2563eb;
    }
    
    .stat-card-warning .stat-card-value {
        color: #f59e0b;
    }
    
    .stat-card-info .stat-card-value {
        color: #06b6d4;
    }
    
    .stat-card-success .stat-card-value {
        color: #10b981;
    }
    
    .stat-card-decoration {
        position: absolute;
        bottom: -20px;
        left: -20px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        opacity: 0.1;
        z-index: 1;
    }
    
    .stat-card-primary .stat-card-decoration {
        background: #2563eb;
    }
    
    .stat-card-warning .stat-card-decoration {
        background: #f59e0b;
    }
    
    .stat-card-info .stat-card-decoration {
        background: #06b6d4;
    }
    
    .stat-card-success .stat-card-decoration {
        background: #10b981;
    }

    .role-name {
        font-size: 1.05rem;
        transition: all 0.2s;
        display: inline-block;
    }

    .role-name:hover {
        color: #2563eb !important;
        transform: translateX(-2px);
    }

    .users-count-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 50px;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border-radius: 8px;
        border: 1px solid #7dd3fc;
    }

    .users-count-wrapper span {
        font-size: 1.1rem;
        color: #0369a1;
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    .roles-table {
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .roles-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .roles-table thead th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        padding: 1.125rem 1rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
    }

    .roles-table thead th:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        bottom: 20%;
        width: 1px;
        background: #dee2e6;
    }

    .roles-table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        background: white;
    }

    .roles-table tbody tr {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
    }

    .roles-table tbody tr:hover {
        background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateX(4px);
    }

    .roles-table tbody tr:last-child td {
        border-bottom: none;
    }

    .btn-outline-info,
    .btn-outline-warning,
    .btn-outline-danger {
        border-width: 1.5px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
    }

    .btn-outline-info:hover {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(13, 202, 240, 0.4);
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 193, 7, 0.4);
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4);
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        opacity: 0.5;
    }

    @media (max-width: 767.98px) {
        .stat-card {
            min-height: 100px;
            padding: 1rem;
        }
        
        .stat-card-icon {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }
        
        .stat-card-value {
            font-size: 1.4rem;
        }

        .roles-table thead th,
        .roles-table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-sm {
            padding: 0.375rem 0.5rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush
@endsection

