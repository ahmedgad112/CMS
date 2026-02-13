@extends('layouts.app')

@section('title', 'إضافة موعد جديد')
@section('page-title', 'إضافة موعد جديد')

@section('content')
    <style>
        #patient_search_results {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        #patient_search_results .list-group-item {
            border: none;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        #patient_search_results .list-group-item:hover {
            background-color: #f8f9fa;
        }

        #patient_search_results .list-group-item:last-child {
            border-bottom: none;
        }

        #appointment-schedule-table {
            margin-bottom: 0;
        }

        #appointment-schedule-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .time-slot-btn {
            transition: all 0.2s;
            min-width: 80px;
        }

        .time-slot-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .time-slot-btn.btn-primary {
            font-weight: bold;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">إضافة موعد جديد</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="patient_search" class="form-label">البحث عن المريض <span
                                class="text-danger">*</span></label>
                        <div class="d-flex gap-2 mb-2">
                            <div class="position-relative flex-grow-1">
                                <input type="text" class="form-control pe-4 @error('patient_id') is-invalid @enderror"
                                    id="patient_search" placeholder="ابحث بالاسم أو رقم الهاتف أو رقم الهوية..." autocomplete="off">
                                <span id="patient_search_loading" class="position-absolute top-50 translate-middle-y text-muted" style="display: none; left: 12px;">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                                <input type="hidden" id="patient_id" name="patient_id"
                                    value="{{ old('patient_id', request('patient_id')) }}" required>
                                <div id="patient_search_results"
                                    class="position-absolute w-100 bg-white border rounded shadow-lg"
                                    style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto; top: 100%; margin-top: 2px;">
                                </div>
                            </div>
                            <a href="{{ route('patients.create', ['return_to' => route('appointments.create')]) }}"
                                class="btn btn-success" title="إضافة مريض جديد">
                                <i class="fas fa-user-plus me-1"></i>
                                <span class="d-none d-md-inline">مريض جديد</span>
                            </a>
                        </div>
                        <div id="selected_patient" class="mt-2" style="display: none;">
                            <div class="alert alert-info d-flex justify-content-between align-items-center mb-0">
                                <div>
                                    <i class="fas fa-user-check me-2"></i>
                                    <strong id="selected_patient_name"></strong>
                                    <span class="text-muted ms-2" id="selected_patient_phone"></span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="clear_patient">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @error('patient_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            اكتب حرفين على الأقل (الاسم، رقم الهاتف، أو رقم الهوية) أو أضف مريضاً جديداً
                        </small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="doctor_id" class="form-label">الطبيب <span class="text-danger">*</span></label>
                        <select class="form-select @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id"
                            required>
                            <option value="">اختر الطبيب</option>
                            @foreach ($doctors as $doctor)
                                @php
                                    $departmentName =
                                        $doctor->department && is_object($doctor->department)
                                            ? $doctor->department->name
                                            : '';
                                    $specializationName = null;
                                    if ($doctor->specialization_id) {
                                        if (
                                            $doctor->relationLoaded('specialization') &&
                                            $doctor->specialization &&
                                            is_object($doctor->specialization)
                                        ) {
                                            $specializationName = $doctor->specialization->name;
                                        }
                                    }
                                    if (
                                        !$specializationName &&
                                        !empty($doctor->specialization) &&
                                        is_string($doctor->specialization)
                                    ) {
                                        $specializationName = $doctor->specialization;
                                    }

                                    $displayText = $doctor->name;
                                    if ($departmentName || $specializationName) {
                                        $displayText .= ' - ';
                                        if ($departmentName && $specializationName) {
                                            $displayText .= $departmentName . ' / ' . $specializationName;
                                        } elseif ($departmentName) {
                                            $displayText .= $departmentName;
                                        } elseif ($specializationName) {
                                            $displayText .= $specializationName;
                                        }
                                    }
                                @endphp
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $displayText }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            سيتم عرض القسم والتخصص بجانب اسم الطبيب
                        </small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label mb-3">
                            <i class="fas fa-calendar-alt text-primary me-1"></i> اختر التاريخ والوقت <span
                                class="text-danger">*</span>
                        </label>
                        <input type="hidden" id="appointment_date" name="appointment_date"
                            value="{{ old('appointment_date') }}" required>
                        <input type="hidden" id="appointment_time" name="appointment_time"
                            value="{{ old('appointment_time') }}" required>

                        <div id="appointment-schedule-container" class="border rounded p-3 bg-light" style="display: none;">
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                اختر الطبيب أولاً لعرض المواعيد المتاحة
                            </div>
                        </div>

                        <div id="appointment-schedule-table-container" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="appointment-schedule-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 200px;">التاريخ</th>
                                            <th>الأوقات المتاحة</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appointment-schedule-tbody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <div class="alert alert-success" id="selected-slot-info" style="display: none;">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>تم اختيار:</strong> <span id="selected-slot-text"></span>
                                </div>
                            </div>
                        </div>

                        @error('appointment_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('appointment_time')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="appointment_type" class="form-label">نوع الموعد <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('appointment_type') is-invalid @enderror" id="appointment_type"
                            name="appointment_type" required>
                            <option value="">اختر نوع الموعد</option>
                            <option value="checkup" {{ old('appointment_type') == 'checkup' ? 'selected' : '' }}>كشف
                            </option>
                            <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>
                                استشارة</option>
                        </select>
                        @error('appointment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Patient search functionality
                const patientSearch = document.getElementById('patient_search');
                const patientIdInput = document.getElementById('patient_id');
                const patientSearchResults = document.getElementById('patient_search_results');
                const selectedPatientDiv = document.getElementById('selected_patient');
                const selectedPatientName = document.getElementById('selected_patient_name');
                const selectedPatientPhone = document.getElementById('selected_patient_phone');
                const clearPatientBtn = document.getElementById('clear_patient');
                let searchTimeout;

                // Load selected patient if exists
                @if (old('patient_id', request('patient_id')))
                    const selectedPatientId = {{ old('patient_id', request('patient_id')) }};
                    fetch(`/api/patients/${selectedPatientId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                selectPatient(data);
                            }
                        })
                        .catch(error => {
                            console.error('Error loading patient:', error);
                        });
                @endif

                function selectPatient(patient) {
                    patientIdInput.value = patient.id;
                    selectedPatientName.textContent = patient.full_name;
                    selectedPatientPhone.textContent = `(${patient.phone_number})`;
                    selectedPatientDiv.style.display = 'block';
                    patientSearch.value = '';
                    patientSearchResults.style.display = 'none';
                }

                function clearPatient() {
                    patientIdInput.value = '';
                    selectedPatientDiv.style.display = 'none';
                    patientSearch.value = '';
                    patientSearchResults.style.display = 'none';
                }

                const patientSearchLoading = document.getElementById('patient_search_loading');

                patientSearch.addEventListener('input', function() {
                    const query = this.value.trim();

                    clearTimeout(searchTimeout);

                    if (query.length < 2) {
                        patientSearchResults.style.display = 'none';
                        patientSearchLoading.style.display = 'none';
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        patientSearchLoading.style.display = 'block';
                        patientSearchResults.style.display = 'none';

                        fetch(`/api/patients/search?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                patientSearchLoading.style.display = 'none';
                                if (data.patients && data.patients.length > 0) {
                                    let html = '<div class="list-group list-group-flush">';
                                    data.patients.forEach(patient => {
                                        const genderIcon = patient.gender === 'male' ?
                                            'mars' : 'venus';
                                        const genderText = patient.gender === 'male' ?
                                            'ذكر' : 'أنثى';
                                        const nationalIdPart = patient.national_id ?
                                            `<span class="ms-2"><i class="fas fa-id-card me-1"></i>${patient.national_id}</span>` : '';
                                        html += `
                                <a href="#" class="list-group-item list-group-item-action" data-patient-id="${patient.id}" data-patient-name="${escapeHtml(patient.full_name)}" data-patient-phone="${escapeHtml(patient.phone_number)}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-${genderIcon} me-2 text-primary"></i>
                                            <strong>${escapeHtml(patient.full_name)}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>${escapeHtml(patient.phone_number)}
                                                ${nationalIdPart}
                                                <span class="ms-2">
                                                    <i class="fas fa-birthday-cake me-1"></i>${patient.age || '-'} سنة
                                                    <span class="ms-2">${genderText}</span>
                                                </span>
                                            </small>
                                        </div>
                                        <i class="fas fa-chevron-left text-muted"></i>
                                    </div>
                                </a>
                            `;
                                    });
                                    html += '</div>';
                                    patientSearchResults.innerHTML = html;
                                    patientSearchResults.style.display = 'block';

                                    patientSearchResults.querySelectorAll('.list-group-item')
                                        .forEach(item => {
                                            item.addEventListener('click', function(e) {
                                                e.preventDefault();
                                                selectPatient({
                                                    id: this.dataset.patientId,
                                                    full_name: this.dataset.patientName,
                                                    phone_number: this.dataset.patientPhone
                                                });
                                            });
                                        });
                                } else {
                                    patientSearchResults.innerHTML =
                                        '<div class="p-3 text-center text-muted"><i class="fas fa-search me-1"></i>لا توجد نتائج، جرب كلمات أخرى أو أضف مريضاً جديداً</div>';
                                    patientSearchResults.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                patientSearchLoading.style.display = 'none';
                                patientSearchResults.innerHTML =
                                    '<div class="p-3 text-center text-danger"><i class="fas fa-exclamation-triangle me-1"></i>حدث خطأ في البحث، حاول مرة أخرى</div>';
                                patientSearchResults.style.display = 'block';
                            });
                    }, 350);
                });

                function escapeHtml(text) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                clearPatientBtn.addEventListener('click', clearPatient);

                // Hide results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!patientSearch.contains(e.target) && !patientSearchResults.contains(e.target)) {
                        patientSearchResults.style.display = 'none';
                    }
                });

                // Doctor schedules functionality
                const doctorSelect = document.getElementById('doctor_id');
                const appointmentDateInput = document.getElementById('appointment_date');
                const appointmentTimeInput = document.getElementById('appointment_time');
                const scheduleContainer = document.getElementById('appointment-schedule-container');
                const scheduleTableContainer = document.getElementById('appointment-schedule-table-container');
                const scheduleTbody = document.getElementById('appointment-schedule-tbody');
                const selectedSlotInfo = document.getElementById('selected-slot-info');
                const selectedSlotText = document.getElementById('selected-slot-text');
    let doctorSchedules = [];
    let bookedSlots = [];
    let selectedDate = null;
    let selectedTime = null;

                const dayNames = {
                    'saturday': 'السبت',
                    'sunday': 'الأحد',
                    'monday': 'الإثنين',
                    'tuesday': 'الثلاثاء',
                    'wednesday': 'الأربعاء',
                    'thursday': 'الخميس',
                    'friday': 'الجمعة'
                };

                function getDayName(date) {
                    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                    return days[date.getDay()];
                }

                function formatDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }

                function formatTime(hour, minute) {
                    const period = hour >= 12 ? 'م' : 'ص';
                    const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
                    return `${String(displayHour).padStart(2, '0')}:${String(minute).padStart(2, '0')} ${period}`;
                }

                function formatTimeForValue(hour, minute) {
                    return `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                }

                function generateScheduleTable(doctorId) {
                    if (!doctorId || doctorSchedules.length === 0) {
                        scheduleContainer.style.display = 'block';
                        scheduleTableContainer.style.display = 'none';
                        selectedSlotInfo.style.display = 'none';
                        return;
                    }

                    // Get unique days of week from schedules
                    const availableDays = [...new Set(doctorSchedules.map(s => s.day_of_week))];

                    if (availableDays.length === 0) {
                        scheduleContainer.innerHTML =
                            '<div class="alert alert-warning mb-0"><i class="fas fa-exclamation-triangle me-2"></i>لا توجد أيام متاحة لهذا الطبيب</div>';
                        scheduleContainer.style.display = 'block';
                        scheduleTableContainer.style.display = 'none';
                        selectedSlotInfo.style.display = 'none';
                        return;
                    }

                    scheduleContainer.style.display = 'none';
                    scheduleTableContainer.style.display = 'block';
                    scheduleTbody.innerHTML = '';

                    const today = new Date();
                    const scheduleData = [];

                    // Generate dates for next 30 days
                    for (let i = 0; i < 30; i++) {
                        const date = new Date(today);
                        date.setDate(today.getDate() + i);
                        const dayOfWeek = getDayName(date);

                        // Check if this day is in the doctor's schedule
                        if (availableDays.includes(dayOfWeek)) {
                            const dateStr = formatDate(date);
                            const dayName = dayNames[dayOfWeek];
                            const isToday = i === 0;
                            const isTomorrow = i === 1;

                            // Get time slots for this day
                            const daySchedules = doctorSchedules.filter(s => s.day_of_week === dayOfWeek);
                            const timeSlots = [];

                            daySchedules.forEach(schedule => {
                                const startTime = schedule.start_time;
                                const endTime = schedule.end_time;

                                const [startHour, startMin] = startTime.split(':').map(Number);
                                const [endHour, endMin] = endTime.split(':').map(Number);
                                const startMinutes = startHour * 60 + startMin;
                                const endMinutes = endHour * 60 + endMin;

                                for (let minutes = startMinutes; minutes < endMinutes; minutes += 30) {
                                    const slotHour = Math.floor(minutes / 60);
                                    const slotMin = minutes % 60;
                                    const timeValue = formatTimeForValue(slotHour, slotMin);
                                    const timeDisplay = formatTime(slotHour, slotMin);

                                    // Check if slot is in the future (if today)
                                    if (isToday) {
                                        const now = new Date();
                                        const slotTime = new Date();
                                        slotTime.setHours(slotHour, slotMin, 0, 0);
                                        if (slotTime <= now) {
                                            continue; // Skip past times
                                        }
                                    }

                                    const isBooked = bookedSlots.some(s => s.date === dateStr && s.time === timeValue);
                                    if (!isBooked) {
                                        timeSlots.push({
                                            value: timeValue,
                                            display: timeDisplay
                                        });
                                    }
                                }
                            });

                // Remove duplicates and sort
                            const uniqueTimeSlots = [];
                            const seenValues = new Set();

                            timeSlots.forEach(slot => {
                                if (!seenValues.has(slot.value)) {
                                    seenValues.add(slot.value);
                                    uniqueTimeSlots.push(slot);
                                }
                            });

                            uniqueTimeSlots.sort((a, b) => a.value.localeCompare(b.value));

                            if (uniqueTimeSlots.length > 0) {
                                let dateLabel = `${dayName} ${dateStr}`;
                                if (isToday) {
                                    dateLabel += ' (اليوم)';
                                } else if (isTomorrow) {
                                    dateLabel += ' (غداً)';
                                }

                                scheduleData.push({
                                    date: dateStr,
                                    dateLabel: dateLabel,
                                    dayOfWeek: dayOfWeek,
                                    timeSlots: uniqueTimeSlots
                                });
                            }
                        }
                    }

                    // Generate table rows
                    scheduleData.forEach(item => {
                        const row = document.createElement('tr');

                        const dateCell = document.createElement('td');
                        dateCell.className = 'align-middle fw-semibold';
                        dateCell.style.width = '200px';
                        dateCell.textContent = item.dateLabel;
                        row.appendChild(dateCell);

                        const timeCell = document.createElement('td');
                        timeCell.className = 'align-middle';

                        const timeContainer = document.createElement('div');
                        timeContainer.className = 'd-flex flex-wrap gap-2';

                        item.timeSlots.forEach(slot => {
                            const timeBtn = document.createElement('button');
                            timeBtn.type = 'button';
                            timeBtn.className = 'btn btn-outline-primary btn-sm time-slot-btn';
                            timeBtn.textContent = slot.display;
                            timeBtn.dataset.date = item.date;
                            timeBtn.dataset.time = slot.value;
                            timeBtn.dataset.display = `${item.dateLabel} - ${slot.display}`;

                            // Check if this slot is selected
                            if (selectedDate === item.date && selectedTime === slot.value) {
                                timeBtn.classList.remove('btn-outline-primary');
                                timeBtn.classList.add('btn-primary');
                            }

                            timeBtn.addEventListener('click', function() {
                                // Remove previous selection
                                document.querySelectorAll('.time-slot-btn').forEach(btn => {
                                    btn.classList.remove('btn-primary');
                                    btn.classList.add('btn-outline-primary');
                                });

                                // Select this slot
                                this.classList.remove('btn-outline-primary');
                                this.classList.add('btn-primary');

                                // Update hidden inputs
                                selectedDate = this.dataset.date;
                                selectedTime = this.dataset.time;
                                appointmentDateInput.value = selectedDate;
                                appointmentTimeInput.value = selectedTime;

                                // Update selected slot info
                                selectedSlotText.textContent = this.dataset.display;
                                selectedSlotInfo.style.display = 'block';
                            });

                            timeContainer.appendChild(timeBtn);
                        });

                        timeCell.appendChild(timeContainer);
                        row.appendChild(timeCell);

                        scheduleTbody.appendChild(row);
                    });

                    if (scheduleData.length === 0) {
                        scheduleTbody.innerHTML =
                            '<tr><td colspan="2" class="text-center text-muted">لا توجد مواعيد متاحة في الأيام القادمة</td></tr>';
                    }
                }

                function loadDoctorSchedules(doctorId) {
                    if (!doctorId) {
                        doctorSchedules = [];
                        bookedSlots = [];
                        scheduleContainer.style.display = 'block';
                        scheduleTableContainer.style.display = 'none';
                        selectedSlotInfo.style.display = 'none';
                        selectedDate = null;
                        selectedTime = null;
                        appointmentDateInput.value = '';
                        appointmentTimeInput.value = '';
                        return;
                    }

                    // Fetch doctor schedules and booked slots in parallel
                    Promise.all([
                        fetch(`/api/doctor/${doctorId}/schedules`).then(r => r.json()),
                        fetch(`/api/doctor/${doctorId}/booked-slots`).then(r => r.json())
                    ])
                        .then(([schedulesData, bookedData]) => {
                            doctorSchedules = schedulesData.schedules || [];
                            bookedSlots = bookedData.booked_slots || [];
                            generateScheduleTable(doctorId);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            doctorSchedules = [];
                            bookedSlots = [];
                            scheduleContainer.innerHTML =
                                '<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>حدث خطأ في جلب المواعيد</div>';
                            scheduleContainer.style.display = 'block';
                            scheduleTableContainer.style.display = 'none';
                            selectedSlotInfo.style.display = 'none';
                        });
                }

                doctorSelect.addEventListener('change', function() {
                    selectedDate = null;
                    selectedTime = null;
                    appointmentDateInput.value = '';
                    appointmentTimeInput.value = '';
                    selectedSlotInfo.style.display = 'none';
                    loadDoctorSchedules(this.value);
                });

                // Load schedules if doctor is pre-selected
                if (doctorSelect.value) {
                    loadDoctorSchedules(doctorSelect.value);
                }
            });
        </script>
    @endpush
@endsection
