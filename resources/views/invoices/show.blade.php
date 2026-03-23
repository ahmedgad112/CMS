@extends('layouts.app')

@section('title', 'تفاصيل الفاتورة')
@section('page-title', 'تفاصيل الفاتورة')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">معلومات الفاتورة</h5>
                <div>
                    @if(auth()->user()->isAccountant() || auth()->user()->isAdmin() || auth()->user()->isReceptionist())
                    @if($invoice->status == 'unpaid')
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    @endif
                    @endif
                    <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-sm btn-primary" target="_blank">
                        <i class="fas fa-print"></i> طباعة
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>رقم الفاتورة:</strong>
                        <p>{{ $invoice->invoice_number }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>المريض:</strong>
                        <p>
                            <a href="{{ route('patients.show', $invoice->patient_id) }}" class="text-decoration-none">
                                {{ $invoice->patient->full_name }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>رسوم الكشف:</strong>
                        <p>{{ number_format($invoice->consultation_fee, 2) }} ج.م</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>المبلغ الإجمالي:</strong>
                        <p class="h5 text-primary">{{ number_format($invoice->total_amount, 2) }} ج.م</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>الحالة:</strong>
                        <p>
                            @if($invoice->status == 'paid')
                                <span class="badge bg-success">مدفوعة</span>
                            @else
                                <span class="badge bg-warning">غير مدفوعة</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>المدفوع:</strong>
                        <p class="text-success">{{ number_format($invoice->paid_amount, 2) }} ج.م</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>المتبقي:</strong>
                        <p class="text-danger">{{ number_format($invoice->remaining_amount, 2) }} ج.م</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاريخ الإنشاء:</strong>
                        <p>{{ $invoice->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($invoice->payments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">المدفوعات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>المبلغ</th>
                                <th>طريقة الدفع</th>
                                <th>تاريخ الدفع</th>
                                <th>المستلم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{ number_format($payment->amount, 2) }} ج.م</td>
                                <td>
                                    @if($payment->payment_method == 'cash')
                                        <span class="badge bg-success">نقدي</span>
                                    @else
                                        <span class="badge bg-info">بطاقة ائتمان</span>
                                    @endif
                                </td>
                                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $payment->receiver->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                @if(auth()->user()->isAccountant() || auth()->user()->isAdmin())
                @if($invoice->status == 'unpaid')
                <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-success w-100 mb-2">
                    <i class="fas fa-money-bill-wave"></i> تسجيل دفعة
                </a>
                @endif
                @endif
                <a href="{{ route('patients.show', $invoice->patient_id) }}" class="btn btn-info w-100 mb-2">
                    <i class="fas fa-user"></i> عرض بيانات المريض
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

