@extends('layouts.app')

@section('title', 'تعديل موعد')
@section('page-title', 'تعديل موعد')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">تعديل بيانات الموعد</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('appointments.update', $appointment->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="patient_id" class="form-label">المريض <span class="text-danger">*</span></label>
                    <select class="form-select @error('patient_id') is-invalid @enderror" 
                            id="patient_id" 
                            name="patient_id" 
                            required>
                        <option value="">اختر المريض</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }} - {{ $patient->phone_number }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="doctor_id" class="form-label">الطبيب <span class="text-danger">*</span></label>
                    <select class="form-select @error('doctor_id') is-invalid @enderror" 
                            id="doctor_id" 
                            name="doctor_id" 
                            required>
                        <option value="">اختر الطبيب</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="appointment_date" class="form-label">التاريخ والوقت <span class="text-danger">*</span></label>
                    <input type="datetime-local" 
                           class="form-control @error('appointment_date') is-invalid @enderror" 
                           id="appointment_date" 
                           name="appointment_date" 
                           value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d\TH:i')) }}" 
                           required>
                    @error('appointment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="appointment_type" class="form-label">نوع الموعد <span class="text-danger">*</span></label>
                    <select class="form-select @error('appointment_type') is-invalid @enderror" 
                            id="appointment_type" 
                            name="appointment_type" 
                            required>
                        <option value="">اختر نوع الموعد</option>
                        <option value="checkup" {{ old('appointment_type', $appointment->appointment_type ?? 'checkup') == 'checkup' ? 'selected' : '' }}>كشف</option>
                        <option value="consultation" {{ old('appointment_type', $appointment->appointment_type ?? 'checkup') == 'consultation' ? 'selected' : '' }}>استشارة</option>
                    </select>
                    @error('appointment_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="canceled" {{ old('status', $appointment->status) == 'canceled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" 
                              name="notes" 
                              rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

