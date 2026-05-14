@extends('layouts.app')

@section('title', 'إدارة العيادات')
@section('page-title', 'إدارة العيادات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon me-3">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">قائمة العيادات</h5>
                        <small class="opacity-75">إدارة كل العيادات والفروع التابعة للنظام</small>
                    </div>
                </div>
                <a href="{{ route('admin.clinics.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-2"></i> إضافة عيادة جديدة
                </a>
            </div>
            <div class="card-body p-4">
                @php
                    $totalClinics = $clinics->total();
                    $activeClinics = $clinics->where('is_active', true)->count();
                    $totalDoctors = $clinics->sum('doctors_count');
                    $totalAppointments = $clinics->sum('appointments_count');
                @endphp
                <div class="row g-3 mb-4">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-icon">
                                <i class="fas fa-hospital"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">إجمالي العيادات</div>
                                <div class="stat-card-value">{{ $totalClinics }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-success">
                            <div class="stat-card-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">عيادات نشطة</div>
                                <div class="stat-card-value">{{ $activeClinics }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-info">
                            <div class="stat-card-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">أطباء متعاقدون</div>
                                <div class="stat-card-value">{{ $totalDoctors }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-warning">
                            <div class="stat-card-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">إجمالي المواعيد</div>
                                <div class="stat-card-value">{{ $totalAppointments }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($clinics->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th>اسم العيادة</th>
                                <th>المدينة</th>
                                <th>الهاتف</th>
                                <th width="120" class="text-center">عدد الأطباء</th>
                                <th width="120" class="text-center">عدد المواعيد</th>
                                <th width="100" class="text-center">الحالة</th>
                                <th width="200" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clinics as $clinic)
                            <tr>
                                <td class="text-center text-muted fw-semibold">
                                    {{ $loop->iteration + ($clinics->currentPage() - 1) * $clinics->perPage() }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="clinic-icon">
                                            <i class="fas fa-hospital"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">
                                                {{ $clinic->name }}
                                                @if($clinic->is_main)
                                                    <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">
                                                        <i class="fas fa-star"></i> الرئيسية
                                                    </span>
                                                @endif
                                            </h6>
                                            @if($clinic->name_en)
                                                <small class="text-muted">{{ $clinic->name_en }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $clinic->city ?? '—' }}</td>
                                <td>
                                    @if($clinic->phone)
                                        <a href="tel:{{ $clinic->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1 text-primary"></i> {{ $clinic->phone }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info rounded-pill px-3 py-2">
                                        <i class="fas fa-user-md me-1"></i> {{ $clinic->doctors_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        <i class="fas fa-calendar me-1"></i> {{ $clinic->appointments_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($clinic->is_active)
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> نشطة
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i> غير نشطة
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.clinics.show', $clinic->id) }}"
                                           class="btn btn-sm btn-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.clinics.edit', $clinic->id) }}"
                                           class="btn btn-sm btn-warning" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.clinics.destroy', $clinic->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه العيادة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">
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

                <div class="d-flex justify-content-center mt-4">
                    {{ $clinics->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-3" style="opacity: 0.3;">
                        <i class="fas fa-hospital fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-2">لا توجد عيادات مسجلة</h5>
                    <p class="text-muted mb-4">ابدأ بإضافة عيادة جديدة وربط الأطباء بها</p>
                    <a href="{{ route('admin.clinics.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> إضافة عيادة جديدة
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.stat-card-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.stat-card-primary .stat-card-icon { background: linear-gradient(135deg, #0d9488, #0f766e); }
.stat-card-success .stat-card-icon { background: linear-gradient(135deg, #10b981, #059669); }
.stat-card-info .stat-card-icon { background: linear-gradient(135deg, #06b6d4, #0891b2); }
.stat-card-warning .stat-card-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

.stat-card-label {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.stat-card-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
}

.clinic-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
    color: #0d9488;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
</style>
@endsection
