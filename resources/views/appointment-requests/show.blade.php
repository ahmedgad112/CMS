@extends('layouts.app')

@section('title', 'تفاصيل طلب الحجز #'.str_pad($appointmentRequest->id, 6, '0', STR_PAD_LEFT))
@section('page-title', 'تفاصيل طلب الحجز')

@section('content')
<style>
    .info-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
    }

    .info-card-header {
        padding: 0.85rem 1.1rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-body { padding: 1.1rem; }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.55rem 0;
        border-bottom: 1px dashed #e2e8f0;
        gap: 1rem;
    }

    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: #64748b; font-size: 0.875rem; }
    .info-row .value { color: var(--text-color); font-weight: 600; text-align: left; }

    .req-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .req-badge.checkup { background: rgba(13, 148, 136, 0.1); color: var(--primary-color); }
    .req-badge.consultation { background: rgba(245, 158, 11, 0.12); color: #b45309; }

    .status-banner {
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
    }

    .status-banner.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
        border-right: 4px solid #f59e0b;
    }

    .status-banner.processed {
        background: rgba(16, 185, 129, 0.08);
        color: #047857;
        border-right: 4px solid #10b981;
    }

    .status-banner.canceled {
        background: rgba(239, 68, 68, 0.08);
        color: #b91c1c;
        border-right: 4px solid #ef4444;
    }

    .request-id-chip {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        padding: 0.3rem 0.85rem;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 0.95rem;
        font-weight: 700;
    }

    .process-form-card {
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.04) 0%, rgba(13, 148, 136, 0.02) 100%);
        border: 2px dashed rgba(13, 148, 136, 0.3);
        border-radius: 14px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .schedule-section {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .schedule-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: #94a3b8;
    }

    .schedule-table { margin-bottom: 0; }
    .schedule-table th { background: #f8fafc; font-weight: 700; }
    .schedule-table td { vertical-align: middle; }

    .day-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        padding: 0.3rem 0.7rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .day-badge.today { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .day-badge.tomorrow { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

    .time-slot-btn {
        min-width: 92px;
        margin: 0.15rem;
        transition: all 0.15s;
        font-size: 0.85rem;
    }

    .time-slot-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .time-slot-btn.btn-primary {
        font-weight: 700;
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
    }

    .time-slot-btn.booked {
        background: #f1f5f9;
        color: #94a3b8;
        border-color: #e2e8f0;
        cursor: not-allowed;
        pointer-events: none;
        text-decoration: line-through;
    }

    .clinic-info-card {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.04) 0%, rgba(37, 99, 235, 0.02) 100%);
        border: 1px solid rgba(37, 99, 235, 0.2);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-top: 0.75rem;
        font-size: 0.9rem;
    }

    .clinic-info-card .clinic-line {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.3rem;
        color: #475569;
    }

    .clinic-info-card .clinic-line i {
        color: var(--primary-color);
        margin-top: 0.2rem;
    }

    .working-days-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.5rem;
    }

    .working-day-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(37, 99, 235, 0.08);
        color: var(--primary-color);
        padding: 0.3rem 0.7rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid rgba(37, 99, 235, 0.15);
    }

    .selected-slot-box {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border: 1.5px solid rgba(16, 185, 129, 0.3);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-top: 0.75rem;
        color: #047857;
        font-weight: 600;
        display: none;
        align-items: center;
        gap: 0.5rem;
    }

    .selected-slot-box.show { display: flex; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('appointment-requests.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-right"></i> رجوع للقائمة
        </a>
        <span class="request-id-chip">#{{ str_pad($appointmentRequest->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
</div>

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

<div class="status-banner {{ $appointmentRequest->status }}">
    @if($appointmentRequest->status === 'pending')
        <i class="fas fa-clock fa-lg"></i>
        <div>
            <div>الطلب قيد الانتظار - جاهز للمعالجة</div>
            <small class="fw-normal">حدد الطبيب والميعاد وأكد الحجز.</small>
        </div>
    @elseif($appointmentRequest->status === 'processed')
        <i class="fas fa-check-circle fa-lg"></i>
        <div>
            <div>تم تأكيد الحجز وإنشاء الموعد</div>
            <small class="fw-normal">
                @if($appointmentRequest->processor)
                    تمت المعالجة بواسطة {{ $appointmentRequest->processor->name }}
                @endif
                @if($appointmentRequest->processed_at)
                    — {{ $appointmentRequest->processed_at->format('Y-m-d H:i') }}
                @endif
            </small>
        </div>
    @else
        <i class="fas fa-times-circle fa-lg"></i>
        <div>
            <div>تم إلغاء طلب الحجز</div>
            <small class="fw-normal">
                @if($appointmentRequest->processor)
                    ألغاه {{ $appointmentRequest->processor->name }}
                @endif
                @if($appointmentRequest->processed_at)
                    — {{ $appointmentRequest->processed_at->format('Y-m-d H:i') }}
                @endif
            </small>
        </div>
    @endif
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-user text-primary"></i> بيانات المريض
                @if($appointmentRequest->status === 'pending' && ! $appointmentRequest->patient_id)
                    <span class="badge bg-warning text-dark ms-auto">يُضاف لقائمة المرضى عند التأكيد</span>
                @endif
            </div>
            <div class="info-card-body">
                @php $dp = $appointmentRequest->displayPatient(); @endphp
                <div class="info-row">
                    <span class="label">الاسم الكامل</span>
                    <span class="value">{{ $dp->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">رقم الهاتف</span>
                    <span class="value">{{ $dp->phone_number }}</span>
                </div>
                <div class="info-row">
                    <span class="label">السن</span>
                    <span class="value">{{ $dp->age }} سنة</span>
                </div>
                <div class="info-row">
                    <span class="label">النوع</span>
                    <span class="value">
                        @if(($dp->gender ?? '') === 'male')
                            <i class="fas fa-mars text-primary"></i> ذكر
                        @else
                            <i class="fas fa-venus text-danger"></i> أنثى
                        @endif
                    </span>
                </div>
                @if(! empty($dp->medical_history))
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted d-block mb-1"><i class="fas fa-notes-medical me-1"></i>التاريخ الطبي</small>
                        <div style="white-space: pre-wrap; font-size: 0.9rem;">{{ $dp->medical_history }}</div>
                    </div>
                @endif
                @php
                    $chronic = $dp->chronic_diseases ?? null;
                    $chronicArr = [];
                    if ($chronic !== null && $chronic !== '') {
                        $chronicArr = is_array($chronic) ? $chronic : (json_decode($chronic, true) ?: []);
                    }
                @endphp
                @if(! empty($chronicArr))
                    <div class="mt-2">
                        <small class="text-muted d-block mb-1"><i class="fas fa-heartbeat me-1"></i>الأمراض المزمنة</small>
                        <div class="d-flex flex-wrap gap-1">
                            @php
                                $map = [
                                    'diabetes' => 'السكري', 'hypertension' => 'ضغط الدم', 'asthma' => 'الربو',
                                    'heart_disease' => 'القلب', 'kidney_disease' => 'الكلى', 'liver_disease' => 'الكبد',
                                    'arthritis' => 'المفاصل', 'thyroid' => 'الغدة الدرقية', 'anemia' => 'فقر الدم',
                                    'epilepsy' => 'الصرع',
                                ];
                            @endphp
                            @foreach($chronicArr as $c)
                                <span class="badge bg-light text-dark border">{{ $map[$c] ?? $c }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-clipboard-list text-primary"></i> تفاصيل الطلب
            </div>
            <div class="info-card-body">
                <div class="info-row">
                    <span class="label">نوع الخدمة</span>
                    <span class="value">
                        @if($appointmentRequest->service_type === 'checkup')
                            <span class="req-badge checkup"><i class="fas fa-user-md"></i> كشف جديد</span>
                        @else
                            <span class="req-badge consultation"><i class="fas fa-comments"></i> استشارة</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">التخصص المطلوب</span>
                    <span class="value">
                        @if($appointmentRequest->specialization)
                            {{ $appointmentRequest->specialization->name }}
                            @if($appointmentRequest->specialization->department)
                                <small class="text-muted">— {{ $appointmentRequest->specialization->department->name }}</small>
                            @endif
                        @else
                            <span class="text-muted">لم يُحدد</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">الطبيب المفضل</span>
                    <span class="value">
                        @if($appointmentRequest->preferredDoctor)
                            د. {{ $appointmentRequest->preferredDoctor->name }}
                            @if($appointmentRequest->preferredDoctor->specialization)
                                <small class="text-muted d-block">{{ $appointmentRequest->preferredDoctor->specialization->name }}</small>
                            @endif
                        @else
                            <span class="text-muted">أي طبيب متاح</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">تاريخ الطلب</span>
                    <span class="value">{{ $appointmentRequest->created_at->format('Y-m-d H:i') }}</span>
                </div>
                @if($appointmentRequest->notes)
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted d-block mb-1"><i class="fas fa-comment me-1"></i>ملاحظات المريض</small>
                        <div style="white-space: pre-wrap; font-size: 0.9rem;">{{ $appointmentRequest->notes }}</div>
                    </div>
                @endif
                @if($appointmentRequest->appointment)
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted d-block mb-1"><i class="fas fa-calendar-check me-1"></i>الموعد المُنشأ</small>
                        <a href="{{ route('appointments.show', $appointmentRequest->appointment->id) }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-external-link-alt"></i> عرض الموعد
                            ({{ \Carbon\Carbon::parse($appointmentRequest->appointment->appointment_date)->format('Y-m-d H:i') }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($appointmentRequest->status === 'pending')
@if(auth()->user()->hasPermission('process_appointment_requests'))
<div class="process-form-card">
    <h5 class="mb-3">
        <i class="fas fa-calendar-plus text-primary me-2"></i>
        تأكيد الحجز وإنشاء الموعد
    </h5>

    <form method="POST" action="{{ route('appointment-requests.process', $appointmentRequest->id) }}" id="processForm">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label for="doctor_id" class="form-label fw-semibold">
                    <i class="fas fa-user-md text-primary me-1"></i> الطبيب <span class="text-danger">*</span>
                </label>
                <select id="doctor_id" name="doctor_id" class="form-select" required>
                    <option value="">-- اختر الطبيب --</option>
                    @foreach($doctors as $doctor)
                        @php
                            $isPreferred = $appointmentRequest->preferred_doctor_id == $doctor->id;
                            $oldVal = old('doctor_id', $appointmentRequest->preferred_doctor_id);
                        @endphp
                        <option value="{{ $doctor->id }}"
                                {{ $oldVal == $doctor->id ? 'selected' : '' }}>
                            د. {{ $doctor->name }}
                            @if($doctor->specialization) — {{ $doctor->specialization->name }} @endif
                            {{ $isPreferred ? '★ (المفضل)' : '' }}
                        </option>
                    @endforeach
                </select>
                @if($appointmentRequest->preferred_doctor_id)
                    <small class="text-success">
                        <i class="fas fa-star"></i> الطبيب المفضل للمريض تم اختياره تلقائياً
                    </small>
                @endif

                <div id="working_days_box" class="working-days-pills" style="display: none;"></div>
            </div>

            <div class="col-md-6">
                <label for="clinic_id" class="form-label fw-semibold">
                    <i class="fas fa-hospital text-info me-1"></i> الفرع / العيادة
                </label>
                <select id="clinic_id" name="clinic_id" class="form-select">
                    <option value="">-- اختر الفرع --</option>
                    @foreach($clinics ?? [] as $clinic)
                        <option value="{{ $clinic->id }}"
                                data-phone="{{ $clinic->phone }}"
                                data-address="{{ $clinic->address }}"
                                data-city="{{ $clinic->city }}"
                                data-working-hours="{{ $clinic->working_hours }}"
                                data-is-main="{{ $clinic->is_main ? '1' : '0' }}"
                                {{ old('clinic_id', $appointmentRequest->preferred_clinic_id) == $clinic->id ? 'selected' : '' }}>
                            {{ $clinic->name }}
                            @if($clinic->is_main) (الرئيسية) @endif
                            @if($clinic->city) - {{ $clinic->city }} @endif
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">اختر الطبيب أولاً لعرض الفروع التابعة له</small>

                <div id="clinic_info_box" class="clinic-info-card" style="display: none;"></div>
            </div>
        </div>

        <input type="hidden" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}">
        <input type="hidden" id="appointment_time" name="appointment_time" value="{{ old('appointment_time') }}">

        <div class="schedule-section">
            <h6 class="fw-bold mb-2">
                <i class="fas fa-calendar-week text-primary me-1"></i>
                مواعيد الطبيب المتاحة <span class="text-danger">*</span>
            </h6>
            <small class="text-muted d-block mb-2">
                <i class="fas fa-info-circle me-1"></i>
                اختار الطبيب لعرض الأيام والمواعيد اللي بيشتغل فيها، وبعدين اضغط على الميعاد المناسب.
            </small>

            <div id="schedule_empty" class="schedule-empty">
                <i class="fas fa-user-md" style="font-size: 2.5rem; opacity: 0.3;"></i>
                <div class="mt-2">اختار الطبيب لعرض جدول مواعيده.</div>
            </div>

            <div id="schedule_loading" class="schedule-empty" style="display: none;">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-2">جاري تحميل المواعيد...</div>
            </div>

            <div id="schedule_no_data" class="schedule-empty" style="display: none;">
                <i class="fas fa-calendar-times text-warning" style="font-size: 2.5rem;"></i>
                <div class="mt-2 text-warning fw-semibold">لم يتم تحديد جدول عمل لهذا الطبيب بعد.</div>
                <small class="text-muted">يمكنك إدخال التاريخ والوقت يدوياً من الحقول أدناه.</small>
            </div>

            <div id="schedule_table_wrapper" class="table-responsive" style="display: none;">
                <table class="table table-bordered table-hover schedule-table no-mobile-cards">
                    <thead>
                        <tr>
                            <th style="width: 220px;">اليوم والتاريخ</th>
                            <th>المواعيد المتاحة (انقر على الميعاد لاختياره)</th>
                        </tr>
                    </thead>
                    <tbody id="schedule_tbody"></tbody>
                </table>
            </div>

            <div id="selected_slot_box" class="selected-slot-box">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>تم اختيار الميعاد: </strong>
                    <span id="selected_slot_text"></span>
                </div>
            </div>

            <details class="mt-3">
                <summary class="text-muted" style="cursor: pointer;">
                    <i class="fas fa-edit me-1"></i> أو أدخل التاريخ والوقت يدوياً
                </summary>
                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label class="form-label small">التاريخ</label>
                        <input type="date" id="manual_date"
                               class="form-control form-control-sm"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('appointment_date') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">الوقت</label>
                        <input type="time" id="manual_time"
                               class="form-control form-control-sm"
                               value="{{ old('appointment_time') }}">
                    </div>
                </div>
                <small class="text-muted d-block mt-1">
                    استخدم هذه الحقول لو محتاج تحجز خارج جدول الطبيب المعتاد.
                </small>
            </details>
        </div>

        <div class="mt-3">
            <label for="notes" class="form-label fw-semibold">
                ملاحظات الموعد <small class="text-muted fw-normal">(اختياري)</small>
            </label>
            <textarea id="notes" name="notes" rows="2" class="form-control"
                      placeholder="ملاحظات للطبيب أو الفريق الطبي...">{{ old('notes', $appointmentRequest->notes) }}</textarea>
        </div>

        <div class="d-flex justify-content-between gap-2 mt-3 flex-wrap">
            <button type="button" class="btn btn-outline-danger"
                    onclick="document.getElementById('cancelForm').submit()">
                <i class="fas fa-times"></i> إلغاء الطلب
            </button>
            <button type="submit" class="btn btn-success btn-lg px-4" id="submitProcessBtn">
                <i class="fas fa-check"></i> تأكيد وإنشاء الموعد
            </button>
        </div>
    </form>

    <form id="cancelForm" method="POST" action="{{ route('appointment-requests.cancel', $appointmentRequest->id) }}"
          onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟');" class="d-none">
        @csrf
    </form>
</div>
@else
<div class="alert alert-info d-flex align-items-start gap-2 mb-0">
    <i class="fas fa-info-circle mt-1"></i>
    <div>يمكنك عرض تفاصيل هذا الطلب فقط. لتأكيد الحجز أو إلغائه تحتاج صلاحية <strong>معالجة طلبات الحجز</strong>.</div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const doctorSelect = document.getElementById('doctor_id');
    const clinicSelect = document.getElementById('clinic_id');
    const clinicInfoBox = document.getElementById('clinic_info_box');
    const workingDaysBox = document.getElementById('working_days_box');
    const scheduleEmpty = document.getElementById('schedule_empty');
    const scheduleLoading = document.getElementById('schedule_loading');
    const scheduleNoData = document.getElementById('schedule_no_data');
    const scheduleTableWrapper = document.getElementById('schedule_table_wrapper');
    const scheduleTbody = document.getElementById('schedule_tbody');
    const selectedSlotBox = document.getElementById('selected_slot_box');
    const selectedSlotText = document.getElementById('selected_slot_text');
    const appointmentDateInput = document.getElementById('appointment_date');
    const appointmentTimeInput = document.getElementById('appointment_time');
    const manualDate = document.getElementById('manual_date');
    const manualTime = document.getElementById('manual_time');
    const submitBtn = document.getElementById('submitProcessBtn');
    const processForm = document.getElementById('processForm');

    if (!doctorSelect) return;

    const initiallySelectedClinic = clinicSelect ? clinicSelect.value : '';
    let doctorSchedules = [];
    let bookedSlots = new Set();
    let selectedDate = appointmentDateInput.value || null;
    let selectedTime = appointmentTimeInput.value || null;

    const dayNames = {
        saturday: 'السبت', sunday: 'الأحد', monday: 'الإثنين',
        tuesday: 'الثلاثاء', wednesday: 'الأربعاء', thursday: 'الخميس', friday: 'الجمعة'
    };
    const daysOrder = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    function pad2(n) { return String(n).padStart(2, '0'); }
    function getDayName(d) { return daysOrder[d.getDay()]; }
    function formatDate(d) { return `${d.getFullYear()}-${pad2(d.getMonth() + 1)}-${pad2(d.getDate())}`; }
    function formatTime12(h, m) {
        const period = h >= 12 ? 'م' : 'ص';
        const display = h > 12 ? h - 12 : (h === 0 ? 12 : h);
        return `${pad2(display)}:${pad2(m)} ${period}`;
    }
    function formatTime24(h, m) { return `${pad2(h)}:${pad2(m)}`; }

    function showOnly(el) {
        [scheduleEmpty, scheduleLoading, scheduleNoData, scheduleTableWrapper].forEach(e => {
            if (e) e.style.display = 'none';
        });
        if (el) el.style.display = el === scheduleTableWrapper ? 'block' : 'block';
    }

    function clearSelection() {
        selectedDate = null;
        selectedTime = null;
        appointmentDateInput.value = '';
        appointmentTimeInput.value = '';
        selectedSlotBox.classList.remove('show');
        document.querySelectorAll('.time-slot-btn').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-primary');
        });
    }

    function updateSelectedSlot(date, time, label) {
        selectedDate = date;
        selectedTime = time;
        appointmentDateInput.value = date;
        appointmentTimeInput.value = time;
        manualDate.value = date;
        manualTime.value = time;
        selectedSlotText.textContent = label;
        selectedSlotBox.classList.add('show');
    }

    function renderWorkingDays() {
        if (!doctorSchedules || doctorSchedules.length === 0) {
            workingDaysBox.style.display = 'none';
            workingDaysBox.innerHTML = '';
            return;
        }
        const uniqueDays = [...new Set(doctorSchedules.map(s => s.day_of_week))];
        uniqueDays.sort((a, b) => daysOrder.indexOf(a) - daysOrder.indexOf(b));

        workingDaysBox.innerHTML = uniqueDays.map(d => {
            const daySchedules = doctorSchedules.filter(s => s.day_of_week === d);
            const times = daySchedules.map(s => `${s.start_time}-${s.end_time}`).join(' / ');
            return `<span class="working-day-pill" title="${times}">
                <i class="fas fa-calendar-day"></i> ${dayNames[d]}
            </span>`;
        }).join('');
        workingDaysBox.style.display = 'flex';
    }

    function buildScheduleTable() {
        scheduleTbody.innerHTML = '';
        if (!doctorSchedules || doctorSchedules.length === 0) {
            showOnly(scheduleNoData);
            return;
        }

        const availableDays = new Set(doctorSchedules.map(s => s.day_of_week));
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const now = new Date();

        let rowsAdded = 0;

        for (let i = 0; i < 30; i++) {
            const date = new Date(today);
            date.setDate(today.getDate() + i);
            const dayKey = getDayName(date);

            if (!availableDays.has(dayKey)) continue;

            const dateStr = formatDate(date);
            const daySchedules = doctorSchedules.filter(s => s.day_of_week === dayKey);

            const slots = [];
            const seen = new Set();
            daySchedules.forEach(s => {
                const [sh, sm] = s.start_time.split(':').map(Number);
                const [eh, em] = s.end_time.split(':').map(Number);
                let startMin = sh * 60 + sm;
                const endMin = eh * 60 + em;
                for (; startMin < endMin; startMin += 30) {
                    const h = Math.floor(startMin / 60);
                    const m = startMin % 60;
                    const t24 = formatTime24(h, m);
                    if (seen.has(t24)) continue;
                    seen.add(t24);

                    if (i === 0) {
                        const slotDate = new Date(date);
                        slotDate.setHours(h, m, 0, 0);
                        if (slotDate <= now) continue;
                    }

                    slots.push({ t24, display: formatTime12(h, m) });
                }
            });

            if (slots.length === 0) continue;

            const tr = document.createElement('tr');

            const td1 = document.createElement('td');
            let badgeClass = 'day-badge';
            let suffix = '';
            if (i === 0) { badgeClass += ' today'; suffix = ' (اليوم)'; }
            else if (i === 1) { badgeClass += ' tomorrow'; suffix = ' (غداً)'; }
            td1.innerHTML = `<span class="${badgeClass}"><i class="fas fa-calendar-day"></i> ${dayNames[dayKey]}</span>
                <div class="mt-1 small text-muted">${dateStr}${suffix}</div>`;
            tr.appendChild(td1);

            const td2 = document.createElement('td');
            const wrap = document.createElement('div');
            wrap.className = 'd-flex flex-wrap';
            slots.forEach(s => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-primary btn-sm time-slot-btn';
                btn.textContent = s.display;
                btn.dataset.date = dateStr;
                btn.dataset.time = s.t24;

                const bookedKey = `${dateStr} ${s.t24}`;
                if (bookedSlots.has(bookedKey)) {
                    btn.classList.add('booked');
                    btn.title = 'محجوز';
                    btn.textContent = s.display + ' (محجوز)';
                }

                if (selectedDate === dateStr && selectedTime === s.t24) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary');
                }

                btn.addEventListener('click', function () {
                    document.querySelectorAll('.time-slot-btn').forEach(b => {
                        b.classList.remove('btn-primary');
                        b.classList.add('btn-outline-primary');
                    });
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary');
                    const label = `${dayNames[dayKey]} ${dateStr}${suffix} - ${s.display}`;
                    updateSelectedSlot(dateStr, s.t24, label);
                });
                wrap.appendChild(btn);
            });
            td2.appendChild(wrap);
            tr.appendChild(td2);

            scheduleTbody.appendChild(tr);
            rowsAdded++;
        }

        if (rowsAdded === 0) {
            showOnly(scheduleNoData);
        } else {
            showOnly(scheduleTableWrapper);
        }
    }

    function loadDoctorData(doctorId) {
        if (!doctorId) {
            doctorSchedules = [];
            bookedSlots = new Set();
            renderWorkingDays();
            showOnly(scheduleEmpty);
            return;
        }

        showOnly(scheduleLoading);

        Promise.all([
            fetch(`/api/doctor/${doctorId}/schedules`).then(r => r.json()).catch(() => ({ schedules: [] })),
            fetch(`/api/doctor/${doctorId}/booked-slots`).then(r => r.json()).catch(() => ({ booked: [] }))
        ]).then(([sched, booked]) => {
            doctorSchedules = sched.schedules || [];
            bookedSlots = new Set((booked.booked || []).map(b => `${b.date} ${b.time}`));
            renderWorkingDays();
            buildScheduleTable();
        });
    }

    function loadClinicsForDoctor(doctorId) {
        if (!doctorId) return;
        fetch(`/api/doctors/${doctorId}/clinics`)
            .then(r => r.json())
            .then(data => {
                clinicSelect.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = data.clinics.length > 0 ? '-- اختر الفرع --' : 'لا توجد فروع مرتبطة بالطبيب';
                clinicSelect.appendChild(placeholder);

                data.clinics.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.dataset.phone = c.phone || '';
                    opt.dataset.address = c.address || '';
                    opt.dataset.city = c.city || '';
                    opt.dataset.workingHours = c.working_hours || '';
                    opt.dataset.isMain = c.is_main ? '1' : '0';
                    let text = c.name;
                    if (c.is_main) text += ' (الرئيسية)';
                    if (c.city) text += ` - ${c.city}`;
                    opt.textContent = text;
                    if (String(c.id) === String(initiallySelectedClinic)) opt.selected = true;
                    clinicSelect.appendChild(opt);
                });

                if (data.clinics.length === 1 && !initiallySelectedClinic) {
                    clinicSelect.value = data.clinics[0].id;
                }
                renderClinicInfo();
            })
            .catch(() => {});
    }

    function renderClinicInfo() {
        const selected = clinicSelect.options[clinicSelect.selectedIndex];
        if (!selected || !selected.value) {
            clinicInfoBox.style.display = 'none';
            clinicInfoBox.innerHTML = '';
            return;
        }
        const phone = selected.dataset.phone;
        const address = selected.dataset.address;
        const city = selected.dataset.city;
        const wh = selected.dataset.workingHours;
        const isMain = selected.dataset.isMain === '1';

        let html = `<strong><i class="fas fa-hospital me-1"></i>${selected.textContent.trim()}</strong>`;
        if (isMain) html += ' <span class="badge bg-primary">الرئيسية</span>';
        if (phone) html += `<div class="clinic-line"><i class="fas fa-phone"></i><span>${phone}</span></div>`;
        if (address) html += `<div class="clinic-line"><i class="fas fa-map-marker-alt"></i><span>${address}${city ? ' - ' + city : ''}</span></div>`;
        else if (city) html += `<div class="clinic-line"><i class="fas fa-map-marker-alt"></i><span>${city}</span></div>`;
        if (wh) html += `<div class="clinic-line"><i class="fas fa-clock"></i><span>${wh}</span></div>`;

        clinicInfoBox.innerHTML = html;
        clinicInfoBox.style.display = 'block';
    }

    doctorSelect.addEventListener('change', function () {
        clearSelection();
        loadDoctorData(this.value);
        loadClinicsForDoctor(this.value);
    });

    if (clinicSelect) {
        clinicSelect.addEventListener('change', renderClinicInfo);
    }

    manualDate.addEventListener('change', function () {
        if (this.value && manualTime.value) {
            updateSelectedSlot(this.value, manualTime.value,
                `إدخال يدوي - ${this.value} ${manualTime.value}`);
        } else {
            appointmentDateInput.value = this.value;
        }
    });
    manualTime.addEventListener('change', function () {
        if (this.value && manualDate.value) {
            updateSelectedSlot(manualDate.value, this.value,
                `إدخال يدوي - ${manualDate.value} ${this.value}`);
        } else {
            appointmentTimeInput.value = this.value;
        }
    });

    processForm.addEventListener('submit', function (e) {
        if (!appointmentDateInput.value || !appointmentTimeInput.value) {
            e.preventDefault();
            alert('من فضلك اختر ميعاد من جدول مواعيد الطبيب أو أدخل التاريخ والوقت يدوياً.');
            return false;
        }
    });

    if (doctorSelect.value) {
        loadDoctorData(doctorSelect.value);
        loadClinicsForDoctor(doctorSelect.value);
    } else {
        showOnly(scheduleEmpty);
    }
});
</script>
@endpush
@endif
@endsection
