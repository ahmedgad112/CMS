@extends('layouts.app')

@section('title', 'تفاصيل الموعد')
@section('page-title', 'تفاصيل الموعد')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Appointment Header Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i> معلومات الموعد
                    </h5>
                </div>
                <div>
                    @php
                        $statusInfo = [
                            'pending' => ['name' => 'معلق', 'color' => 'warning', 'icon' => 'fa-clock'],
                            'confirmed' => ['name' => 'مؤكد', 'color' => 'info', 'icon' => 'fa-check-circle'],
                            'completed' => ['name' => 'مكتمل', 'color' => 'success', 'icon' => 'fa-check-double'],
                            'canceled' => ['name' => 'ملغي', 'color' => 'danger', 'icon' => 'fa-times-circle']
                        ];
                        $currentStatus = $statusInfo[$appointment->status] ?? $statusInfo['pending'];
                    @endphp
                    <span class="badge bg-{{ $currentStatus['color'] }} fs-6">
                        <i class="fas {{ $currentStatus['icon'] }} me-1"></i>
                        {{ $currentStatus['name'] }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Date and Time -->
                    <div class="col-12">
                        <div class="bg-light rounded p-3 text-center">
                            <i class="fas fa-calendar-alt text-primary fs-4 mb-2"></i>
                            <div class="fs-5 fw-bold text-primary mb-1">
                                {{ $appointment->appointment_date->format('Y-m-d') }}
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $appointment->appointment_date->format('H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Patient Info -->
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-user me-1"></i> المريض
                            </label>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <a href="{{ route('patients.show', $appointment->patient_id) }}" 
                                       class="text-decoration-none fw-bold">
                                        {{ $appointment->patient->full_name }}
                                    </a>
                                    <div class="small text-muted">
                                        <i class="fas fa-phone me-1"></i>
                                        {{ $appointment->patient->phone_number }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Info -->
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-user-md me-1"></i> الطبيب
                            </label>
                            <div class="d-flex align-items-center">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div>
                                    <a href="{{ route('doctors.show', $appointment->doctor_id) }}" 
                                       class="text-decoration-none fw-bold">
                                        {{ $appointment->doctor->name }}
                                    </a>
                                    @if($appointment->doctor->specialization)
                                    <div class="small text-muted">
                                        <i class="fas fa-stethoscope me-1"></i>
                                        {{ $appointment->doctor->specialization }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Type -->
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-tag me-1"></i> نوع الموعد
                            </label>
                            <div>
                                @if(($appointment->appointment_type ?? 'checkup') == 'checkup')
                                    <span class="badge bg-primary fs-6">
                                        <i class="fas fa-stethoscope me-1"></i>كشف
                                    </span>
                                @else
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-comments me-1"></i>استشارة
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($appointment->notes)
                    <div class="col-12">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-sticky-note me-1"></i> الملاحظات
                            </label>
                            <p class="mb-0">{{ $appointment->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Created Info -->
                    <div class="col-md-6">
                        <div class="pb-2">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-user-plus me-1"></i> تم الإنشاء بواسطة
                            </label>
                            <span class="small">{{ $appointment->creator->name ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pb-2">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-calendar me-1"></i> تاريخ الإنشاء
                            </label>
                            <span class="small">{{ $appointment->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Related Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-link me-2"></i> معلومات إضافية
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-calendar-check text-primary fs-4 mb-2"></i>
                            <div class="fw-bold">{{ $appointment->patient->appointments()->count() }}</div>
                            <small class="text-muted">مواعيد المريض</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-prescription text-info fs-4 mb-2"></i>
                            <div class="fw-bold">{{ $appointment->patient->prescriptions()->count() }}</div>
                            <small class="text-muted">وصفات المريض</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i> إجراءات سريعة
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    @if(auth()->user()->isDoctor() && $appointment->doctor_id == auth()->id())
                        @if(!$appointment->prescription)
                            <!-- Create Prescription -->
                            <a href="{{ route('doctor.prescriptions.create', ['appointment_id' => $appointment->id]) }}" class="quick-action-card">
                                <div class="quick-action-icon bg-success">
                                    <i class="fas fa-prescription"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6 class="mb-1">كتابة الروشته</h6>
                                    <small class="text-muted">إنشاء وصفة طبية للمريض</small>
                                </div>
                                <div class="quick-action-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @else
                            <!-- View Prescription -->
                            <a href="{{ route('doctor.prescriptions.show', $appointment->prescription->id) }}" class="quick-action-card outline">
                                <div class="quick-action-icon bg-light text-success border-success">
                                    <i class="fas fa-file-prescription"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6 class="mb-1">عرض الروشته</h6>
                                    <small class="text-muted">عرض الوصفة الطبية</small>
                                </div>
                                <div class="quick-action-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @endif
                    @endif

                    @if(auth()->user()->canManageAppointments())
                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="quick-action-card outline">
                        <div class="quick-action-icon bg-light text-warning border-warning">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">تعديل الموعد</h6>
                            <small class="text-muted">تحديث معلومات الموعد</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
                    @endif

                    @if($appointment->status == 'confirmed' && auth()->user()->canManageAppointments() && !auth()->user()->isDoctor())
                    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                        <input type="hidden" name="doctor_id" value="{{ $appointment->doctor_id }}">
                        <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date->format('Y-m-d H:i:s') }}">
                        <input type="hidden" name="status" value="completed">
                        <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                        <button type="submit" class="quick-action-card w-100 text-start border-0 p-3" style="background: white; cursor: pointer;">
                            <div class="quick-action-icon bg-primary">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">تحديد كمكتمل</h6>
                                <small class="text-muted">تغيير حالة الموعد</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('patients.show', $appointment->patient_id) }}" class="quick-action-card outline">
                        <div class="quick-action-icon bg-light text-primary border-primary">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">عرض ملف المريض</h6>
                            <small class="text-muted">تفاصيل المريض الكاملة</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>

                    <a href="{{ route('doctors.show', $appointment->doctor_id) }}" class="quick-action-card outline">
                        <div class="quick-action-icon bg-light text-info border-info">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">عرض ملف الطبيب</h6>
                            <small class="text-muted">تفاصيل الطبيب</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>

                    <a href="{{ route('appointments.index') }}" class="quick-action-card outline">
                        <div class="quick-action-icon bg-light text-secondary border-secondary">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">العودة للقائمة</h6>
                            <small class="text-muted">عرض جميع المواعيد</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>

                    @if(auth()->user()->canManageAppointments() && $appointment->status != 'completed')
                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="quick-action-card w-100 text-start border-0 p-3" style="background: white; cursor: pointer; border: 2px solid #dc3545 !important;" onclick="return confirm('هل أنت متأكد من حذف هذا الموعد؟');">
                            <div class="quick-action-icon bg-danger">
                                <i class="fas fa-trash"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6 class="mb-1">حذف الموعد</h6>
                                <small class="text-muted">حذف الموعد نهائياً</small>
                            </div>
                            <div class="quick-action-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i> حالة الموعد
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $appointment->status == 'pending' ? 'active' : ($appointment->status != 'pending' ? 'completed' : '') }}">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <div class="fw-bold">معلق</div>
                            <small class="text-muted">تم إنشاء الموعد</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ $appointment->status == 'confirmed' ? 'active' : (in_array($appointment->status, ['completed', 'canceled']) ? 'completed' : '') }}">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <div class="fw-bold">مؤكد</div>
                            <small class="text-muted">تم تأكيد الموعد</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ $appointment->status == 'completed' ? 'active' : '' }}">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="fw-bold">مكتمل</div>
                            <small class="text-muted">تم إكمال الموعد</small>
                        </div>
                    </div>
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

    .timeline {
        position: relative;
        padding: 0;
        list-style: none;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        padding-right: 2rem;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        right: 0.4rem;
        top: 1.5rem;
        bottom: -1.5rem;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item.completed:not(:last-child)::before {
        background: #10b981;
    }

    .timeline-marker {
        position: absolute;
        right: 0;
        top: 0.25rem;
        width: 0.875rem;
        height: 0.875rem;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e2e8f0;
    }

    .timeline-item.completed .timeline-marker {
        box-shadow: 0 0 0 2px #10b981;
    }

    .timeline-item.active .timeline-marker {
        box-shadow: 0 0 0 3px currentColor;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .timeline-content {
        margin-right: 1.5rem;
    }
</style>
@endpush
@endsection

