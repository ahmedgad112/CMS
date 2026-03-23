@extends('layouts.app')

@section('title', 'الملف الشخصي')
@section('page-title', 'الملف الشخصي')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                     style="width: 120px; height: 120px; font-size: 3rem;">
                    <i class="fas fa-user"></i>
                </div>
                <h4 class="mb-2">{{ $user->name }}</h4>
                <p class="text-muted mb-3">
                    <i class="fas fa-envelope me-2"></i>
                    {{ $user->email }}
                </p>
                @php
                    $roleNames = [
                        'admin' => ['name' => 'مدير', 'icon' => 'fa-user-shield', 'color' => 'danger'],
                        'doctor' => ['name' => 'طبيب', 'icon' => 'fa-user-md', 'color' => 'primary'],
                        'receptionist' => ['name' => 'موظف استقبال', 'icon' => 'fa-user-tie', 'color' => 'info'],
                        'call_center' => ['name' => 'مركز اتصال', 'icon' => 'fa-headset', 'color' => 'warning'],
                        'accountant' => ['name' => 'محاسب', 'icon' => 'fa-calculator', 'color' => 'success'],
                        'storekeeper' => ['name' => 'مخزن', 'icon' => 'fa-warehouse', 'color' => 'secondary']
                    ];
                    $roleInfo = $roleNames[$user->role] ?? ['name' => $user->role, 'icon' => 'fa-user', 'color' => 'secondary'];
                @endphp
                <span class="badge bg-{{ $roleInfo['color'] }} fs-6 mb-3">
                    <i class="fas {{ $roleInfo['icon'] }} me-1"></i>
                    {{ $roleInfo['name'] }}
                </span>
                @if($user->role === 'doctor' && $user->specialization)
                <p class="mt-2 mb-0">
                    <i class="fas fa-stethoscope me-2 text-primary"></i>
                    <strong>{{ $user->specialization }}</strong>
                </p>
                @endif
                <div class="mt-4">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100">
                        <i class="fas fa-edit me-2"></i> تعديل الملف الشخصي
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        @if(!empty($stats))
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i> إحصائيات
                </h6>
            </div>
            <div class="card-body">
                @if($user->isDoctor())
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-calendar-check text-primary me-2"></i>
                                <span class="text-muted">المواعيد</span>
                            </div>
                            <strong class="fs-5">{{ $stats['appointments'] ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-calendar-day text-info me-2"></i>
                                <span class="text-muted">مواعيد اليوم</span>
                            </div>
                            <strong class="fs-5">{{ $stats['today_appointments'] ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-prescription text-success me-2"></i>
                                <span class="text-muted">الوصفات</span>
                            </div>
                            <strong class="fs-5">{{ $stats['prescriptions'] ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="text-muted">المواعيد المكتملة</span>
                            </div>
                            <strong class="fs-5">{{ $stats['completed_appointments'] ?? 0 }}</strong>
                        </div>
                    </div>
                @elseif($user->isReceptionist() || $user->isCallCenter())
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-users text-primary me-2"></i>
                                <span class="text-muted">المرضى</span>
                            </div>
                            <strong class="fs-5">{{ $stats['patients'] ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-calendar-check text-success me-2"></i>
                                <span class="text-muted">المواعيد</span>
                            </div>
                            <strong class="fs-5">{{ $stats['appointments'] ?? 0 }}</strong>
                        </div>
                    </div>
                @elseif($user->isAccountant())
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-invoice text-primary me-2"></i>
                                <span class="text-muted">الفواتير</span>
                            </div>
                            <strong class="fs-5">{{ $stats['invoices'] ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                <span class="text-muted">المدفوعات</span>
                            </div>
                            <strong class="fs-5">{{ $stats['payments'] ?? 0 }}</strong>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        <!-- Profile Information -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i> معلومات الملف الشخصي
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-user me-1"></i> الاسم الكامل
                            </label>
                            <strong class="fs-6">{{ $user->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-envelope me-1"></i> البريد الإلكتروني
                            </label>
                            <strong class="fs-6">{{ $user->email }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-user-tag me-1"></i> الدور
                            </label>
                            <span class="badge bg-{{ $roleInfo['color'] }} fs-6">
                                <i class="fas {{ $roleInfo['icon'] }} me-1"></i>
                                {{ $roleInfo['name'] }}
                            </span>
                        </div>
                    </div>
                    @if($user->role === 'doctor' && $user->specialization)
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-stethoscope me-1"></i> التخصص
                            </label>
                            <strong class="fs-6">{{ $user->specialization }}</strong>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="border-bottom pb-3">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-toggle-{{ $user->is_active ? 'on' : 'off' }} me-1"></i> الحالة
                            </label>
                            @if($user->is_active)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i> نشط
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i> غير نشط
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pb-2">
                            <label class="text-muted small d-block mb-2">
                                <i class="fas fa-calendar me-1"></i> تاريخ التسجيل
                            </label>
                            <strong class="fs-6">{{ $user->created_at->format('Y-m-d') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctor Schedules -->
        @if($user->isDoctor())
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i> جدول المواعيد
                </h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                    <i class="fas fa-plus me-2"></i> إضافة موعد
                </button>
            </div>
            <div class="card-body">
                @if($user->schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>اليوم</th>
                                <th>من</th>
                                <th>إلى</th>
                                <th width="120" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $dayNames = [
                                    'saturday' => 'السبت',
                                    'sunday' => 'الأحد',
                                    'monday' => 'الإثنين',
                                    'tuesday' => 'الثلاثاء',
                                    'wednesday' => 'الأربعاء',
                                    'thursday' => 'الخميس',
                                    'friday' => 'الجمعة'
                                ];
                            @endphp
                            @foreach($user->schedules as $schedule)
                            <tr>
                                <td>
                                    <strong>{{ $dayNames[$schedule->day_of_week] ?? $schedule->day_of_week }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $schedule->start_time->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $schedule->end_time->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" 
                                                class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editScheduleModal"
                                                data-schedule-id="{{ $schedule->id }}"
                                                data-day="{{ $schedule->day_of_week }}"
                                                data-start="{{ $schedule->start_time->format('H:i') }}"
                                                data-end="{{ $schedule->end_time->format('H:i') }}"
                                                title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('profile.schedules.destroy', $schedule->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الموعد؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    لا توجد مواعيد محددة. أضف مواعيدك للعمل.
                </div>
                @endif
            </div>
        </div>

        <!-- Add Schedule Modal -->
        <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addScheduleModalLabel">
                            <i class="fas fa-plus me-2"></i> إضافة موعد جديد
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('profile.schedules.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="day_of_week" class="form-label">اليوم <span class="text-danger">*</span></label>
                                <select name="day_of_week" id="day_of_week" class="form-select" required>
                                    <option value="">اختر اليوم</option>
                                    <option value="saturday">السبت</option>
                                    <option value="sunday">الأحد</option>
                                    <option value="monday">الإثنين</option>
                                    <option value="tuesday">الثلاثاء</option>
                                    <option value="wednesday">الأربعاء</option>
                                    <option value="thursday">الخميس</option>
                                    <option value="friday">الجمعة</option>
                                </select>
                                @error('day_of_week')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_time" class="form-label">من <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           name="start_time" 
                                           id="start_time" 
                                           class="form-control" 
                                           required>
                                    @error('start_time')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_time" class="form-label">إلى <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           name="end_time" 
                                           id="end_time" 
                                           class="form-control" 
                                           required>
                                    @error('end_time')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Schedule Modal -->
        <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="editScheduleModalLabel">
                            <i class="fas fa-edit me-2"></i> تعديل الموعد
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editScheduleForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_day_of_week" class="form-label">اليوم <span class="text-danger">*</span></label>
                                <select name="day_of_week" id="edit_day_of_week" class="form-select" required>
                                    <option value="">اختر اليوم</option>
                                    <option value="saturday">السبت</option>
                                    <option value="sunday">الأحد</option>
                                    <option value="monday">الإثنين</option>
                                    <option value="tuesday">الثلاثاء</option>
                                    <option value="wednesday">الأربعاء</option>
                                    <option value="thursday">الخميس</option>
                                    <option value="friday">الجمعة</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_start_time" class="form-label">من <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           name="start_time" 
                                           id="edit_start_time" 
                                           class="form-control" 
                                           required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_end_time" class="form-label">إلى <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           name="end_time" 
                                           id="edit_end_time" 
                                           class="form-control" 
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editModal = document.getElementById('editScheduleModal');
                if (editModal) {
                    editModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const scheduleId = button.getAttribute('data-schedule-id');
                        const day = button.getAttribute('data-day');
                        const start = button.getAttribute('data-start');
                        const end = button.getAttribute('data-end');

                        const form = document.getElementById('editScheduleForm');
                        form.action = `/profile/schedules/${scheduleId}`;

                        document.getElementById('edit_day_of_week').value = day;
                        document.getElementById('edit_start_time').value = start;
                        document.getElementById('edit_end_time').value = end;
                    });
                }
            });
        </script>
        @endpush
        @endif
    </div>
</div>
@endsection

