@extends('layouts.app')

@section('title', 'إدارة المستخدمين')
@section('page-title', 'إدارة المستخدمين')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-users-cog me-2"></i> قائمة المستخدمين
        </h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> إضافة مستخدم جديد
        </a>
    </div>
    <div class="card-body">
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4 col-12">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-search me-1 text-muted"></i> البحث بالاسم
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-user text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="ابحث بالاسم..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-user-tag me-1 text-muted"></i> الدور
                    </label>
                    <select name="role" class="form-select">
                        <option value="">جميع الأدوار</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                        <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>طبيب</option>
                        <option value="receptionist" {{ request('role') == 'receptionist' ? 'selected' : '' }}>موظف استقبال</option>
                        <option value="call_center" {{ request('role') == 'call_center' ? 'selected' : '' }}>مركز اتصال</option>
                        <option value="accountant" {{ request('role') == 'accountant' ? 'selected' : '' }}>محاسب</option>
                        <option value="storekeeper" {{ request('role') == 'storekeeper' ? 'selected' : '' }}>مخزن</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-toggle-on me-1 text-muted"></i> الحالة
                    </label>
                    <select name="is_active" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-2 col-12 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search d-md-inline d-none me-md-2"></i>
                        <span class="d-md-inline">بحث</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Users Table -->
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th width="100">الحالة</th>
                        <th width="120">تاريخ الإنشاء</th>
                        <th width="150" class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; font-size: 0.875rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-info ms-2">أنت</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-envelope text-muted me-2"></i>
                            {{ $user->email }}
                        </td>
                        <td>
                            @php
                                $roleNames = [
                                    'admin' => ['name' => 'مدير', 'icon' => 'fa-user-shield', 'color' => 'danger'],
                                    'doctor' => ['name' => 'طبيب', 'icon' => 'fa-user-md', 'color' => 'primary'],
                                    'receptionist' => ['name' => 'موظف استقبال', 'icon' => 'fa-user-tie', 'color' => 'info'],
                                    'call_center' => ['name' => 'مركز اتصال', 'icon' => 'fa-headset', 'color' => 'warning'],
                                    'accountant' => ['name' => 'محاسب', 'icon' => 'fa-calculator', 'color' => 'success'],
                                    'storekeeper' => ['name' => 'مخزن', 'icon' => 'fa-warehouse', 'color' => 'secondary']
                                ];
                                $roleInfo = $roleNames[$user->role] ?? ['name' => $user->role, 'icon' => 'fa-user', 'color' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ $roleInfo['color'] }}">
                                <i class="fas {{ $roleInfo['icon'] }} me-1"></i>
                                {{ $roleInfo['name'] }}
                            </span>
                            @if($user->role === 'doctor' && $user->specialization)
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-stethoscope me-1"></i>
                                    {{ $user->specialization }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> نشط
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-times-circle me-1"></i> غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                   class="btn btn-info" 
                                   title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                    <span class="d-none d-md-inline ms-1">عرض</span>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="btn btn-warning" 
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                    <span class="d-none d-md-inline ms-1">تعديل</span>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-md-inline ms-1">حذف</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> لا يوجد مستخدمون مسجلون حالياً.
            <a href="{{ route('admin.users.create') }}" class="alert-link">إضافة مستخدم جديد</a>
        </div>
        @endif
    </div>
</div>
@endsection

