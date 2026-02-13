@extends('layouts.app')

@section('title', 'المرضى')
@section('page-title', 'إدارة المرضى')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="page-header-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">قائمة المرضى</h5>
                        <small class="opacity-75">إدارة جميع المرضى المسجلين في النظام</small>
                    </div>
                </div>
                @if(auth()->user()->canManagePatients())
                <a href="{{ route('patients.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-2"></i> إضافة مريض جديد
                </a>
                @endif
            </div>
            <div class="card-body p-4">
                <!-- Statistics Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">إجمالي المرضى</div>
                                <div class="stat-card-value">{{ $stats['total'] ?? 0 }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-info">
                            <div class="stat-card-icon">
                                <i class="fas fa-mars"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">ذكور</div>
                                <div class="stat-card-value">{{ $stats['male'] ?? 0 }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="stat-card stat-card-danger">
                            <div class="stat-card-icon">
                                <i class="fas fa-venus"></i>
                            </div>
                            <div class="stat-card-content">
                                <div class="stat-card-label">إناث</div>
                                <div class="stat-card-value">{{ $stats['female'] ?? 0 }}</div>
                            </div>
                            <div class="stat-card-decoration"></div>
                        </div>
                    </div>
                </div>

                <!-- Search Form -->
                <form method="GET" action="{{ route('patients.index') }}" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-10 col-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control border-start-0" 
                                       placeholder="ابحث بالاسم، رقم الهاتف، أو رقم الهوية..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <button class="btn btn-primary w-100 h-100" type="submit">
                                <i class="fas fa-search d-md-inline d-none me-md-2"></i>
                                <span>بحث</span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Patients List: table on md+, cards on small screens -->
                @if($patients->count() > 0)
                <x-responsive-list>
                    <x-slot:table>
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>الاسم الكامل</th>
                                    <th>رقم الهاتف</th>
                                    <th width="100">الجنس</th>
                                    <th width="80">العمر</th>
                                    <th width="120">تاريخ الإضافة</th>
                                    <th width="200" class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                <tr>
                                    <td class="text-muted"><strong>{{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}</strong></td>
                                    <td>
                                        <a href="{{ route('patients.show', $patient->id) }}" class="text-decoration-none fw-semibold text-dark">{{ $patient->full_name }}</a>
                                    </td>
                                    <td>
                                        <a href="tel:{{ $patient->phone_number }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1 text-primary"></i>{{ $patient->phone_number }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($patient->gender == 'male')
                                            <span class="badge bg-primary-subtle text-primary border border-primary"><i class="fas fa-mars me-1"></i>ذكر</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger"><i class="fas fa-venus me-1"></i>أنثى</span>
                                        @endif
                                    </td>
                                    <td><span class="text-muted"><i class="fas fa-birthday-cake me-1"></i>{{ $patient->age }} سنة</span></td>
                                    <td><span class="text-muted small"><i class="fas fa-calendar me-1"></i>{{ $patient->created_at->format('Y-m-d') }}</span></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-outline-info" title="عرض"><i class="fas fa-eye"></i><span class="d-none d-lg-inline ms-1">عرض</span></a>
                                            @if(auth()->user()->canManagePatients())
                                            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل"><i class="fas fa-edit"></i><span class="d-none d-lg-inline ms-1">تعديل</span></a>
                                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف المريض {{ $patient->full_name }}؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"><i class="fas fa-trash"></i><span class="d-none d-lg-inline ms-1">حذف</span></button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-slot:table>
                    <x-slot:cards>
                        @foreach($patients as $patient)
                        <x-list-card
                            :title="$patient->full_name"
                            :title-url="route('patients.show', $patient->id)"
                            :badge="$patient->gender == 'male' ? 'ذكر' : 'أنثى'"
                            :badge-variant="$patient->gender == 'male' ? 'primary' : 'danger'"
                        >
                            <x-slot:fields>
                                <x-list-card-field label="رقم الهاتف" icon="fas fa-phone">
                                    <a href="tel:{{ $patient->phone_number }}" class="text-decoration-none">{{ $patient->phone_number }}</a>
                                </x-list-card-field>
                                <x-list-card-field label="العمر" icon="fas fa-birthday-cake">{{ $patient->age }} سنة</x-list-card-field>
                                <x-list-card-field label="تاريخ الإضافة" icon="fas fa-calendar">{{ $patient->created_at->format('Y-m-d') }}</x-list-card-field>
                            </x-slot:fields>
                            <x-slot:actions>
                                <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye me-1"></i>عرض</a>
                                @if(auth()->user()->canManagePatients())
                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit me-1"></i>تعديل</a>
                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف المريض؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>حذف</button>
                                </form>
                                @endif
                            </x-slot:actions>
                        </x-list-card>
                        @endforeach
                    </x-slot:cards>
                </x-responsive-list>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $patients->links() }}
                </div>
                @else
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-3">
                        <i class="fas fa-users fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-2">لا توجد مرضى مسجلين</h5>
                    <p class="text-muted mb-4">
                        @if(request('search'))
                            لم يتم العثور على مرضى يطابقون البحث "{{ request('search') }}"
                        @else
                            لا توجد مرضى مسجلين حالياً في النظام
                        @endif
                    </p>
                    @if(auth()->user()->canManagePatients())
                    <a href="{{ route('patients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> إضافة مريض جديد
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .page-header-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-left: 1rem;
    }

    .stat-card {
        position: relative;
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: white;
        margin-bottom: 0.75rem;
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
    
    .stat-card-danger .stat-card-icon {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .stat-card-content {
        position: relative;
        z-index: 2;
    }
    
    .stat-card-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.4rem;
    }
    
    .stat-card-value {
        font-size: 1.6rem;
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
    
    .stat-card-danger .stat-card-value {
        color: #ef4444;
    }
    
    .stat-card-decoration {
        position: absolute;
        bottom: -20px;
        left: -20px;
        width: 100px;
        height: 100px;
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
    
    .stat-card-danger .stat-card-decoration {
        background: #ef4444;
    }

    .patient-avatar {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.95rem;
    }

    .table thead th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        padding: 0.875rem 0.75rem;
    }

    .table tbody td {
        padding: 0.875rem 0.75rem;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        opacity: 0.5;
    }

    .input-group-text {
        border-color: #ced4da;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    @media (max-width: 767.98px) {
        .page-header-icon {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }

        .stat-card {
            min-height: 100px;
            padding: 1.25rem;
        }
        
        .stat-card-icon {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }
        
        .stat-card-value {
            font-size: 1.5rem;
        }
    }
</style>
@endpush
@endsection

