@extends('layouts.app')

@section('title', 'المواعيد')
@section('page-title', 'إدارة المواعيد')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">قائمة المواعيد</h5>
        @if(auth()->user()->canManageAppointments())
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة موعد جديد
        </a>
        @endif
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('appointments.index') }}" class="mb-4">
            <div class="card bg-light border-0 mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-calendar text-primary me-1"></i> التاريخ
                            </label>
                            <input type="date" 
                                   name="date" 
                                   class="form-control" 
                                   value="{{ request('date') }}"
                                   placeholder="التاريخ">
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-building text-secondary me-1"></i> القسم
                            </label>
                            <select name="department_id" class="form-select" id="filter_department_id">
                                <option value="">جميع الأقسام</option>
                                @foreach($departments ?? [] as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-stethoscope text-info me-1"></i> التخصص
                            </label>
                            <select name="specialization_id" class="form-select" id="filter_specialization_id">
                                <option value="">جميع التخصصات</option>
                                @if(request('department_id'))
                                    @foreach(\App\Models\Specialization::where('department_id', request('department_id'))->get() as $spec)
                                        <option value="{{ $spec->id }}" {{ request('specialization_id') == $spec->id ? 'selected' : '' }}>
                                            {{ $spec->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-user-md text-info me-1"></i> الطبيب
                            </label>
                            <select name="doctor_id" class="form-select">
                                <option value="">جميع الأطباء</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-tag text-success me-1"></i> نوع الموعد
                            </label>
                            <select name="appointment_type" class="form-select">
                                <option value="">جميع الأنواع</option>
                                <option value="checkup" {{ request('appointment_type') == 'checkup' ? 'selected' : '' }}>كشف</option>
                                <option value="consultation" {{ request('appointment_type') == 'consultation' ? 'selected' : '' }}>استشارة</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-info-circle text-warning me-1"></i> الحالة
                            </label>
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fw-semibold mb-2 d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> بحث
                            </button>
                        </div>
                    </div>
                    
                    @if(request()->hasAny(['date', 'department_id', 'specialization_id', 'doctor_id', 'appointment_type', 'status']))
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <small class="text-muted fw-semibold">
                                <i class="fas fa-filter me-1"></i> الفلاتر النشطة:
                            </small>
                            @if(request('date'))
                            <span class="badge bg-primary">
                                <i class="fas fa-calendar me-1"></i> {{ request('date') }}
                                <a href="{{ route('appointments.index', array_merge(request()->except('date'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                            @if(request('department_id'))
                            <span class="badge bg-secondary">
                                <i class="fas fa-building me-1"></i> {{ $departments->where('id', request('department_id'))->first()->name ?? '' }}
                                <a href="{{ route('appointments.index', array_merge(request()->except('department_id'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                            @if(request('specialization_id'))
                            <span class="badge bg-info">
                                <i class="fas fa-stethoscope me-1"></i> {{ \App\Models\Specialization::find(request('specialization_id'))->name ?? '' }}
                                <a href="{{ route('appointments.index', array_merge(request()->except('specialization_id'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                            @if(request('doctor_id'))
                            <span class="badge bg-info">
                                <i class="fas fa-user-md me-1"></i> {{ $doctors->where('id', request('doctor_id'))->first()->name ?? '' }}
                                <a href="{{ route('appointments.index', array_merge(request()->except('doctor_id'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                            @if(request('appointment_type'))
                            <span class="badge bg-success">
                                <i class="fas fa-tag me-1"></i> {{ request('appointment_type') == 'checkup' ? 'كشف' : 'استشارة' }}
                                <a href="{{ route('appointments.index', array_merge(request()->except('appointment_type'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                            @if(request('status'))
                            <span class="badge bg-warning">
                                <i class="fas fa-info-circle me-1"></i> 
                                @if(request('status') == 'pending') معلق
                                @elseif(request('status') == 'confirmed') مؤكد
                                @elseif(request('status') == 'completed') مكتمل
                                @else ملغي
                                @endif
                                <a href="{{ route('appointments.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-redo me-1"></i> إعادة تعيين الكل
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>

        <!-- Appointments List: table on md+, cards on small screens -->
        @if($appointments->count() > 0)
        <x-responsive-list>
            <x-slot:table>
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المريض</th>
                            <th>الطبيب</th>
                            <th>التاريخ والوقت</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $loop->iteration + ($appointments->currentPage() - 1) * $appointments->perPage() }}</td>
                            <td>
                                <a href="{{ route('patients.show', $appointment->patient_id) }}" class="text-decoration-none">
                                    {{ $appointment->patient->full_name }}
                                </a>
                            </td>
                            <td>{{ $appointment->doctor->name }}</td>
                            <td>{{ $appointment->appointment_date->format('Y-m-d H:i') }}</td>
                            <td>
                                @if(($appointment->appointment_type ?? 'checkup') == 'checkup')
                                    <span class="badge bg-primary"><i class="fas fa-stethoscope me-1"></i>كشف</span>
                                @else
                                    <span class="badge bg-info"><i class="fas fa-comments me-1"></i>استشارة</span>
                                @endif
                            </td>
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    @if(auth()->user()->canManageAppointments())
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-warning" title="تعديل"><i class="fas fa-edit"></i></a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-slot:table>
            <x-slot:cards>
                @php
                    $statusLabels = ['pending' => 'معلق', 'confirmed' => 'مؤكد', 'completed' => 'مكتمل', 'canceled' => 'ملغي'];
                    $statusVariants = ['pending' => 'warning', 'confirmed' => 'info', 'completed' => 'success', 'canceled' => 'danger'];
                @endphp
                @foreach($appointments as $appointment)
                <x-list-card
                    :title="$appointment->patient->full_name"
                    :title-url="route('patients.show', $appointment->patient_id)"
                    :badge="$statusLabels[$appointment->status] ?? 'ملغي'"
                    :badge-variant="$statusVariants[$appointment->status] ?? 'danger'"
                >
                    <x-slot:fields>
                        <x-list-card-field label="الطبيب" icon="fas fa-user-md">{{ $appointment->doctor->name }}</x-list-card-field>
                        <x-list-card-field label="التاريخ والوقت" icon="fas fa-calendar-alt">{{ $appointment->appointment_date->format('Y-m-d H:i') }}</x-list-card-field>
                        <x-list-card-field label="النوع" icon="fas fa-tag">
                            {{ ($appointment->appointment_type ?? 'checkup') == 'checkup' ? 'كشف' : 'استشارة' }}
                        </x-list-card-field>
                    </x-slot:fields>
                    <x-slot:actions>
                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye me-1"></i>عرض</a>
                        @if(auth()->user()->canManageAppointments())
                        <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-warning" title="تعديل"><i class="fas fa-edit me-1"></i>تعديل</a>
                        @endif
                    </x-slot:actions>
                </x-list-card>
                @endforeach
            </x-slot:cards>
        </x-responsive-list>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $appointments->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> لا توجد مواعيد مسجلة حالياً.
            @if(auth()->user()->canManageAppointments())
            <a href="{{ route('appointments.create') }}" class="alert-link">إضافة موعد جديد</a>
            @endif
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('filter_department_id');
        const specializationSelect = document.getElementById('filter_specialization_id');

        if (departmentSelect) {
            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;
                specializationSelect.innerHTML = '<option value="">جميع التخصصات</option>';
                
                if (departmentId) {
                    fetch(`/api/departments/${departmentId}/specializations`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.specializations && data.specializations.length > 0) {
                                data.specializations.forEach(spec => {
                                    const option = document.createElement('option');
                                    option.value = spec.id;
                                    option.textContent = spec.name;
                                    specializationSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        }
    });
</script>
@endpush
@endsection

