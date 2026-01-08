@extends('layouts.app')

@section('title', 'إدارة التخصصات')
@section('page-title', 'إدارة التخصصات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon me-3">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">قائمة التخصصات</h5>
                        <small class="opacity-75">إدارة جميع التخصصات الطبية في النظام</small>
                    </div>
                </div>
                <a href="{{ route('admin.specializations.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-2"></i> إضافة تخصص جديد
                </a>
            </div>
            <div class="card-body p-4">
                <!-- Filter -->
                <form method="GET" action="{{ route('admin.specializations.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-filter text-primary me-1"></i> الفلترة بالقسم
                            </label>
                            <select name="department_id" class="form-select" onchange="this.form.submit()">
                                <option value="">جميع الأقسام</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Statistics Cards -->
                @php
                    $totalSpecializations = $specializations->total();
                    $activeSpecializations = $specializations->where('is_active', true)->count();
                    $totalDoctors = $specializations->sum('doctors_count');
                    $departmentsCount = $specializations->pluck('department_id')->unique()->count();
                @endphp
                <div class="row g-3 mb-4">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-info">
                            <div class="stat-card-icon">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">إجمالي التخصصات</div>
                                <div class="stat-card-value">{{ $totalSpecializations }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-success">
                            <div class="stat-card-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">تخصصات نشطة</div>
                                <div class="stat-card-value">{{ $activeSpecializations }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">إجمالي الأطباء</div>
                                <div class="stat-card-value">{{ $totalDoctors }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-warning">
                            <div class="stat-card-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">عدد الأقسام</div>
                                <div class="stat-card-value">{{ $departmentsCount }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                </div>

                @if($specializations->count() > 0)
                <!-- Specializations Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th>اسم التخصص</th>
                                <th width="150" class="text-center">القسم</th>
                                <th width="120" class="text-center">عدد الأطباء</th>
                                <th width="100" class="text-center">الحالة</th>
                                <th width="180" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($specializations as $specialization)
                            <tr class="specialization-row">
                                <td class="text-center text-muted fw-semibold">
                                    {{ $loop->iteration + ($specializations->currentPage() - 1) * $specializations->perPage() }}
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">{{ $specialization->name }}</h6>
                                        @if($specialization->description)
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ Str::limit($specialization->description, 60) }}
                                        </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="fas fa-building me-1"></i>
                                        {{ $specialization->department->name }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="fas fa-user-md me-1"></i>
                                        {{ $specialization->doctors_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($specialization->is_active)
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> نشط
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i> غير نشط
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.specializations.show', $specialization->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="عرض التفاصيل"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.specializations.edit', $specialization->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="تعديل"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.specializations.destroy', $specialization->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا التخصص؟ سيتم إزالة التخصص من جميع الأطباء المرتبطين به.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="حذف"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $specializations->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="empty-state-icon mb-3">
                        <i class="fas fa-stethoscope fa-4x text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted mb-2">لا توجد تخصصات مسجلة</h5>
                    <p class="text-muted mb-4">ابدأ بإضافة تخصص جديد لإدارة الأطباء</p>
                    <a href="{{ route('admin.specializations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> إضافة تخصص جديد
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.specialization-row {
    transition: all 0.2s ease;
}

.specialization-row:hover {
    background-color: #f8f9fa;
    transform: translateX(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.stat-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1rem;
}

.stat-card-primary .stat-card-icon {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
}

.stat-card-success .stat-card-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-card-info .stat-card-icon {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
}

.stat-card-warning .stat-card-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-card-label {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
}

.stat-card-decoration {
    position: absolute;
    top: -20px;
    right: -20px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    opacity: 0.1;
    background: currentColor;
}

.empty-state-icon {
    opacity: 0.3;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #64748b;
    border-bottom: 2px solid #e2e8f0;
    padding: 1rem;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
</style>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
