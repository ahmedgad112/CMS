@extends('layouts.app')

@section('title', 'الوصفات الطبية')
@section('page-title', 'الوصفات الطبية')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-prescription me-2"></i> قائمة الوصفات الطبية
        </h5>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('doctor.prescriptions.index') }}" class="mb-4">
            <div class="card bg-light border-0 mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-user-md text-primary me-1"></i> الطبيب
                            </label>
                            <select name="doctor_id" class="form-select" onchange="this.form.submit()">
                                <option value="">جميع الأطباء</option>
                                @foreach($doctors ?? [] as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('doctor_id'))
                        <div class="col-md-2 col-12 d-flex align-items-end">
                            <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-redo me-1"></i> إعادة تعيين
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    @if(request('doctor_id'))
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <small class="text-muted fw-semibold">
                                <i class="fas fa-filter me-1"></i> الفلاتر النشطة:
                            </small>
                            <span class="badge bg-primary">
                                <i class="fas fa-user-md me-1"></i> {{ $doctors->where('id', request('doctor_id'))->first()->name ?? '' }}
                                <a href="{{ route('doctor.prescriptions.index') }}" class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>

        @if($prescriptions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>المريض</th>
                        <th>تاريخ الزيارة</th>
                        <th>عدد الأدوية</th>
                        <th>تاريخ الوصفة</th>
                        <th width="150" class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescriptions as $prescription)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($prescriptions->currentPage() - 1) * $prescriptions->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 35px; height: 35px; font-size: 0.875rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <a href="{{ route('patients.show', $prescription->patient_id) }}" 
                                       class="text-decoration-none fw-bold">
                                        {{ $prescription->patient->full_name }}
                                    </a>
                                    <div class="small text-muted">
                                        <i class="fas fa-phone me-1"></i>
                                        {{ $prescription->patient->phone_number }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($prescription->appointment)
                                <i class="fas fa-calendar-check text-primary me-1"></i>
                                {{ $prescription->appointment->appointment_date->format('Y-m-d H:i') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">
                                <i class="fas fa-pills me-1"></i>
                                {{ $prescription->items->count() }}
                            </span>
                        </td>
                        <td class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ $prescription->created_at->format('Y-m-d') }}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" 
                                   class="btn btn-info" 
                                   title="عرض">
                                    <i class="fas fa-eye"></i>
                                    <span class="d-none d-md-inline ms-1">عرض</span>
                                </a>
                                <a href="{{ route('doctor.prescriptions.print', $prescription->id) }}" 
                                   class="btn btn-primary" 
                                   title="طباعة"
                                   target="_blank">
                                    <i class="fas fa-print"></i>
                                    <span class="d-none d-md-inline ms-1">طباعة</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $prescriptions->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            لا توجد وصفات طبية مسجلة حالياً.
        </div>
        @endif
    </div>
</div>
@endsection

