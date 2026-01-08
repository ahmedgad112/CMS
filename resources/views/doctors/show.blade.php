@extends('layouts.app')

@section('title', 'تفاصيل الطبيب')
@section('page-title', 'تفاصيل الطبيب')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 120px; height: 120px; font-size: 3rem;">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4 class="mb-2">{{ $doctor->name }}</h4>
                    @if ($doctor->specialization)
                        <p class="text-muted mb-3">
                            <i class="fas fa-stethoscope me-2"></i>
                            <strong>{{ $doctor->specialization }}</strong>
                        </p>
                    @else
                        <p class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <em>لا يوجد تخصص محدد</em>
                        </p>
                    @endif
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        {{ $doctor->email }}
                    </p>
                    <div class="mt-3">
                        @if ($doctor->is_active)
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i> نشط
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6">
                                <i class="fas fa-times-circle me-1"></i> غير نشط
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> الإحصائيات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">إجمالي المواعيد</span>
                            <span class="fw-bold text-primary">{{ $stats['total_appointments'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary"
                                style="width: {{ $stats['total_appointments'] > 0 ? ($stats['confirmed_appointments'] / $stats['total_appointments']) * 100 : 0 }}%">
                            </div>
                        </div>
                        <small class="text-muted">مواعيد مؤكدة: {{ $stats['confirmed_appointments'] }}</small>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">المواعيد المكتملة</span>
                            <span class="fw-bold text-success">{{ $stats['completed_appointments'] }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">الوصفات الطبية</span>
                            <span class="fw-bold text-info">{{ $stats['total_prescriptions'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Recent Appointments -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i> المواعيد الأخيرة
                    </h6>
                    <a href="{{ route('appointments.index', ['doctor_id' => $doctor->id]) }}"
                        class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $recentAppointments = $doctor->doctorAppointments()->with('patient')->latest()->limit(5)->get();
                    @endphp
                    @if ($recentAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>المريض</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentAppointments as $appointment)
                                        <tr>
                                            <td>
                                                <a href="{{ route('patients.show', $appointment->patient_id) }}"
                                                    class="text-decoration-none">
                                                    {{ $appointment->patient->full_name }}
                                                </a>
                                            </td>
                                            <td>{{ $appointment->appointment_date->format('Y-m-d H:i') }}</td>
                                            <td>
                                                @if ($appointment->status == 'confirmed')
                                                    <span class="badge bg-success">مؤكد</span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="badge bg-primary">مكتمل</span>
                                                @elseif($appointment->status == 'cancelled')
                                                    <span class="badge bg-danger">ملغي</span>
                                                @else
                                                    <span class="badge bg-warning">معلق</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">لا توجد مواعيد</p>
                    @endif
                </div>
            </div>

            <!-- Recent Prescriptions -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-prescription me-2"></i> الوصفات الأخيرة
                    </h6>
                    <a href="{{ route('doctor.prescriptions.index') }}"
                        class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $recentPrescriptions = $doctor->prescriptions()->with('patient')->latest()->limit(5)->get();
                    @endphp
                    @if ($recentPrescriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>المريض</th>
                                        <th>تاريخ الوصفة</th>
                                        <th>عدد الأدوية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentPrescriptions as $prescription)
                                        <tr>
                                            <td>
                                                <a href="{{ route('patients.show', $prescription->patient_id) }}"
                                                    class="text-decoration-none">
                                                    {{ $prescription->patient->full_name }}
                                                </a>
                                            </td>
                                            <td>{{ $prescription->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $prescription->items->count() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">لا توجد وصفات</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('doctors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i> العودة للقائمة
                        </a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.users.edit', $doctor->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i> تعديل البيانات
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
