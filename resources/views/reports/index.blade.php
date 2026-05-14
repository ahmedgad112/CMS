@extends('layouts.app')

@section('title', 'التقارير')
@section('page-title', 'التقارير')

@section('content')
<div class="row g-4">
    @if(auth()->user()->hasPermission('financial_reports'))
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-chart-line fa-3x text-primary"></i>
                </div>
                <h5 class="card-title fw-bold mb-3">التقارير المالية</h5>
                <p class="card-text text-muted mb-4">عرض الإيرادات والمصروفات والفواتير</p>
                <a href="{{ route('reports.financial') }}" class="btn btn-primary w-100">
                    <i class="fas fa-eye me-2"></i> عرض التقرير
                </a>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasPermission('doctor_reports'))
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-user-md fa-3x text-success"></i>
                </div>
                <h5 class="card-title fw-bold mb-3">أداء الأطباء</h5>
                <p class="card-text text-muted mb-4">إحصائيات الزيارات والوصفات الطبية</p>
                <a href="{{ route('reports.doctor-performance') }}" class="btn btn-success w-100">
                    <i class="fas fa-eye me-2"></i> عرض التقرير
                </a>
            </div>
        </div>
    </div>
    @endif

    @if(! auth()->user()->hasPermission('financial_reports') && ! auth()->user()->hasPermission('doctor_reports'))
    <div class="col-12">
        <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle me-2"></i> لا توجد تقارير متاحة لصلاحيات حسابك الحالية.
        </div>
    </div>
    @endif

</div>
@endsection

