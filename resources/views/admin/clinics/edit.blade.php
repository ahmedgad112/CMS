@extends('layouts.app')

@section('title', 'تعديل العيادة')
@section('page-title', 'تعديل العيادة')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-white">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon me-3">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">تعديل بيانات العيادة</h5>
                        <small class="opacity-75">{{ $clinic->name }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.clinics.update', $clinic->id) }}">
                    @csrf
                    @method('PUT')

                    @include('admin.clinics._form', ['clinic' => $clinic, 'doctors' => $doctors, 'assignedDoctorIds' => $assignedDoctorIds])

                    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.clinics.index') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-times me-2"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-2"></i> حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
