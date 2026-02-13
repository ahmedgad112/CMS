@extends('layouts.app')

@section('title', 'الفواتير')
@section('page-title', 'إدارة الفواتير')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-file-invoice me-2"></i> قائمة الفواتير
        </h5>
        @if(auth()->user()->isAccountant() || auth()->user()->isAdmin() || auth()->user()->isReceptionist())
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> إنشاء فاتورة جديدة
        </a>
        @endif
    </div>
    <div class="card-body">
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('invoices.index') }}" class="mb-4">
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
                                   placeholder="ابحث برقم الفاتورة..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-toggle-on text-warning me-1"></i> الحالة
                            </label>
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوعة</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-user text-info me-1"></i> المريض
                            </label>
                            <select name="patient_id" class="form-select">
                                <option value="">جميع المرضى</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <label class="form-label fw-semibold mb-2">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-search me-1"></i> بحث
                                </button>
                                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters -->
                    @if(request('search') || request('status') || request('patient_id'))
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="text-muted small">
                                <i class="fas fa-filter me-1"></i> الفلاتر النشطة:
                            </span>
                            @if(request('search'))
                            <span class="badge bg-primary">
                                <i class="fas fa-search me-1"></i>
                                البحث: {{ request('search') }}
                            </span>
                            @endif
                            @if(request('status'))
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-toggle-on me-1"></i>
                                الحالة: {{ request('status') == 'paid' ? 'مدفوعة' : 'غير مدفوعة' }}
                            </span>
                            @endif
                            @if(request('patient_id'))
                            <span class="badge bg-info">
                                <i class="fas fa-user me-1"></i>
                                المريض: {{ $patients->find(request('patient_id'))->full_name ?? '' }}
                            </span>
                            @endif
                            <a href="{{ route('invoices.index') }}" class="badge bg-danger text-decoration-none">
                                <i class="fas fa-times me-1"></i> مسح الكل
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-label">إجمالي الفواتير</div>
                        <div class="stat-card-value">{{ $stats['total_invoices'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card-decoration"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card stat-card-info">
                    <div class="stat-card-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-label">إجمالي المبلغ</div>
                        <div class="stat-card-value">
                            {{ number_format($stats['total_amount'] ?? 0, 2) }}
                            <span class="stat-card-currency">ج.م</span>
                        </div>
                    </div>
                    <div class="stat-card-decoration"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-label">المدفوع</div>
                        <div class="stat-card-value">
                            {{ number_format($stats['paid_amount'] ?? 0, 2) }}
                            <span class="stat-card-currency">ج.م</span>
                        </div>
                    </div>
                    <div class="stat-card-decoration"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card stat-card-danger">
                    <div class="stat-card-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-label">المتبقي</div>
                        <div class="stat-card-value">
                            {{ number_format($stats['unpaid_amount'] ?? 0, 2) }}
                            <span class="stat-card-currency">ج.م</span>
                        </div>
                    </div>
                    <div class="stat-card-decoration"></div>
                </div>
            </div>
        </div>

@push('styles')
<style>
    .stat-card {
        position: relative;
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card-primary .stat-card-icon {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    
    .stat-card-info .stat-card-icon {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    }
    
    .stat-card-success .stat-card-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .stat-card-danger .stat-card-icon {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .stat-card-content {
        position: relative;
        z-index: 2;
    }
    
    .stat-card-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .stat-card-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }
    
    .stat-card-primary .stat-card-value {
        color: #2563eb;
    }
    
    .stat-card-info .stat-card-value {
        color: #06b6d4;
    }
    
    .stat-card-success .stat-card-value {
        color: #10b981;
    }
    
    .stat-card-danger .stat-card-value {
        color: #ef4444;
    }
    
    .stat-card-currency {
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748b;
        margin-right: 0.25rem;
    }
    
    .stat-card-decoration {
        position: absolute;
        bottom: -20px;
        left: -20px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        opacity: 0.1;
        z-index: 1;
    }
    
    .stat-card-primary .stat-card-decoration {
        background: #2563eb;
    }
    
    .stat-card-info .stat-card-decoration {
        background: #06b6d4;
    }
    
    .stat-card-success .stat-card-decoration {
        background: #10b981;
    }
    
    .stat-card-danger .stat-card-decoration {
        background: #ef4444;
    }
    
    @media (max-width: 767.98px) {
        .stat-card {
            min-height: 120px;
            padding: 1.25rem;
        }
        
        .stat-card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
        
        .stat-card-value {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

        <!-- Invoices List: table on md+, cards on small screens -->
        @if($invoices->count() > 0)
        <x-responsive-list>
            <x-slot:table>
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>رقم الفاتورة</th>
                            <th>المريض</th>
                            <th>المبلغ الإجمالي</th>
                            <th>المدفوع</th>
                            <th>المتبقي</th>
                            <th>تاريخ الإنشاء</th>
                            <th width="100">الحالة</th>
                            <th width="150" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.875rem;"><i class="fas fa-file-invoice"></i></div>
                                    <div>
                                        <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                        @if($invoice->appointment)<div class="small text-muted"><i class="fas fa-calendar-check me-1"></i>مرتبطة بموعد</div>@endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.875rem;"><i class="fas fa-user"></i></div>
                                    <div>
                                        <a href="{{ route('patients.show', $invoice->patient_id) }}" class="text-decoration-none fw-bold">{{ $invoice->patient->full_name }}</a>
                                        <div class="small text-muted"><i class="fas fa-phone me-1"></i>{{ $invoice->patient->phone_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><strong class="text-primary">{{ number_format($invoice->total_amount, 2) }} <small class="text-muted">ج.م</small></strong></td>
                            <td><span class="text-success fw-bold">{{ number_format($invoice->paid_amount, 2) }} <small class="text-muted">ج.م</small></span></td>
                            <td>
                                @if($invoice->remaining_amount > 0)
                                    <span class="text-danger fw-bold"><i class="fas fa-exclamation-circle me-1"></i>{{ number_format($invoice->remaining_amount, 2) }} <small class="text-muted">ج.م</small></span>
                                @else
                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>0.00 <small class="text-muted">ج.م</small></span>
                                @endif
                            </td>
                            <td class="text-muted"><i class="fas fa-calendar me-1"></i>{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if($invoice->status == 'paid')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> مدفوعة</span>
                                @else
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> غير مدفوعة</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info" title="عرض"><i class="fas fa-eye"></i><span class="d-none d-md-inline ms-1">عرض</span></a>
                                    @if(auth()->user()->isAccountant() || auth()->user()->isAdmin() || auth()->user()->isReceptionist())
                                    @if($invoice->status == 'unpaid')
                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning" title="تعديل"><i class="fas fa-edit"></i><span class="d-none d-md-inline ms-1">تعديل</span></a>
                                    @endif
                                    @if($invoice->remaining_amount > 0)
                                    <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-success" title="دفع"><i class="fas fa-money-bill-wave"></i><span class="d-none d-md-inline ms-1">دفع</span></a>
                                    @endif
                                    @endif
                                    <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-primary" title="طباعة" target="_blank"><i class="fas fa-print"></i><span class="d-none d-md-inline ms-1">طباعة</span></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-slot:table>
            <x-slot:cards>
                @foreach($invoices as $invoice)
                <x-list-card
                    :title="$invoice->invoice_number"
                    :title-url="route('invoices.show', $invoice->id)"
                    :badge="$invoice->status == 'paid' ? 'مدفوعة' : 'غير مدفوعة'"
                    :badge-variant="$invoice->status == 'paid' ? 'success' : 'warning'"
                >
                    <x-slot:fields>
                        <x-list-card-field label="المريض" icon="fas fa-user">{{ $invoice->patient->full_name }}</x-list-card-field>
                        <x-list-card-field label="الإجمالي" icon="fas fa-coins">{{ number_format($invoice->total_amount, 2) }} ج.م</x-list-card-field>
                        <x-list-card-field label="المدفوع" icon="fas fa-check-circle">{{ number_format($invoice->paid_amount, 2) }} ج.م</x-list-card-field>
                        <x-list-card-field label="المتبقي" icon="fas fa-exclamation-circle">{{ number_format($invoice->remaining_amount, 2) }} ج.م</x-list-card-field>
                        <x-list-card-field label="التاريخ" icon="fas fa-calendar">{{ $invoice->created_at->format('Y-m-d') }}</x-list-card-field>
                    </x-slot:fields>
                    <x-slot:actions>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye me-1"></i>عرض</a>
                        @if(auth()->user()->isAccountant() || auth()->user()->isAdmin() || auth()->user()->isReceptionist())
                        @if($invoice->status == 'unpaid')
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit me-1"></i>تعديل</a>
                        @endif
                        @if($invoice->remaining_amount > 0)
                        <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-sm btn-success"><i class="fas fa-money-bill-wave me-1"></i>دفع</a>
                        @endif
                        @endif
                        <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-print me-1"></i>طباعة</a>
                    </x-slot:actions>
                </x-list-card>
                @endforeach
            </x-slot:cards>
        </x-responsive-list>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $invoices->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> لا توجد فواتير مسجلة حالياً.
            @if(auth()->user()->isAccountant() || auth()->user()->isAdmin() || auth()->user()->isReceptionist())
            <a href="{{ route('invoices.create') }}" class="alert-link">إنشاء فاتورة جديدة</a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

