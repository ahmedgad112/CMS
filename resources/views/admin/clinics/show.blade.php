@extends('layouts.app')

@section('title', 'تفاصيل العيادة')
@section('page-title', 'تفاصيل العيادة')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('admin.clinics.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-right me-2"></i> رجوع للقائمة
            </a>
            <a href="{{ route('admin.clinics.edit', $clinic->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-2"></i> تعديل العيادة
            </a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <div class="page-header-icon me-3">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">
                                {{ $clinic->name }}
                                @if($clinic->is_main)
                                    <span class="badge bg-warning text-dark ms-2">
                                        <i class="fas fa-star"></i> الرئيسية
                                    </span>
                                @endif
                            </h5>
                            @if($clinic->name_en)
                                <small class="opacity-75">{{ $clinic->name_en }}</small>
                            @endif
                        </div>
                    </div>
                    @if($clinic->is_active)
                        <span class="badge bg-success">نشطة</span>
                    @else
                        <span class="badge bg-secondary">غير نشطة</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-row">
                            <i class="fas fa-phone text-success"></i>
                            <div>
                                <small class="text-muted d-block">الهاتف</small>
                                <span class="fw-semibold">{{ $clinic->phone ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <i class="fas fa-envelope text-info"></i>
                            <div>
                                <small class="text-muted d-block">البريد الإلكتروني</small>
                                <span class="fw-semibold">{{ $clinic->email ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <i class="fas fa-city text-warning"></i>
                            <div>
                                <small class="text-muted d-block">المدينة</small>
                                <span class="fw-semibold">{{ $clinic->city ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <i class="fas fa-clock text-secondary"></i>
                            <div>
                                <small class="text-muted d-block">مواعيد العمل</small>
                                <span class="fw-semibold">{{ $clinic->working_hours ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-row">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                            <div>
                                <small class="text-muted d-block">العنوان</small>
                                <span class="fw-semibold">{{ $clinic->address ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($clinic->description)
                    <div class="col-12">
                        <div class="info-row">
                            <i class="fas fa-align-right text-muted"></i>
                            <div>
                                <small class="text-muted d-block">الوصف</small>
                                <span>{{ $clinic->description }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-user-md me-2"></i>
                    الأطباء المتعاقدون ({{ $clinic->doctors->count() }})
                </h5>
            </div>
            <div class="card-body p-4">
                @if($clinic->doctors->count() > 0)
                <div class="row g-3">
                    @foreach($clinic->doctors as $doctor)
                    <div class="col-md-6">
                        <div class="doctor-card">
                            <div class="doctor-avatar">
                                {{ mb_substr($doctor->name, 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $doctor->name }}</h6>
                                <small class="text-muted d-block">
                                    @if($doctor->specialization)
                                        <i class="fas fa-stethoscope me-1"></i> {{ $doctor->specialization->name }}
                                    @endif
                                    @if($doctor->department)
                                        <span class="mx-1">•</span>
                                        <i class="fas fa-building me-1"></i> {{ $doctor->department->name }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-user-md fa-2x mb-2 opacity-50"></i>
                    <p class="mb-0">لا يوجد أطباء متعاقدون مع هذه العيادة بعد</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-chart-bar me-2"></i> إحصائيات
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="stat-box mb-3">
                    <div class="stat-box-icon bg-primary">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <small class="text-muted">عدد الأطباء</small>
                        <h4 class="mb-0 fw-bold">{{ $clinic->doctors_count }}</h4>
                    </div>
                </div>
                <div class="stat-box mb-3">
                    <div class="stat-box-icon bg-info">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <small class="text-muted">إجمالي المواعيد</small>
                        <h4 class="mb-0 fw-bold">{{ $clinic->appointments_count }}</h4>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-icon bg-warning">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <small class="text-muted">مواعيد اليوم</small>
                        <h4 class="mb-0 fw-bold">{{ $todayAppointments }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-row {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 8px;
}

.info-row i {
    font-size: 1.25rem;
    width: 30px;
    text-align: center;
    margin-top: 0.25rem;
}

.doctor-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}

.doctor-card:hover {
    background: #f0fdfa;
    border-color: #0d9488;
}

.doctor-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0d9488, #0f766e);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-box {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
}

.stat-box-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
</style>
@endsection
