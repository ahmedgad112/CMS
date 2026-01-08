@extends('layouts.app')

@section('title', 'تفاصيل التخصص')
@section('page-title', 'تفاصيل التخصص')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-info text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="page-header-icon me-3">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $specialization->name }}</h5>
                            @if($specialization->name_en)
                            <small class="opacity-75">{{ $specialization->name_en }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.specializations.edit', $specialization->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-2"></i> تعديل
                        </a>
                        <a href="{{ route('admin.specializations.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right me-2"></i> العودة
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="stat-card stat-card-primary text-center">
                            <div class="stat-card-icon mx-auto mb-3">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="stat-card-value">{{ $specialization->doctors_count }}</div>
                            <div class="stat-card-label">طبيب</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card stat-card-info text-center">
                            <div class="stat-card-icon mx-auto mb-3">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-card-value small">{{ $specialization->department->name }}</div>
                            <div class="stat-card-label">القسم</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card stat-card-success text-center">
                            <div class="stat-card-icon mx-auto mb-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-card-value">
                                @if($specialization->is_active)
                                    <span class="text-success">نشط</span>
                                @else
                                    <span class="text-secondary">غير نشط</span>
                                @endif
                            </div>
                            <div class="stat-card-label">الحالة</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card stat-card-warning text-center">
                            <div class="stat-card-icon mx-auto mb-3">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="stat-card-value small">{{ $specialization->created_at->format('Y-m-d') }}</div>
                            <div class="stat-card-label">تاريخ الإنشاء</div>
                        </div>
                    </div>
                </div>

                @if($specialization->description)
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        الوصف
                    </h6>
                    <p class="text-muted mb-0">{{ $specialization->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Doctors -->
        @if($specialization->doctors->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-user-md me-2"></i>
                    الأطباء ({{ $specialization->doctors->count() }})
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>الاسم</th>
                                <th>القسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الحالة</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($specialization->doctors as $doctor)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 35px; height: 35px;">
                                            <i class="fas fa-user-md"></i>
                                        </div>
                                        <strong>{{ $doctor->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    @if($doctor->department)
                                        <span class="badge bg-info">{{ $doctor->department->name }}</span>
                                    @endif
                                </td>
                                <td>{{ $doctor->email }}</td>
                                <td>
                                    @if($doctor->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-md fa-3x text-muted opacity-50 mb-3"></i>
                <p class="text-muted">لا يوجد أطباء مرتبطين بهذا التخصص</p>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
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
    margin-top: 0.5rem;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
}
</style>
@endsection

