@extends('layouts.app')

@section('title', 'تفاصيل المريض')
@section('page-title', 'تفاصيل المريض')

@section('content')
<div class="patient-show-page">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-right me-1"></i> العودة لقائمة المرضى
        </a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            {{-- بطاقة التعريف --}}
            <div class="card border-0 shadow-sm mb-4 patient-profile-summary">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3">
                        <div class="patient-avatar-xl flex-shrink-0 mx-auto mx-md-0">
                            {{ mb_substr($patient->full_name, 0, 1) }}
                        </div>
                        <div class="flex-grow-1 text-center text-md-end min-w-0">
                            <h4 class="mb-2 fw-bold text-truncate">{{ $patient->full_name }}</h4>
                            <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2 align-items-center">
                                <a href="tel:{{ $patient->phone_number }}" class="btn btn-sm btn-light border text-decoration-none">
                                    <i class="fas fa-phone text-primary me-1"></i>{{ $patient->phone_number }}
                                </a>
                                @if($patient->gender === 'male')
                                    <span class="badge bg-primary rounded-pill"><i class="fas fa-mars me-1"></i>ذكر</span>
                                @else
                                    <span class="badge bg-danger rounded-pill"><i class="fas fa-venus me-1"></i>أنثى</span>
                                @endif
                                <span class="badge bg-light text-dark border rounded-pill">
                                    <i class="fas fa-birthday-cake text-muted me-1"></i>{{ $patient->age }} سنة
                                </span>
                            </div>
                        </div>
                        @if(auth()->user()->hasPermission('edit_patients'))
                            <div class="d-flex flex-md-column gap-2 justify-content-center flex-shrink-0">
                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i> تعديل
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- تفاصيل إضافية --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-id-card text-primary me-2"></i>البيانات التفصيلية</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="patient-field">
                                <span class="patient-field-label">رقم الهوية</span>
                                <span class="patient-field-value">{{ $patient->national_id ?: '—' }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="patient-field">
                                <span class="patient-field-label">تاريخ التسجيل</span>
                                <span class="patient-field-value">{{ $patient->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                        @if($patient->clinic)
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="patient-field">
                                    <span class="patient-field-label">الفرع</span>
                                    <span class="patient-field-value">{{ $patient->clinic->name }}</span>
                                </div>
                            </div>
                        @endif
                        @if($patient->address)
                            <div class="col-12">
                                <div class="patient-field">
                                    <span class="patient-field-label">العنوان</span>
                                    <span class="patient-field-value text-wrap">{{ $patient->address }}</span>
                                </div>
                            </div>
                        @endif
                        @if($patient->medical_history)
                            <div class="col-12">
                                <div class="patient-field">
                                    <span class="patient-field-label">التاريخ الطبي</span>
                                    <div class="patient-field-value patient-field-multiline">{{ $patient->medical_history }}</div>
                                </div>
                            </div>
                        @endif
                        @if($patient->chronic_diseases)
                            <div class="col-12">
                                <div class="patient-field">
                                    <span class="patient-field-label">
                                        <i class="fas fa-heartbeat text-danger me-1"></i>الأمراض المزمنة
                                    </span>
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
                                            $raw = $patient->chronic_diseases;
                                            $patientDiseases = is_array($raw)
                                                ? $raw
                                                : (json_decode($raw, true) ?? []);
                                        @endphp
                                        @if(is_array($patientDiseases) && count($patientDiseases) > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($patientDiseases as $disease)
                                                    @if(isset($chronicDiseases[$disease]))
                                                        <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill">{{ $chronicDiseases[$disease] }}</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill">{{ $disease }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-body">{{ is_string($raw) ? $raw : '—' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- المواعيد --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt text-primary me-2"></i>المواعيد</h5>
                    @if(auth()->user()->hasPermission('create_appointments'))
                        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> موعد جديد
                        </a>
                    @endif
                </div>
                <div class="card-body p-0 p-md-4">
                    @if($patient->appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>الطبيب</th>
                                        <th>الحالة</th>
                                        <th class="text-center">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patient->appointments as $appointment)
                                        <tr>
                                            <td>
                                                <span class="text-nowrap d-inline-block">{{ $appointment->appointment_date->format('Y-m-d') }}</span>
                                                <small class="text-muted d-block">{{ $appointment->appointment_date->format('H:i') }}</small>
                                            </td>
                                            <td>{{ $appointment->doctor->name }}</td>
                                            <td>
                                                @if($appointment->status === 'pending')
                                                    <span class="badge bg-warning text-dark">معلق</span>
                                                @elseif($appointment->status === 'confirmed')
                                                    <span class="badge bg-info">مؤكد</span>
                                                @elseif($appointment->status === 'completed')
                                                    <span class="badge bg-success">مكتمل</span>
                                                @elseif($appointment->status === 'canceled')
                                                    <span class="badge bg-danger">ملغي</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i><span class="d-none d-sm-inline ms-1">عرض</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5 px-3">
                            <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
                            <p class="mb-3">لا توجد مواعيد مسجلة لهذا المريض بعد.</p>
                            @if(auth()->user()->hasPermission('create_appointments'))
                                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>إضافة أول موعد
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-pie text-primary me-2"></i>ملخص</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 patient-stats-row">
                        <div class="col-4">
                            <div class="patient-stat-chip">
                                <span class="patient-stat-num">{{ $patient->appointments->count() }}</span>
                                <span class="patient-stat-lbl">مواعيد</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="patient-stat-chip">
                                <span class="patient-stat-num">{{ $patient->prescriptions->count() }}</span>
                                <span class="patient-stat-lbl">وصفات</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="patient-stat-chip">
                                <span class="patient-stat-num">{{ $patient->invoices->count() }}</span>
                                <span class="patient-stat-lbl">فواتير</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm patient-quick-actions-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i> إجراءات سريعة
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-column gap-3">
                        @if(auth()->user()->hasPermission('create_appointments'))
                            <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="quick-action-card">
                                <div class="quick-action-icon bg-success">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6 class="mb-1">إضافة موعد جديد</h6>
                                    <small class="text-muted">حجز موعد لهذا المريض</small>
                                </div>
                                <div class="quick-action-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('create_invoices'))
                            <a href="{{ route('invoices.create', ['patient_id' => $patient->id]) }}" class="quick-action-card">
                                <div class="quick-action-icon bg-warning text-dark">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6 class="mb-1">إنشاء فاتورة</h6>
                                    <small class="text-muted">فاتورة جديدة للمريض</small>
                                </div>
                                <div class="quick-action-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('create_payments') && $patient->invoices->where('status', 'unpaid')->count() > 0)
                            <a href="{{ route('payments.create', ['patient_id' => $patient->id]) }}" class="quick-action-card">
                                <div class="quick-action-icon bg-light text-primary border">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6 class="mb-1">تسجيل دفعة</h6>
                                    <small class="text-muted">لديه فواتير غير مدفوعة</small>
                                </div>
                                <div class="quick-action-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('edit_patients'))
                            <a href="{{ route('patients.edit', $patient->id) }}" class="quick-action-card outline">
                                <div class="quick-action-icon bg-light text-warning border-warning">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6 class="mb-1">تعديل بيانات المريض</h6>
                                    <small class="text-muted">تحديث الملف</small>
                                </div>
                                <div class="quick-action-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('view_appointments'))
                            <a href="{{ route('appointments.index', ['patient_id' => $patient->id]) }}" class="quick-action-card outline">
                                <div class="quick-action-icon bg-light text-info border-info">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6 class="mb-1">كل المواعيد</h6>
                                    <small class="text-muted">في قائمة المواعيد</small>
                                </div>
                                <div class="quick-action-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('view_invoices'))
                            <a href="{{ route('invoices.index', ['patient_id' => $patient->id]) }}" class="quick-action-card outline">
                                <div class="quick-action-icon bg-light text-success border-success">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6 class="mb-1">كل الفواتير</h6>
                                    <small class="text-muted">في قائمة الفواتير</small>
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
</div>

@push('styles')
<style>
    .patient-avatar-xl {
        width: 72px;
        height: 72px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        font-size: 1.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(13, 148, 136, 0.35);
    }

    .patient-field {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        height: 100%;
    }

    .patient-field-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.35rem;
    }

    .patient-field-value {
        display: block;
        font-weight: 600;
        color: var(--text-color);
        word-break: break-word;
    }

    .patient-field-multiline {
        white-space: pre-wrap;
        font-weight: 500;
        line-height: 1.55;
    }

    .patient-stat-chip {
        background: linear-gradient(180deg, #f0fdfa 0%, #fff 100%);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.65rem 0.35rem;
        text-align: center;
    }

    .patient-stat-num {
        display: block;
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1.2;
    }

    .patient-stat-lbl {
        font-size: 0.7rem;
        color: #64748b;
        font-weight: 600;
    }

    .quick-action-card {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
        min-height: 88px;
    }

    .quick-action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(13, 148, 136, 0.12);
        border-color: var(--primary-color);
    }

    .quick-action-card.outline:hover {
        background: #f8fafc;
    }

    .quick-action-icon {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: white;
        flex-shrink: 0;
        margin-left: 0.85rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .quick-action-card.outline .quick-action-icon {
        border: 2px solid;
        background: white !important;
    }

    .quick-action-content {
        flex: 1;
        min-width: 0;
    }

    .quick-action-content h6 {
        margin: 0;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-color);
    }

    .quick-action-content small {
        display: block;
        font-size: 0.78rem;
        margin-top: 0.2rem;
    }

    .quick-action-arrow {
        color: var(--primary-color);
        font-size: 1.1rem;
        opacity: 0.35;
        flex-shrink: 0;
        margin-right: 0.25rem;
        transition: opacity 0.2s;
    }

    .quick-action-card:hover .quick-action-arrow {
        opacity: 1;
    }

    @media (max-width: 767.98px) {
        .patient-profile-summary .card-body {
            padding: 1.25rem !important;
        }

        .patient-avatar-xl {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
        }

        .patient-stats-row .col-4 {
            min-width: 0;
        }

        .patient-stat-num {
            font-size: 1.15rem;
        }

        .patient-stat-lbl {
            font-size: 0.65rem;
        }

        .quick-action-card {
            padding: 0.85rem;
            min-height: 80px;
        }

        .quick-action-icon {
            width: 46px;
            height: 46px;
            font-size: 1.2rem;
            margin-left: 0.65rem;
        }
    }

    @media (max-width: 575.98px) {
        .patient-show-page .table-responsive {
            border-radius: 0;
        }
    }
</style>
@endpush
@endsection
