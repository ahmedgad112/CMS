@extends('layouts.app')

@section('title', 'تفاصيل المريض')
@section('page-title', 'تفاصيل المريض')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">معلومات المريض</h5>
                @if(auth()->user()->canManagePatients())
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>الاسم الكامل:</strong>
                        <p>{{ $patient->full_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>رقم الهوية:</strong>
                        <p>{{ $patient->national_id }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>رقم الهاتف:</strong>
                        <p>{{ $patient->phone_number }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>الجنس:</strong>
                        <p>
                            @if($patient->gender == 'male')
                                <span class="badge bg-primary">ذكر</span>
                            @else
                                <span class="badge bg-danger">أنثى</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>العمر:</strong>
                        <p>{{ $patient->age }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاريخ الإضافة:</strong>
                        <p>{{ $patient->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @if($patient->address)
                    <div class="col-md-12 mb-3">
                        <strong>العنوان:</strong>
                        <p>{{ $patient->address }}</p>
                    </div>
                    @endif
                    @if($patient->medical_history)
                    <div class="col-md-12 mb-3">
                        <strong>التاريخ الطبي:</strong>
                        <p>{{ $patient->medical_history }}</p>
                    </div>
                    @endif
                    @if($patient->chronic_diseases)
                    <div class="col-md-12 mb-3">
                        <strong>
                            <i class="fas fa-heartbeat text-danger me-1"></i> الأمراض المزمنة:
                        </strong>
                        <div class="mt-2">
                            @php
                                $chronicDiseases = [
                                    'diabetes' => 'السكري',
                                    'hypertension' => 'ضغط الدم',
                                    'asthma' => 'الربو',
                                    'heart_disease' => 'أمراض القلب',
                                    'kidney_disease' => 'أمراض الكلى',
                                    'liver_disease' => 'أمراض الكبد',
                                    'arthritis' => 'التهاب المفاصل',
                                    'osteoporosis' => 'هشاشة العظام',
                                    'epilepsy' => 'الصرع',
                                    'thyroid' => 'أمراض الغدة الدرقية',
                                    'anemia' => 'فقر الدم',
                                    'copd' => 'مرض الانسداد الرئوي المزمن',
                                    'depression' => 'الاكتئاب',
                                    'anxiety' => 'القلق',
                                ];
                                $patientDiseases = json_decode($patient->chronic_diseases, true) ?? [];
                            @endphp
                            @if(is_array($patientDiseases) && count($patientDiseases) > 0)
                                @foreach($patientDiseases as $disease)
                                    @if(isset($chronicDiseases[$disease]))
                                        <span class="badge bg-danger me-2 mb-2">{{ $chronicDiseases[$disease] }}</span>
                                    @else
                                        <span class="badge bg-danger me-2 mb-2">{{ $disease }}</span>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-danger fw-semibold">{{ $patient->chronic_diseases }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Appointments -->
        @if($patient->appointments->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">المواعيد</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الطبيب</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient->appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('Y-m-d H:i') }}</td>
                                <td>{{ $appointment->doctor->name }}</td>
                                <td>
                                    @if($appointment->status == 'pending')
                                        <span class="badge bg-warning">معلق</span>
                                    @elseif($appointment->status == 'confirmed')
                                        <span class="badge bg-info">مؤكد</span>
                                    @elseif($appointment->status == 'completed')
                                        <span class="badge bg-success">مكتمل</span>
                                    @else
                                        <span class="badge bg-danger">ملغي</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">
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
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">إحصائيات</h5>
            </div>
            <div class="card-body">
                <p><strong>عدد المواعيد:</strong> {{ $patient->appointments->count() }}</p>
                <p><strong>عدد الوصفات:</strong> {{ $patient->prescriptions->count() }}</p>
                <p><strong>عدد الفواتير:</strong> {{ $patient->invoices->count() }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i> إجراءات سريعة
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    @if(auth()->user()->canManageAppointments())
                    <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="quick-action-card">
                        <div class="quick-action-icon bg-success">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">إضافة موعد جديد</h6>
                            <small class="text-muted">حجز موعد جديد لهذا المريض</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->isAccountant() || auth()->user()->isAdmin())
                    <a href="{{ route('invoices.create', ['patient_id' => $patient->id]) }}" class="quick-action-card">
                        <div class="quick-action-icon bg-warning">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">إنشاء فاتورة</h6>
                            <small class="text-muted">إصدار فاتورة جديدة للمريض</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->isAccountant() || auth()->user()->isAdmin())
                    @if($patient->invoices->where('status', 'unpaid')->count() > 0)
                    <a href="{{ route('payments.create', ['patient_id' => $patient->id]) }}" class="quick-action-card">
                        <div class="quick-action-icon bg-primary">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">تسجيل دفعة</h6>
                            <small class="text-muted">تسجيل دفعة للمريض</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
                    @endif
                    @endif

                    @if(auth()->user()->canManagePatients())
                    <a href="{{ route('patients.edit', $patient->id) }}" class="quick-action-card outline">
                        <div class="quick-action-icon bg-light text-warning border-warning">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">تعديل بيانات المريض</h6>
                            <small class="text-muted">تحديث معلومات المريض</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
                    @endif

                    <a href="{{ route('appointments.index', ['patient_id' => $patient->id]) }}" class="quick-action-card outline">
                        <div class="quick-action-icon bg-light text-info border-info">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">عرض جميع المواعيد</h6>
                            <small class="text-muted">قائمة مواعيد المريض</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>

                    @if(auth()->user()->hasPermission('view_invoices'))
                    <a href="{{ route('invoices.index', ['patient_id' => $patient->id]) }}" class="quick-action-card outline">
                        <div class="quick-action-icon bg-light text-success border-success">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="quick-action-content">
                            <h6 class="mb-1">عرض جميع الفواتير</h6>
                            <small class="text-muted">قائمة فواتير المريض</small>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
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

