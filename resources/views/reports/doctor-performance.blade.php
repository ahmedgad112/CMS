@extends('layouts.app')

@section('title', 'أداء الأطباء')
@section('page-title', 'أداء الأطباء')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">تقرير أداء الأطباء</h5>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('reports.doctor-performance') }}" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="doctor_id" class="form-label">اختر الطبيب</label>
                    <select class="form-select" id="doctor_id" name="doctor_id">
                        <option value="">جميع الأطباء</option>
                        @foreach(\App\Models\User::where('role', 'doctor')->get() as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                        <a href="{{ route('reports.doctor-performance') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> إعادة تعيين
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Doctors Performance Table -->
        @if($doctors->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الطبيب</th>
                        <th>البريد الإلكتروني</th>
                        <th>عدد الزيارات</th>
                        <th>عدد الوصفات الطبية</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $doctor->name }}</td>
                        <td>{{ $doctor->email }}</td>
                        <td>
                            <span class="badge bg-info">{{ $doctor->doctorAppointments()->where('status', 'completed')->count() }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $doctor->prescriptions_count }}</span>
                        </td>
                        <td>
                            @if($doctor->is_active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-danger">غير نشط</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> لا يوجد أطباء مسجلون حالياً.
        </div>
        @endif
    </div>
</div>
@endsection

