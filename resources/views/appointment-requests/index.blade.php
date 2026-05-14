@extends('layouts.app')

@section('title', 'طلبات الحجز')
@section('page-title', 'طلبات الحجز من المرضى')

@section('content')
<style>
    .req-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .req-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1rem;
        background: white;
        border: 1.5px solid var(--border-color);
        border-radius: 999px;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .req-tab:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .req-tab.active {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        box-shadow: 0 6px 14px rgba(13, 148, 136, 0.25);
    }

    .req-tab .count {
        background: rgba(255, 255, 255, 0.25);
        color: inherit;
        padding: 0.15rem 0.55rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .req-tab:not(.active) .count {
        background: #f1f5f9;
        color: #64748b;
    }

    .req-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .req-badge.checkup {
        background: rgba(13, 148, 136, 0.1);
        color: var(--primary-color);
    }

    .req-badge.consultation {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.7rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .status-pill.pending { background: rgba(245, 158, 11, 0.12); color: #b45309; }
    .status-pill.processed { background: rgba(16, 185, 129, 0.12); color: #047857; }
    .status-pill.canceled { background: rgba(239, 68, 68, 0.12); color: #b91c1c; }

    .request-id-chip {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        font-weight: 700;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fas fa-inbox text-primary me-2"></i>طلبات الحجز
        </h5>
        <small class="text-muted">
            طلبات الحجز اللي بيرسلها المرضى من صفحة التسجيل العامة
        </small>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="req-tabs">
            <a href="{{ route('appointment-requests.index', ['status' => 'pending']) }}"
               class="req-tab {{ $status === 'pending' ? 'active' : '' }}">
                <i class="fas fa-clock"></i> قيد الانتظار
                <span class="count">{{ $counts['pending'] }}</span>
            </a>
            <a href="{{ route('appointment-requests.index', ['status' => 'processed']) }}"
               class="req-tab {{ $status === 'processed' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i> تم الحجز
                <span class="count">{{ $counts['processed'] }}</span>
            </a>
            <a href="{{ route('appointment-requests.index', ['status' => 'canceled']) }}"
               class="req-tab {{ $status === 'canceled' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i> ملغي
                <span class="count">{{ $counts['canceled'] }}</span>
            </a>
        </div>

        <form method="GET" class="mb-3">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                       placeholder="ابحث باسم المريض أو رقم الهاتف..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">بحث</button>
                @if(request('search'))
                    <a href="{{ route('appointment-requests.index', ['status' => $status]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>

        @if($appointmentRequests->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox text-muted" style="font-size: 4rem; opacity: 0.4;"></i>
                <h5 class="text-muted mt-3">لا توجد طلبات
                    @if($status === 'pending') قيد الانتظار
                    @elseif($status === 'processed') تم تأكيدها
                    @else ملغاة
                    @endif
                </h5>
                <p class="text-muted small">سيظهر هنا الطلبات اللي بيرسلها المرضى من صفحة التسجيل العامة.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>رقم الطلب</th>
                            <th>المريض</th>
                            <th>الخدمة</th>
                            <th>التخصص</th>
                            <th>الطبيب المفضل</th>
                            <th>تاريخ الطلب</th>
                            <th>الحالة</th>
                            <th class="text-center">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointmentRequests as $req)
                            @php $dp = $req->displayPatient(); @endphp
                            <tr>
                                <td>
                                    <span class="request-id-chip">#{{ str_pad($req->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $dp->full_name }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $dp->phone_number }}
                                    </small>
                                </td>
                                <td>
                                    @if($req->service_type === 'checkup')
                                        <span class="req-badge checkup"><i class="fas fa-user-md"></i> كشف جديد</span>
                                    @else
                                        <span class="req-badge consultation"><i class="fas fa-comments"></i> استشارة</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $req->specialization->name ?? '—' }}
                                </td>
                                <td>
                                    @if($req->preferredDoctor)
                                        د. {{ $req->preferredDoctor->name }}
                                    @else
                                        <span class="text-muted">أي طبيب</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $req->created_at->format('Y-m-d') }}</div>
                                    <small class="text-muted">{{ $req->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="status-pill {{ $req->status }}">
                                        @if($req->status === 'pending')
                                            <i class="fas fa-clock"></i> قيد الانتظار
                                        @elseif($req->status === 'processed')
                                            <i class="fas fa-check"></i> تم الحجز
                                        @else
                                            <i class="fas fa-times"></i> ملغي
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('appointment-requests.show', $req->id) }}"
                                       class="btn btn-sm {{ $req->status === 'pending' && auth()->user()->hasPermission('process_appointment_requests') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                        @if($req->status === 'pending' && auth()->user()->hasPermission('process_appointment_requests'))
                                            <i class="fas fa-check"></i> معالجة
                                        @else
                                            <i class="fas fa-eye"></i> عرض
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $appointmentRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
