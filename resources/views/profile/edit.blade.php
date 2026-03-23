@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي')
@section('page-title', 'تعديل الملف الشخصي')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i> تعديل الملف الشخصي
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-1 text-muted"></i> الاسم الكامل <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1 text-muted"></i> البريد الإلكتروني <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($user->role === 'doctor')
                        <div class="col-md-6">
                            <label for="specialization" class="form-label">
                                <i class="fas fa-stethoscope me-1 text-muted"></i> التخصص
                            </label>
                            <input type="text" 
                                   class="form-control @error('specialization') is-invalid @enderror" 
                                   id="specialization" 
                                   name="specialization" 
                                   value="{{ old('specialization', $user->specialization) }}">
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1 text-muted"></i> كلمة المرور الجديدة
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اتركه فارغاً إذا لم ترد تغيير كلمة المرور</small>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1 text-muted"></i> تأكيد كلمة المرور
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

