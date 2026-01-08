@extends('layouts.app')

@section('title', 'تفاصيل المستخدم')
@section('page-title', 'تفاصيل المستخدم')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                     style="width: 100px; height: 100px; font-size: 2.5rem;">
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
                <span class="badge bg-{{ $roleInfo['color'] }} fs-6 mb-2">
                    <i class="fas {{ $roleInfo['icon'] }} me-1"></i>
                    {{ $roleInfo['name'] }}
                </span>
                @if($user->role === 'doctor' && $user->specialization)
                <p class="mt-2 mb-0">
                    <i class="fas fa-stethoscope me-2 text-primary"></i>
                    <strong>{{ $user->specialization }}</strong>
                </p>
                @endif
                <div class="mt-3">
                    @if($user->is_active)
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

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2"></i> الإجراءات
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> تعديل البيانات
                    </a>
                    <a href="{{ route('admin.users.permissions', $user->id) }}" class="btn btn-info">
                        <i class="fas fa-key me-2"></i> إدارة الصلاحيات
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i> العودة للقائمة
                    </a>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i> حذف المستخدم
                        </button>
                    </form>
                    @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-info-circle me-2"></i> 
                        لا يمكنك حذف حسابك الخاص.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i> معلومات المستخدم
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <label class="text-muted small d-block mb-1">الاسم الكامل</label>
                            <strong class="fs-6">{{ $user->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <label class="text-muted small d-block mb-1">البريد الإلكتروني</label>
                            <strong class="fs-6">
                                <i class="fas fa-envelope text-muted me-2"></i>
                                {{ $user->email }}
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <label class="text-muted small d-block mb-1">الدور</label>
                            <div>
                                <span class="badge bg-{{ $roleInfo['color'] }} fs-6">
                                    <i class="fas {{ $roleInfo['icon'] }} me-1"></i>
                                    {{ $roleInfo['name'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <label class="text-muted small d-block mb-1">الحالة</label>
                            <div>
                                @if($user->is_active)
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
                    @if($user->role === 'doctor' && $user->specialization)
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <label class="text-muted small d-block mb-1">التخصص</label>
                            <strong class="fs-6">
                                <i class="fas fa-stethoscope text-primary me-2"></i>
                                {{ $user->specialization }}
                            </strong>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <label class="text-muted small d-block mb-1">تاريخ الإنشاء</label>
                            <strong class="fs-6">
                                <i class="fas fa-calendar text-muted me-2"></i>
                                {{ $user->created_at->format('Y-m-d H:i') }}
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pb-3">
                            <label class="text-muted small d-block mb-1">آخر تحديث</label>
                            <strong class="fs-6">
                                <i class="fas fa-clock text-muted me-2"></i>
                                {{ $user->updated_at->format('Y-m-d H:i') }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($user->role === 'doctor')
        <!-- Doctor Statistics -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i> إحصائيات الطبيب
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fs-4 fw-bold text-primary mb-1">
                                {{ $user->doctorAppointments()->count() }}
                            </div>
                            <small class="text-muted">إجمالي المواعيد</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fs-4 fw-bold text-success mb-1">
                                {{ $user->doctorAppointments()->where('status', 'completed')->count() }}
                            </div>
                            <small class="text-muted">المواعيد المكتملة</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fs-4 fw-bold text-info mb-1">
                                {{ $user->prescriptions()->count() }}
                            </div>
                            <small class="text-muted">الوصفات الطبية</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('doctors.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-2"></i> عرض الملف الطبي الكامل
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

