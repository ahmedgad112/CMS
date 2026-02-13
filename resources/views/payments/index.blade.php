@extends('layouts.app')

@section('title', 'المدفوعات')
@section('page-title', 'إدارة المدفوعات')

@section('content')
<style>
    /* Hide pagination arrows */
    .pagination .page-item:first-child,
    .pagination .page-item:last-child {
        display: none !important;
    }
    
    /* Hide pagination input fields */
    .pagination input[type="number"],
    .pagination input[type="text"] {
        display: none !important;
    }
    
    /* Hide large standalone arrow icons */
    svg[width="24"],
    svg[width="32"],
    svg[width="48"] {
        display: none !important;
    }
</style>
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="page-header-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h5 class="mb-0 ms-2">إدارة المدفوعات</h5>
        </div>
        <a href="{{ route('invoices.index') }}" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-2"></i> العودة للفواتير
        </a>
    </div>
    <div class="card-body">
        @if($payments->count() > 0)
        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-label">إجمالي المدفوعات</div>
                        <div class="stat-card-value">{{ $stats['total_payments'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card-decoration"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-label">إجمالي المبلغ</div>
                        <div class="stat-card-value">{{ number_format($stats['total_amount'] ?? 0, 2) }} ج.م</div>
                    </div>
                    <div class="stat-card-decoration"></div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('payments.index') }}" class="mb-4">
            <div class="card bg-light border-0 mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-search text-primary me-1"></i> البحث
                            </label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="رقم الفاتورة أو اسم المريض..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-file-invoice text-secondary me-1"></i> الفاتورة
                            </label>
                            <select name="invoice_id" class="form-select">
                                <option value="">جميع الفواتير</option>
                                @foreach($invoices as $invoice)
                                    <option value="{{ $invoice->id }}" {{ request('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                        {{ $invoice->invoice_number }} - {{ $invoice->patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-credit-card text-info me-1"></i> طريقة الدفع
                            </label>
                            <select name="payment_method" class="form-select">
                                <option value="">الكل</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-calendar text-success me-1"></i> من تاريخ
                            </label>
                            <input type="date" 
                                   name="date_from" 
                                   class="form-control" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-calendar text-danger me-1"></i> إلى تاريخ
                            </label>
                            <input type="date" 
                                   name="date_to" 
                                   class="form-control" 
                                   value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i> بحث
                            </button>
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-2"></i> إعادة تعيين
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Payments List: table on md+, cards on small screens -->
        <x-responsive-list>
            <x-slot:table>
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>رقم الفاتورة</th>
                            <th>اسم المريض</th>
                            <th>المبلغ</th>
                            <th>طريقة الدفع</th>
                            <th>التاريخ والوقت</th>
                            <th>المستلم</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                <i class="fas fa-file-invoice me-1 text-muted"></i>
                                <a href="{{ route('invoices.show', $payment->invoice_id) }}" class="text-decoration-none">{{ $payment->invoice->invoice_number }}</a>
                            </td>
                            <td><i class="fas fa-user me-1 text-muted"></i>{{ $payment->invoice->patient->full_name }}</td>
                            <td class="fw-bold text-primary">{{ number_format($payment->amount, 2) }} ج.م</td>
                            <td>
                                @if($payment->payment_method == 'cash')
                                    <span class="badge bg-success">نقدي</span>
                                @else
                                    <span class="badge bg-info">بطاقة ائتمان</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-calendar me-1 text-muted"></i>{{ $payment->created_at->format('Y-m-d') }}
                                <br><small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                            </td>
                            <td><i class="fas fa-user me-1 text-muted"></i>{{ $payment->receiver->name }}</td>
                            <td>
                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-slot:table>
            <x-slot:cards>
                @foreach($payments as $payment)
                <x-list-card
                    :title="$payment->invoice->invoice_number"
                    :title-url="route('invoices.show', $payment->invoice_id)"
                    :badge="$payment->payment_method == 'cash' ? 'نقدي' : 'بطاقة ائتمان'"
                    :badge-variant="$payment->payment_method == 'cash' ? 'success' : 'info'"
                >
                    <x-slot:fields>
                        <x-list-card-field label="المريض" icon="fas fa-user">{{ $payment->invoice->patient->full_name }}</x-list-card-field>
                        <x-list-card-field label="المبلغ" icon="fas fa-coins">{{ number_format($payment->amount, 2) }} ج.م</x-list-card-field>
                        <x-list-card-field label="التاريخ" icon="fas fa-calendar">{{ $payment->created_at->format('Y-m-d H:i') }}</x-list-card-field>
                        <x-list-card-field label="المستلم" icon="fas fa-user-tie">{{ $payment->receiver->name }}</x-list-card-field>
                    </x-slot:fields>
                    <x-slot:actions>
                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye me-1"></i>عرض</a>
                    </x-slot:actions>
                </x-list-card>
                @endforeach
            </x-slot:cards>
        </x-responsive-list>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $payments->links() }}
        </div>
        @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
            <h5>لا توجد مدفوعات مسجلة حالياً</h5>
            <p class="mb-0">
                <a href="{{ route('payments.create') }}" class="alert-link">
                    <i class="fas fa-plus me-1"></i> تسجيل دفعة جديدة
                </a>
            </p>
        </div>
        @endif
    </div>
</div>
@endsection

