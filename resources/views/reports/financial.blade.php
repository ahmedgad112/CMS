@extends('layouts.app')

@section('title', 'التقرير المالي')
@section('page-title', 'التقرير المالي')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">التقرير المالي</h5>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('reports.financial') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">من تاريخ</label>
                    <input type="date" 
                           class="form-control" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">إلى تاريخ</label>
                    <input type="date" 
                           class="form-control" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                        <a href="{{ route('reports.financial') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> إعادة تعيين
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي الإيرادات</h5>
                        <h3 class="mb-0">{{ number_format($totalRevenue, 2) }} ج.م</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">المبلغ غير المدفوع</h5>
                        <h3 class="mb-0">{{ number_format($unpaidAmount, 2) }} ج.م</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        @if($invoices->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم الفاتورة</th>
                        <th>المريض</th>
                        <th>المبلغ الإجمالي</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>#{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->patient->full_name ?? 'غير محدد' }}</td>
                        <td>{{ number_format($invoice->total_amount, 2) }} ج.م</td>
                        <td>
                            @if($invoice->status == 'paid')
                                <span class="badge bg-success">مدفوع</span>
                            @elseif($invoice->status == 'partial')
                                <span class="badge bg-warning">مدفوع جزئياً</span>
                            @else
                                <span class="badge bg-danger">غير مدفوع</span>
                            @endif
                        </td>
                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('invoices.show', $invoice->id) }}" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> لا توجد فواتير في الفترة المحددة.
        </div>
        @endif
    </div>
</div>
@endsection

