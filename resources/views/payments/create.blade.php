@extends('layouts.app')

@section('title', 'تسجيل دفعة')
@section('page-title', 'تسجيل دفعة جديدة')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">تسجيل دفعة جديدة</h5>
        @if(request('invoice_id'))
            <a href="{{ route('invoices.show', request('invoice_id')) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-right me-2"></i> العودة للفاتورة
            </a>
        @else
            <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-right me-2"></i> العودة للفواتير
            </a>
        @endif
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('payments.store') }}" id="paymentForm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="invoice_id" class="form-label">الفاتورة <span class="text-danger">*</span></label>
                    <select class="form-select @error('invoice_id') is-invalid @enderror" 
                            id="invoice_id" 
                            name="invoice_id" 
                            required>
                        <option value="">اختر الفاتورة</option>
                        @foreach($invoices as $inv)
                            <option value="{{ $inv->id }}" 
                                    {{ old('invoice_id', request('invoice_id')) == $inv->id ? 'selected' : '' }}
                                    data-total="{{ $inv->total_amount }}"
                                    data-paid="{{ $inv->paid_amount }}"
                                    data-remaining="{{ $inv->remaining_amount }}">
                                {{ $inv->invoice_number }} - {{ $inv->patient->full_name }} 
                                (المتبقي: {{ number_format($inv->remaining_amount, 2) }} ج.م)
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" 
                               step="0.01" 
                               min="0.01"
                               class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" 
                               name="amount" 
                               value="{{ old('amount') }}" 
                               required
                               oninput="calculateFinalAmount()">
                        <span class="input-group-text">ج.م</span>
                    </div>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted" id="remaining_amount_info"></small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="payment_method" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                            id="payment_method" 
                            name="payment_method" 
                            required>
                        <option value="">اختر طريقة الدفع</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" 
                              name="notes" 
                              rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ request('invoice_id') ? route('invoices.show', request('invoice_id')) : route('payments.index') }}" 
                   class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> تسجيل الدفعة
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function updateRemainingAmountInfo() {
        const invoiceSelect = document.getElementById('invoice_id');
        const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const remaining = parseFloat(selectedOption.getAttribute('data-remaining')) || 0;
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            
            const infoElement = document.getElementById('remaining_amount_info');
            if (amount > remaining) {
                infoElement.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> المبلغ (' + amount.toFixed(2) + ' ج.م) يتجاوز المبلغ المتبقي (' + remaining.toFixed(2) + ' ج.م)</span>';
            } else {
                infoElement.innerHTML = '<span class="text-muted">المبلغ المتبقي: ' + remaining.toFixed(2) + ' ج.م</span>';
            }
        }
    }
    
    document.getElementById('invoice_id').addEventListener('change', function() {
        updateRemainingAmountInfo();
    });
    
    document.getElementById('amount').addEventListener('input', function() {
        updateRemainingAmountInfo();
    });
    
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const invoiceSelect = document.getElementById('invoice_id');
        const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        
        if (selectedOption && selectedOption.value) {
            const remaining = parseFloat(selectedOption.getAttribute('data-remaining')) || 0;
            
            if (amount > remaining) {
                e.preventDefault();
                alert('المبلغ لا يمكن أن يتجاوز المبلغ المتبقي (' + remaining.toFixed(2) + ' ج.م)');
                return false;
            }
        }
    });
    
    // Initialize
    updateRemainingAmountInfo();
</script>
@endpush
@endsection

