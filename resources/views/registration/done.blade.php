@extends('layouts.app')

@section('title', 'تم إرسال الطلب')

@section('content')
<style>
    .reg-page {
        min-height: 100vh;
        padding: 2rem 1rem;
        background:
            radial-gradient(circle at 15% 15%, rgba(13, 148, 136, 0.1) 0%, transparent 45%),
            radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.1) 0%, transparent 45%),
            linear-gradient(135deg, #f0fdfa 0%, #ecfdf5 100%);
        display: flex;
        align-items: center;
    }

    .reg-wrapper {
        max-width: 640px;
        margin: 0 auto;
        width: 100%;
    }

    .success-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
        text-align: center;
        padding: 2.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .success-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    }

    .success-icon {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto 1.5rem;
        box-shadow: 0 12px 28px rgba(16, 185, 129, 0.35);
        animation: popIn 0.4s ease-out;
    }

    @keyframes popIn {
        0% { transform: scale(0.3); opacity: 0; }
        70% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-card h1 {
        color: var(--text-color);
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .success-card p.lead {
        color: #64748b;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    .request-id {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1.5rem;
        letter-spacing: 0.5px;
    }

    .info-rows {
        background: var(--primary-soft);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        text-align: right;
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.55rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .info-row:last-child { border-bottom: none; }

    .info-row .label {
        color: #64748b;
        font-size: 0.9rem;
    }

    .info-row .value {
        color: var(--text-color);
        font-weight: 600;
    }

    .badge-service {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-checkup {
        background: rgba(13, 148, 136, 0.1);
        color: var(--primary-color);
    }

    .badge-consultation {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .next-steps {
        text-align: right;
        background: rgba(13, 148, 136, 0.06);
        border-right: 4px solid var(--primary-color);
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .next-steps h6 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .next-steps ul {
        margin: 0;
        padding-right: 1.25rem;
        color: #475569;
        font-size: 0.9rem;
    }

    .next-steps li { margin-bottom: 0.4rem; }
    .next-steps li:last-child { margin-bottom: 0; }

    @media (max-width: 575.98px) {
        .success-card { padding: 1.75rem 1.25rem; }
        .success-card h1 { font-size: 1.4rem; }
        .success-icon { width: 80px; height: 80px; font-size: 2.5rem; }
    }
</style>

<div class="reg-page">
    <div class="reg-wrapper">
        <div class="success-card">
            <div class="success-icon"><i class="fas fa-check"></i></div>
            <h1>تم إرسال طلبك بنجاح!</h1>
            <p class="lead">شكراً لتسجيلك. سيتم التواصل معك قريباً لتأكيد ميعاد الكشف.</p>

            <div class="request-id">
                <i class="fas fa-hashtag"></i> رقم الطلب: {{ str_pad($appointmentRequest->id, 6, '0', STR_PAD_LEFT) }}
            </div>

            <div class="info-rows">
                @php
                    $regPerson = $appointmentRequest->displayPatient();
                @endphp
                <div class="info-row">
                    <span class="label"><i class="fas fa-user me-2"></i>اسم المريض</span>
                    <span class="value">{{ $regPerson->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="label"><i class="fas fa-phone me-2"></i>رقم الهاتف</span>
                    <span class="value">{{ $regPerson->phone_number }}</span>
                </div>
                <div class="info-row">
                    <span class="label"><i class="fas fa-stethoscope me-2"></i>نوع الخدمة</span>
                    <span class="value">
                        @if($appointmentRequest->service_type === 'checkup')
                            <span class="badge-service badge-checkup">
                                <i class="fas fa-user-md"></i> كشف جديد
                            </span>
                        @else
                            <span class="badge-service badge-consultation">
                                <i class="fas fa-comments"></i> استشارة
                            </span>
                        @endif
                    </span>
                </div>
                @if($appointmentRequest->specialization)
                    <div class="info-row">
                        <span class="label"><i class="fas fa-briefcase-medical me-2"></i>التخصص</span>
                        <span class="value">{{ $appointmentRequest->specialization->name }}</span>
                    </div>
                @endif
                @if($appointmentRequest->preferredDoctor)
                    <div class="info-row">
                        <span class="label"><i class="fas fa-user-md me-2"></i>الطبيب المفضل</span>
                        <span class="value">د. {{ $appointmentRequest->preferredDoctor->name }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="label"><i class="fas fa-clock me-2"></i>تاريخ الطلب</span>
                    <span class="value">{{ $appointmentRequest->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            <div class="next-steps">
                <h6><i class="fas fa-info-circle me-1"></i> الخطوات التالية</h6>
                <ul>
                    <li>سيقوم موظف الاستقبال بمراجعة طلبك وتحديد ميعاد مناسب مع الطبيب.</li>
                    <li>هنتواصل معاك على رقم الهاتف المسجل لتأكيد الميعاد.</li>
                    <li>احتفظ برقم الطلب للرجوع إليه عند التواصل مع العيادة.</li>
                </ul>
            </div>

            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('registration.form') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus me-1"></i> تسجيل مريض آخر
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
