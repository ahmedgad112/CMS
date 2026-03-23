@extends('layouts.app')

@section('title', 'الأطباء')
@section('page-title', 'قائمة الأطباء')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-md me-2"></i> قائمة الأطباء
            </h5>
        </div>
        <div class="card-body">
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('doctors.index') }}" class="mb-4">
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-12">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-search text-primary me-1"></i> البحث بالاسم
                                </label>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="ابحث بالاسم..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2 col-12">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-building text-secondary me-1"></i> القسم
                                </label>
                                <select name="department_id" class="form-select" id="filter_department_id">
                                    <option value="">جميع الأقسام</option>
                                    @if(isset($departments) && $departments->count() > 0)
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}"
                                                {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-stethoscope text-info me-1"></i> التخصص
                                </label>
                                <select name="specialization_id" class="form-select" id="filter_specialization_id">
                                    <option value="">جميع التخصصات</option>
                                    @if (request('department_id'))
                                        @foreach (\App\Models\Specialization::where('department_id', request('department_id'))->get() as $spec)
                                            <option value="{{ $spec->id }}"
                                                {{ request('specialization_id') == $spec->id ? 'selected' : '' }}>
                                                {{ $spec->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-info-circle text-warning me-1"></i> الحالة
                                </label>
                                <select name="is_active" class="form-select">
                                    <option value="">جميع الحالات</option>
                                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label fw-semibold mb-2 d-block">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search me-2"></i> بحث
                                    </button>
                                    @if(request()->hasAny(['search', 'department_id', 'specialization_id', 'is_active']))
                                    <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if(request()->hasAny(['search', 'department_id', 'specialization_id', 'is_active']))
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <small class="text-muted fw-semibold">
                                    <i class="fas fa-filter me-1"></i> الفلاتر النشطة:
                                </small>
                                @if(request('search'))
                                <span class="badge bg-primary">
                                    <i class="fas fa-search me-1"></i> {{ request('search') }}
                                    <a href="{{ route('doctors.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                                @endif
                                @if(request('department_id'))
                                <span class="badge bg-secondary">
                                    <i class="fas fa-building me-1"></i> {{ $departments->where('id', request('department_id'))->first()->name ?? '' }}
                                    <a href="{{ route('doctors.index', array_merge(request()->except('department_id'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                                @endif
                                @if(request('specialization_id'))
                                <span class="badge bg-info">
                                    <i class="fas fa-stethoscope me-1"></i> {{ \App\Models\Specialization::find(request('specialization_id'))->name ?? '' }}
                                    <a href="{{ route('doctors.index', array_merge(request()->except('specialization_id'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                                @endif
                                @if(request('is_active') !== null && request('is_active') !== '')
                                <span class="badge bg-warning">
                                    <i class="fas fa-info-circle me-1"></i> {{ request('is_active') == '1' ? 'نشط' : 'غير نشط' }}
                                    <a href="{{ route('doctors.index', array_merge(request()->except('is_active'), ['page' => 1])) }}" class="text-white ms-2" style="text-decoration: none;">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                                @endif
                                <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-redo me-1"></i> إعادة تعيين الكل
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Doctors Grid -->
        @if ($doctors->count() > 0)
            <div class="row g-4">
                @foreach ($doctors as $doctor)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0" style="transition: transform 0.2s;">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px; font-size: 1.5rem;">
                                            <i class="fas fa-user-md"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">
                                            <a href="{{ route('doctors.show', $doctor->id) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $doctor->name }}
                                            </a>
                                        </h5>
                                        @php
                                            $specializationName = null;
                                            // التحقق من التخصص من العلاقة أولاً
                                            if ($doctor->specialization_id) {
                                                if (
                                                    $doctor->relationLoaded('specialization') &&
                                                    $doctor->specialization &&
                                                    is_object($doctor->specialization)
                                                ) {
                                                    $specializationName = $doctor->specialization->name;
                                                }
                                            }
                                            // إذا لم يكن هناك تخصص من العلاقة، جرب الحقل النصي القديم
                                            if (
                                                !$specializationName &&
                                                !empty($doctor->specialization) &&
                                                is_string($doctor->specialization)
                                            ) {
                                                $specializationName = $doctor->specialization;
                                            }
                                        @endphp

                                        @if ($specializationName)
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-stethoscope me-1 text-info"></i>
                                                <strong>{{ $specializationName }}</strong>
                                            </p>
                                        @else
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <em>لا يوجد تخصص محدد</em>
                                            </p>
                                        @endif
                                        @if ($doctor->department && is_object($doctor->department))
                                            <p class="text-muted mb-2 small">
                                                <i class="fas fa-building me-1 text-primary"></i>
                                                {{ $doctor->department->name }}
                                            </p>
                                        @endif
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-envelope me-1"></i> {{ $doctor->email }}
                                        </p>
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <div class="fw-bold text-primary">{{ $doctor->doctor_appointments_count }}
                                            </div>
                                            <small class="text-muted">مواعيد</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <div class="fw-bold text-success">
                                                {{ $doctor->doctorAppointments()->where('status', 'completed')->count() }}
                                            </div>
                                            <small class="text-muted">مكتملة</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <div class="fw-bold text-info">{{ $doctor->prescriptions_count }}</div>
                                            <small class="text-muted">وصفات</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    @if ($doctor->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i> نشط
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times-circle me-1"></i> غير نشط
                                        </span>
                                    @endif
                                    <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $doctors->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> لا يوجد أطباء مسجلين حالياً.
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
