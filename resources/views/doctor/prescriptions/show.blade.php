@extends('layouts.app')

@section('title', 'تفاصيل الوصفة الطبية')
@section('page-title', 'تفاصيل الوصفة الطبية')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Prescription Header -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-prescription me-2"></i> معلومات الوصفة الطبية
                </h5>
                <a href="{{ route('doctor.prescriptions.print', $prescription->id) }}" 
                   class="btn btn-primary" 
                   target="_blank">
                    <i class="fas fa-print me-2"></i> طباعة
                </a>
            </div>
            <div class="card-body">
                <div class="row g-4">
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
                                    <a href="{{ route('doctors.show', $prescription->doctor_id) }}" 
                                       class="text-decoration-none fw-bold">
                                        {{ $prescription->doctor->name }}
                                    </a>
                                    @if($prescription->doctor->specialization)
                                    <div class="small text-muted">
                                        <i class="fas fa-stethoscope me-1"></i>
                                        {{ $prescription->doctor->specialization }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Date -->
                    @if($prescription->appointment)
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-calendar-check me-1"></i> تاريخ الموعد
                            </label>
                            <strong>{{ $prescription->appointment->appointment_date->format('Y-m-d H:i') }}</strong>
                        </div>
                    </div>
                    @endif

                    <!-- Prescription Date -->
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-calendar me-1"></i> تاريخ الوصفة
                            </label>
                            <strong>{{ $prescription->created_at->format('Y-m-d H:i') }}</strong>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($prescription->notes)
                    <div class="col-12">
                        <div class="bg-light rounded p-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-sticky-note me-1"></i> ملاحظات
                            </label>
                            <p class="mb-0">{{ $prescription->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Medicines List -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-pills me-2"></i> الأدوية الموصوفة
                    <span class="badge bg-light text-primary ms-2">{{ $prescription->items->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                @if($prescription->items->count() > 0)
                <div class="table-responsive">
                    <table class="table medicines-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th>
                                    <i class="fas fa-capsules text-primary me-2"></i>
                                    اسم الدواء
                                </th>
                                <th>
                                    <i class="fas fa-syringe text-info me-2"></i>
                                    الجرعة
                                </th>
                                <th>
                                    <i class="fas fa-redo text-warning me-2"></i>
                                    التكرار
                                </th>
                                <th>
                                    <i class="fas fa-clock text-success me-2"></i>
                                    المدة
                                </th>
                                <th>
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    تعليمات
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->items as $index => $item)
                            <tr class="medicine-row">
                                <td class="text-center">
                                    <span class="medicine-number-badge {{ $index == 0 ? 'medicine-number-black' : '' }}">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="medicine-name-cell">
                                        <i class="fas fa-capsules text-primary me-2"></i>
                                        <strong>{{ $item->medicine_name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge dosage-badge">
                                        <i class="fas fa-syringe me-1"></i>
                                        {{ $item->dosage }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge frequency-badge">
                                        <i class="fas fa-redo me-1"></i>
                                        {{ $item->frequency }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge duration-badge">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $item->duration }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->instructions)
                                        <div class="instructions-cell">
                                            <i class="fas fa-info-circle text-primary me-1"></i>
                                            <span>{{ $item->instructions }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center m-4">
                    <i class="fas fa-info-circle me-2"></i>
                    لا توجد أدوية في هذه الوصفة
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i> إجراءات سريعة
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('doctor.prescriptions.print', $prescription->id) }}" 
                       class="btn btn-primary" 
                       target="_blank">
                        <i class="fas fa-print me-2"></i> طباعة الوصفة
                    </a>
                    @if($prescription->appointment)
                    <a href="{{ route('appointments.show', $prescription->appointment_id) }}" class="btn btn-info">
                        <i class="fas fa-calendar-check me-2"></i> عرض الموعد
                    </a>
                    @endif
                    <a href="{{ route('patients.show', $prescription->patient_id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-user me-2"></i> عرض ملف المريض
                    </a>
                    <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i> العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Appointment Summary -->
        @if($prescription->appointment)
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i> معلومات الموعد
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small d-block mb-1">تاريخ الموعد</label>
                    <strong>
                        <i class="fas fa-calendar text-primary me-1"></i>
                        {{ $prescription->appointment->appointment_date->format('Y-m-d H:i') }}
                    </strong>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block mb-1">نوع الموعد</label>
                    <span class="badge bg-{{ $prescription->appointment->appointment_type == 'checkup' ? 'primary' : 'info' }}">
                        {{ $prescription->appointment->appointment_type == 'checkup' ? 'كشف' : 'استشارة' }}
                    </span>
                </div>
                @if($prescription->appointment->notes)
                <div class="mb-3">
                    <label class="text-muted small d-block mb-1">ملاحظات</label>
                    <p class="mb-0 small">{{ \Illuminate\Support\Str::limit($prescription->appointment->notes, 100) }}</p>
                </div>
                @endif
                <a href="{{ route('appointments.show', $prescription->appointment_id) }}" class="btn btn-sm btn-outline-info w-100">
                    <i class="fas fa-eye me-1"></i> عرض تفاصيل الموعد
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .medicines-table {
        margin-bottom: 0;
    }

    .medicines-table thead th {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
        font-weight: 600;
        color: var(--text-color);
        text-align: right;
        vertical-align: middle;
    }

    .medicines-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .medicines-table tbody tr:hover {
        background-color: #f8fafc;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .medicines-table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }

    .medicine-number-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9375rem;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
    }

    .medicine-number-badge.medicine-number-black {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    .medicine-name-cell {
        display: flex;
        align-items: center;
        font-size: 1rem;
    }

    .medicine-name-cell strong {
        color: var(--text-color);
        font-weight: 600;
    }

    .dosage-badge {
        background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
        color: #164e63;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        box-shadow: 0 2px 4px rgba(6, 182, 212, 0.2);
    }

    .frequency-badge {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
    }

    .duration-badge {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }

    .instructions-cell {
        display: flex;
        align-items: center;
        color: #64748b;
        font-size: 0.875rem;
        max-width: 300px;
    }

    .instructions-cell span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Striped rows for better readability */
    .medicines-table tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }

    .medicines-table tbody tr:nth-child(even):hover {
        background-color: #f1f5f9;
    }

    @media (max-width: 991.98px) {
        .medicines-table {
            font-size: 0.875rem;
        }

        .medicines-table thead th,
        .medicines-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .medicine-number-badge {
            width: 30px;
            height: 30px;
            font-size: 0.8125rem;
        }

        .dosage-badge,
        .frequency-badge,
        .duration-badge {
            padding: 0.375rem 0.625rem;
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 767.98px) {
        .medicines-table {
            font-size: 0.8125rem;
        }

        .medicines-table thead th {
            font-size: 0.75rem;
            padding: 0.625rem 0.375rem;
        }

        .medicines-table thead th i {
            display: none;
        }

        .medicines-table tbody td {
            padding: 0.625rem 0.375rem;
        }

        .medicine-name-cell {
            font-size: 0.875rem;
        }

        .medicine-name-cell i {
            display: none;
        }

        .instructions-cell {
            max-width: 150px;
        }

        .instructions-cell i {
            display: none;
        }
    }
</style>
@endpush
@endsection

