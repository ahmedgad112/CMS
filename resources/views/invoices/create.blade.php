@extends('layouts.app')

@section('title', 'إنشاء فاتورة')
@section('page-title', 'إنشاء فاتورة')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">إنشاء فاتورة جديدة</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="patient_search" class="form-label">البحث عن المريض <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control @error('patient_id') is-invalid @enderror" 
                               id="patient_search" 
                               placeholder="ابحث برقم الهاتف أو الاسم..."
                               autocomplete="off">
                        <input type="hidden" 
                               id="patient_id" 
                               name="patient_id" 
                               value="{{ old('patient_id', request('patient_id')) }}"
                               required>
                        <div id="patient_search_results" class="position-absolute w-100 bg-white border rounded shadow-lg" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto; top: 100%; margin-top: 2px;"></div>
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
                        ابدأ بكتابة رقم الهاتف أو الاسم للبحث
                    </small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="appointment_id" class="form-label">الموعد (اختياري)</label>
                    <select class="form-select @error('appointment_id') is-invalid @enderror" 
                            id="appointment_id" 
                            name="appointment_id">
                        <option value="">اختر الموعد</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                {{ $appointment->patient->full_name }} - {{ $appointment->appointment_date->format('Y-m-d H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="consultation_fee" class="form-label">رسوم الكشف <span class="text-danger">*</span></label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           class="form-control @error('consultation_fee') is-invalid @enderror" 
                           id="consultation_fee" 
                           name="consultation_fee" 
                           value="{{ old('consultation_fee', 0) }}" 
                           required
                           oninput="calculateTotal()">
                    @error('consultation_fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="total_amount" class="form-label">المبلغ الإجمالي</label>
                    <input type="number" 
                           step="0.01" 
                           class="form-control" 
                           id="total_amount" 
                           name="total_amount" 
                           readonly
                           value="{{ old('total_amount', 0) }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
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
    function calculateTotal() {
        const consultationFee = parseFloat(document.getElementById('consultation_fee').value) || 0;
        document.getElementById('total_amount').value = consultationFee.toFixed(2);
    }

    // Calculate on page load
    calculateTotal();

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
    @if(old('patient_id', request('patient_id')))
        const selectedPatientId = {{ old('patient_id', request('patient_id')) }};
        fetch(`/api/patients/search?phone=&id=${selectedPatientId}`)
            .then(response => response.json())
            .then(data => {
                if (data.patients && data.patients.length > 0) {
                    const patient = data.patients[0];
                    selectPatient(patient);
                }
            });
    @endif

    function selectPatient(patient) {
        patientIdInput.value = patient.id;
        selectedPatientName.textContent = patient.full_name;
        selectedPatientPhone.textContent = patient.phone_number;
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

    patientSearch.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            patientSearchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/api/patients/search?phone=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.patients && data.patients.length > 0) {
                        let html = '<div class="list-group list-group-flush">';
                        data.patients.forEach(patient => {
                            const genderIcon = patient.gender === 'male' ? 'mars' : 'venus';
                            const genderText = patient.gender === 'male' ? 'ذكر' : 'أنثى';
                            html += `
                                <a href="#" class="list-group-item list-group-item-action" data-patient-id="${patient.id}" data-patient-name="${patient.full_name}" data-patient-phone="${patient.phone_number}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-${genderIcon} me-2 text-primary"></i>
                                            <strong>${patient.full_name}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>${patient.phone_number}
                                                <span class="ms-2">
                                                    <i class="fas fa-birthday-cake me-1"></i>${patient.age} سنة
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

                        // Add click handlers
                        patientSearchResults.querySelectorAll('.list-group-item').forEach(item => {
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                const patient = {
                                    id: this.dataset.patientId,
                                    full_name: this.dataset.patientName,
                                    phone_number: this.dataset.patientPhone
                                };
                                selectPatient(patient);
                            });
                        });
                    } else {
                        patientSearchResults.innerHTML = '<div class="p-3 text-center text-muted">لا توجد نتائج</div>';
                        patientSearchResults.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    patientSearchResults.innerHTML = '<div class="p-3 text-center text-danger">حدث خطأ في البحث</div>';
                    patientSearchResults.style.display = 'block';
                });
        }, 300);
    });

    clearPatientBtn.addEventListener('click', clearPatient);

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!patientSearch.contains(e.target) && !patientSearchResults.contains(e.target)) {
            patientSearchResults.style.display = 'none';
        }
    });
</script>
@endpush
@endsection

