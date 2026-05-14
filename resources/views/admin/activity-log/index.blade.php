@extends('layouts.app')

@section('title', 'سجل النشاط')
@section('page-title', 'سجل النشاط')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i> سجل النظام (Activity log)
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.activity-log.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-5">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-search me-1 text-muted"></i> بحث
                    </label>
                    <input type="text" name="search" class="form-control" placeholder="الوصف، اسم السجل، الحدث…"
                           value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> تطبيق
                    </button>
                </div>
            </div>
        </form>

        @if($activities->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle small">
                    <thead class="table-light">
                        <tr>
                            <th width="100">التاريخ</th>
                            <th>الوصف</th>
                            <th width="140">المستخدم</th>
                            <th width="160">موضوع الحدث</th>
                            <th width="100">حدث</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $log)
                            <tr>
                                <td class="text-nowrap">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    @if($log->causer instanceof \App\Models\User)
                                        {{ $log->causer->name }}
                                    @elseif($log->causer)
                                        {{ class_basename($log->causer_type) }} #{{ $log->causer_id }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    @if($log->subject_type)
                                        {{ class_basename($log->subject_type) }}
                                        #{{ $log->subject_id }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $log->event ?? '—' }}</td>
                            </tr>
                            @if($log->properties && $log->properties->isNotEmpty())
                                <tr class="border-top-0 pt-0">
                                    <td colspan="5" class="text-muted pb-3 small">
                                        <code class="d-block whitespace-pre-wrap">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $activities->links() }}
        @else
            <p class="text-muted mb-0">لا توجد سجلات حتى الآن.</p>
        @endif
    </div>
</div>
@endsection
