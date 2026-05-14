<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientRegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Patient Self-Registration (no auth required)
Route::get('/register', [PatientRegistrationController::class, 'showForm'])->name('registration.form');
Route::post('/register', [PatientRegistrationController::class, 'register'])->name('registration.register');
Route::get('/register/service', [PatientRegistrationController::class, 'showService'])->name('registration.service');
Route::post('/register/service', [PatientRegistrationController::class, 'submitService'])->name('registration.service.submit');
Route::get('/register/done/{request}', [PatientRegistrationController::class, 'done'])->name('registration.done');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Clinic Switcher (admin only)
    Route::post('clinic/switch', [\App\Http\Controllers\ClinicSwitcherController::class, 'switch'])->name('clinic.switch');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profile Routes
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Doctor Schedule Routes (only for doctors)
    Route::middleware('role:doctor')->group(function () {
        Route::post('profile/schedules', [\App\Http\Controllers\ProfileController::class, 'storeSchedule'])->name('profile.schedules.store');
        Route::put('profile/schedules/{schedule}', [\App\Http\Controllers\ProfileController::class, 'updateSchedule'])->name('profile.schedules.update');
        Route::delete('profile/schedules/{schedule}', [\App\Http\Controllers\ProfileController::class, 'destroySchedule'])->name('profile.schedules.destroy');
    });

    // Doctors Management
    Route::get('doctors', [\App\Http\Controllers\DoctorController::class, 'index'])->name('doctors.index');
    Route::get('doctors/{doctor}', [\App\Http\Controllers\DoctorController::class, 'show'])->name('doctors.show');

    // API Routes
    Route::get('api/doctor/{doctor}/schedules', function ($doctorId) {
        $doctor = \App\Models\User::with('schedules')->findOrFail($doctorId);

        return response()->json([
            'schedules' => $doctor->schedules->map(function ($schedule) {
                return [
                    'day_of_week' => $schedule->day_of_week,
                    'start_time' => $schedule->start_time->format('H:i'),
                    'end_time' => $schedule->end_time->format('H:i'),
                ];
            }),
        ]);
    });

    Route::get('api/patients/search', function (Request $request) {
        $phone = $request->get('phone');
        $id = $request->get('id');

        $query = \App\Models\Patient::query();

        if ($id) {
            $query->where('id', $id);
        } elseif ($phone && strlen($phone) >= 2) {
            $query->where(function ($q) use ($phone) {
                $q->where('phone_number', 'like', "%{$phone}%")
                    ->orWhere('full_name', 'like', "%{$phone}%");
            });
        } else {
            return response()->json(['patients' => []]);
        }

        $patients = $query->limit(10)
            ->get(['id', 'full_name', 'phone_number', 'gender', 'age']);

        return response()->json(['patients' => $patients]);
    });

    Route::get('api/patients/{id}', function ($id) {
        $patient = \App\Models\Patient::findOrFail($id);

        return response()->json([
            'id' => $patient->id,
            'full_name' => $patient->full_name,
            'phone_number' => $patient->phone_number,
            'gender' => $patient->gender,
            'age' => $patient->age,
        ]);
    });

    // Patient Management
    Route::resource('patients', PatientController::class)->except(['index', 'show']);
    Route::get('patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

    // Appointment Management
    Route::resource('appointments', AppointmentController::class)->except(['index', 'show']);
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');

    // Patient appointment requests (from public registration)
    Route::prefix('appointment-requests')->name('appointment-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AppointmentRequestController::class, 'index'])->name('index');
        Route::get('/{appointmentRequest}', [\App\Http\Controllers\AppointmentRequestController::class, 'show'])->name('show');
        Route::post('/{appointmentRequest}/process', [\App\Http\Controllers\AppointmentRequestController::class, 'process'])->name('process');
        Route::post('/{appointmentRequest}/cancel', [\App\Http\Controllers\AppointmentRequestController::class, 'cancel'])->name('cancel');
    });

    // Doctor Routes
    Route::prefix('doctor')->name('doctor.')->group(function () {
        Route::resource('prescriptions', PrescriptionController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])->name('prescriptions.print');
    });

    // Invoice Management
    Route::resource('invoices', InvoiceController::class)->except(['show', 'destroy']);
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

    // Payment Management
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'show']);

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('users', AdminUserController::class);
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
        Route::resource('specializations', \App\Http\Controllers\Admin\SpecializationController::class);
        Route::resource('clinics', \App\Http\Controllers\Admin\ClinicController::class);
        Route::resource('role-permissions', \App\Http\Controllers\Admin\RolePermissionController::class)->parameters([
            'role-permissions' => 'role',
        ]);
        Route::get('users/{user}/permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'userPermissions'])->name('users.permissions');
        Route::put('users/{user}/permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'updateUserPermissions'])->name('users.permissions.update');
    });

    // API: Get doctors of a specific clinic
    Route::get('api/clinics/{clinic}/doctors', function ($clinicId) {
        $clinic = \App\Models\Clinic::with(['doctors' => function ($q) {
            $q->where('is_active', true)
                ->with(['department:id,name', 'specialization:id,name']);
        }])->findOrFail($clinicId);

        return response()->json([
            'doctors' => $clinic->doctors->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'department' => $doctor->department?->name,
                    'specialization' => $doctor->specialization?->name,
                ];
            }),
        ]);
    });

    // API: Get booked appointment slots for a doctor in the next N days
    Route::get('api/doctor/{doctor}/booked-slots', function ($doctorId) {
        $bookedAppointments = \App\Models\Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', now())
            ->where('appointment_date', '<=', now()->addDays(60))
            ->get(['appointment_date']);

        return response()->json([
            'booked' => $bookedAppointments->map(function ($appt) {
                $dt = \Carbon\Carbon::parse($appt->appointment_date);

                return [
                    'date' => $dt->format('Y-m-d'),
                    'time' => $dt->format('H:i'),
                ];
            }),
        ]);
    });

    // API: Get clinics of a specific doctor
    Route::get('api/doctors/{doctor}/clinics', function ($doctorId) {
        $doctor = \App\Models\User::with(['clinics' => function ($q) {
            $q->where('is_active', true);
        }])->findOrFail($doctorId);

        return response()->json([
            'clinics' => $doctor->clinics->map(function ($clinic) {
                return [
                    'id' => $clinic->id,
                    'name' => $clinic->name,
                    'phone' => $clinic->phone,
                    'address' => $clinic->address,
                    'city' => $clinic->city,
                    'working_hours' => $clinic->working_hours,
                    'is_main' => $clinic->is_main,
                ];
            }),
        ]);
    });

    // API Routes for departments and specializations
    Route::get('api/departments/{department}/specializations', function ($departmentId) {
        $specializations = \App\Models\Specialization::where('department_id', '=', $departmentId, 'and')
            ->where('is_active', '=', true, 'and')
            ->get(['id', 'name']);

        return response()->json(['specializations' => $specializations]);
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/doctor-performance', [ReportController::class, 'doctorPerformance'])->name('doctor-performance');
    });
});
