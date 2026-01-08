@extends('layouts.app')

@section('title', 'تعديل فاتورة')
@section('page-title', 'تعديل فاتورة')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">تعديل بيانات الفاتورة</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('invoices.update', $invoice->id) }}" id="invoiceForm">
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
                            <option value="{{ $patient->id }}" {{ old('patient_id', $invoice->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }} - {{ $patient->phone_number }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="appointment_id" class="form-label">الموعد (اختياري)</label>
                    <select class="form-select @error('appointment_id') is-invalid @enderror" 
                            id="appointment_id" 
                            name="appointment_id">
                        <option value="">اختر الموعد</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ old('appointment_id', $invoice->appointment_id) == $appointment->id ? 'selected' : '' }}>
                                {{ $appointment->patient->full_name }} - {{ $appointment->appointment_date->format('Y-m-d H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="consultation_fee" class="form-label">رسوم الكشف <span class="text-danger">*</span></label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           class="form-control @error('consultation_fee') is-invalid @enderror" 
                           id="consultation_fee" 
                           name="consultation_fee" 
                           value="{{ old('consultation_fee', $invoice->consultation_fee) }}" 
                           required
                           oninput="calculateTotal()">
                    @error('consultation_fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="total_amount" class="form-label">المبلغ الإجمالي</label>
                    <input type="number" 
                           step="0.01" 
                           class="form-control" 
                           id="total_amount" 
                           name="total_amount" 
                           readonly
                           value="{{ old('total_amount', $invoice->total_amount) }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function calculateTotal() {
        const consultationFee = parseFloat(document.getElementById('consultation_fee').value) || 0;
        document.getElementById('total_amount').value = consultationFee.toFixed(2);
    }

    // Calculate on page load
    calculateTotal();
</script>
@endpush
@endsection

