@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="row g-4">
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2 text-white-50" style="font-size: 0.875rem;">المواعيد اليوم</h6>
                        <h2 class="mb-0" style="font-size: 2rem; font-weight: 600;">{{ $data['today_appointments'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-calendar fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2 text-white-50" style="font-size: 0.875rem;">مواعيد معلقة</h6>
                        <h2 class="mb-0" style="font-size: 2rem; font-weight: 600;">{{ $data['pending_appointments'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2 text-white-50" style="font-size: 0.875rem;">إجمالي المرضى</h6>
                        <h2 class="mb-0" style="font-size: 2rem; font-weight: 600;">{{ $data['total_patients'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isDoctor())
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2 text-white-50" style="font-size: 0.875rem;">مواعيدي اليوم</h6>
                        <h2 class="mb-0" style="font-size: 2rem; font-weight: 600;">{{ $data['my_appointments'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-user-md fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->isAccountant() || auth()->user()->isAdmin())
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2 text-white-50" style="font-size: 0.875rem;">إيرادات اليوم</h6>
                        <h2 class="mb-0" style="font-size: 1.75rem; font-weight: 600;">{{ number_format($data['today_revenue'] ?? 0, 2) }} <small style="font-size: 0.875rem;">ج.م</small></h2>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2 text-white-50" style="font-size: 0.875rem;">فواتير غير مدفوعة</h6>
                        <h2 class="mb-0" style="font-size: 2rem; font-weight: 600;">{{ $data['unpaid_invoices'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-file-invoice fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2 text-white-50" style="font-size: 0.875rem;">إيرادات الشهر</h6>
                        <h2 class="mb-0" style="font-size: 1.75rem; font-weight: 600;">{{ number_format($data['monthly_revenue'] ?? 0, 2) }} <small style="font-size: 0.875rem;">ج.م</small></h2>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i> إجراءات سريعة
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    @if(auth()->user()->canManagePatients())
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('patients.create') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-primary">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">إضافة مريض جديد</h6>
                                <small class="text-muted">تسجيل بيانات مريض جديد</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if(auth()->user()->canManageAppointments())
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('appointments.create') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-success">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">حجز موعد جديد</h6>
                                <small class="text-muted">إضافة موعد جديد للمريض</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif


                    @if((auth()->user()->isAccountant() || auth()->user()->isAdmin() || auth()->user()->isReceptionist()))
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('invoices.create') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-warning">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">إنشاء فاتورة</h6>
                                <small class="text-muted">إصدار فاتورة جديدة</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if(auth()->user()->isAccountant() || auth()->user()->isAdmin())
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('payments.create') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-primary">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">تسجيل دفعة</h6>
                                <small class="text-muted">تسجيل دفعة جديدة</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif


                    @if(auth()->user()->isAdmin())
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('admin.users.create') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-danger">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">إضافة مستخدم جديد</h6>
                                <small class="text-muted">إنشاء حساب مستخدم</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif

                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('patients.index') }}" class="quick-action-card outline">
                            <div class="quick-action-icon bg-light text-primary border-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">عرض جميع المرضى</h6>
                                <small class="text-muted">قائمة المرضى المسجلين</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('appointments.index') }}" class="quick-action-card outline">
                            <div class="quick-action-icon bg-light text-success border-success">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">عرض جميع المواعيد</h6>
                                <small class="text-muted">قائمة المواعيد</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>

                    @if(auth()->user()->isDoctor())
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('doctor.prescriptions.index') }}" class="quick-action-card outline">
                            <div class="quick-action-icon bg-light text-info border-info">
                                <i class="fas fa-prescription"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">الوصفات الطبية</h6>
                                <small class="text-muted">قائمة الوصفات</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if(auth()->user()->isDoctor())
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('doctor.prescriptions.index') }}" class="quick-action-card outline">
                            <div class="quick-action-icon bg-light text-warning border-warning">
                                <i class="fas fa-prescription"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">الوصفات الطبية</h6>
                                <small class="text-muted">قائمة الوصفات</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif

                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('doctors.index') }}" class="quick-action-card outline">
                            <div class="quick-action-icon bg-light text-primary border-primary">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">عرض الأطباء</h6>
                                <small class="text-muted">قائمة الأطباء والتخصصات</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>

                    @if(auth()->user()->hasPermission('view_invoices'))
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('invoices.index') }}" class="quick-action-card outline">
                            <div class="quick-action-icon bg-light text-info border-info">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">عرض الفواتير</h6>
                                <small class="text-muted">قائمة الفواتير</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if(auth()->user()->hasPermission('view_reports'))
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('reports.index') }}" class="quick-action-card outline">
                            <div class="quick-action-icon bg-light text-secondary border-secondary">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">التقارير</h6>
                                <small class="text-muted">عرض التقارير والإحصائيات</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .quick-action-card {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        min-height: 100px;
    }
    
    .quick-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .quick-action-card:hover::before {
        opacity: 1;
    }
    
    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }
    
    .quick-action-card.outline:hover {
        border-color: var(--primary-color);
        background: #f8fafc;
    }
    
    .quick-action-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
        margin-left: 1rem;
        transition: all 0.3s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .quick-action-card.outline .quick-action-icon {
        border: 2px solid;
        background: white !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .quick-action-card:hover .quick-action-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    
    .quick-action-content {
        flex: 1;
        min-width: 0;
    }
    
    .quick-action-content h6 {
        margin: 0;
        font-weight: 600;
        color: var(--text-color);
        font-size: 1rem;
    }
    
    .quick-action-content small {
        display: block;
        font-size: 0.8125rem;
        margin-top: 0.25rem;
    }
    
    .quick-action-arrow {
        color: var(--primary-color);
        font-size: 1.25rem;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s;
        margin-right: 0.5rem;
    }
    
    .quick-action-card:hover .quick-action-arrow {
        opacity: 1;
        transform: translateX(0);
    }
    
    @media (max-width: 767.98px) {
        .quick-action-card {
            padding: 1rem;
            min-height: 90px;
        }
        
        .quick-action-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
            margin-left: 0.75rem;
        }
        
        .quick-action-content h6 {
            font-size: 0.9375rem;
        }
        
        .quick-action-content small {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@endsection

