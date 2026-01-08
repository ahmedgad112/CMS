@extends('layouts.app')

@section('title', 'تفاصيل الدفعة')
@section('page-title', 'تفاصيل الدفعة')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">معلومات الدفعة</h5>
                <div>
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i> قائمة المدفوعات
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>رقم الدفعة:</strong>
                        <p>#{{ $payment->id }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>الفاتورة:</strong>
                        <p>
                            <a href="{{ route('invoices.show', $payment->invoice_id) }}" class="text-decoration-none">
                                {{ $payment->invoice->invoice_number }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>المريض:</strong>
                        <p>
                            <a href="{{ route('patients.show', $payment->invoice->patient_id) }}" class="text-decoration-none">
                                {{ $payment->invoice->patient->full_name }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>المبلغ:</strong>
                        <p class="h5 text-primary">{{ number_format($payment->amount, 2) }} ج.م</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>طريقة الدفع:</strong>
                        <p>
                            @if($payment->payment_method == 'cash')
                                <span class="badge bg-success">نقدي</span>
                            @else
                                <span class="badge bg-info">بطاقة ائتمان</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>المستلم:</strong>
                        <p>{{ $payment->receiver->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاريخ الدفع:</strong>
                        <p>{{ $payment->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @if($payment->notes)
                    <div class="col-md-12 mb-3">
                        <strong>ملاحظات:</strong>
                        <p>{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('invoices.show', $payment->invoice_id) }}" class="btn btn-info w-100 mb-2">
                    <i class="fas fa-file-invoice"></i> عرض الفاتورة
                </a>
                <a href="{{ route('patients.show', $payment->invoice->patient_id) }}" class="btn btn-secondary w-100 mb-2">
                    <i class="fas fa-user"></i> عرض بيانات المريض
                </a>
                <a href="{{ route('payments.index') }}" class="btn btn-primary w-100">
                    <i class="fas fa-list"></i> قائمة المدفوعات
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

