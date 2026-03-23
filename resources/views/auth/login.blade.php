@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background: white;
    }

    .login-header {
        text-align: center;
        padding: 2rem 1.5rem 1rem;
    }

    .login-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
    }

    .login-body {
        padding: 0 1.5rem 1.5rem;
    }

    .login-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-color);
        background-color: #f8fafc;
        border-radius: 0 0 12px 12px;
    }

    @media (max-width: 575.98px) {
        .login-container {
            padding: 0.5rem;
        }

        .login-card {
            border-radius: 8px;
        }

        .login-header {
            padding: 1.5rem 1rem 0.75rem;
        }

        .login-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .login-header h2 {
            font-size: 1.5rem;
        }

        .login-header p {
            font-size: 0.875rem;
        }

        .login-body {
            padding: 0 1rem 1rem;
        }

        .login-footer {
            padding: 0.75rem 1rem;
        }

        .login-footer .alert {
            padding: 0.75rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 375px) {
        .login-header h2 {
            font-size: 1.25rem;
        }

        .login-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-hospital"></i>
            </div>
            <h2 class="mb-2 fw-bold" style="color: var(--primary-color);">نظام إدارة العيادة</h2>
            <p class="text-muted mb-0">تسجيل الدخول إلى حسابك</p>
        </div>

        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>خطأ في تسجيل الدخول:</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               placeholder="أدخل بريدك الإلكتروني"
                               autocomplete="email">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required
                               placeholder="أدخل كلمة المرور"
                               autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        تذكرني
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg py-2 fw-semibold">
                        <i class="fas fa-sign-in-alt me-2"></i> تسجيل الدخول
                    </button>
                </div>
            </form>
        </div>

        <div class="login-footer">
            <div class="alert alert-info mb-0">
                <div class="d-flex align-items-start mb-2">
                    <i class="fas fa-info-circle me-2 mt-1"></i>
                    <div>
                        <strong class="d-block mb-1">بيانات الدخول الافتراضية:</strong>
                        <div class="small">
                            <div class="mb-1"><strong>Admin:</strong> admin@clinic.com / password</div>
                            <div class="mb-1"><strong>Doctor:</strong> doctor@clinic.com / password</div>
                            <div class="mb-0"><strong>Receptionist:</strong> receptionist@clinic.com / password</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

