<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
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
            'schedules' => $doctor->schedules->map(function($schedule) {
                return [
                    'day_of_week' => $schedule->day_of_week,
                    'start_time' => $schedule->start_time->format('H:i'),
                    'end_time' => $schedule->end_time->format('H:i'),
                ];
            })
        ]);
    });

    Route::get('api/doctor/{doctor}/booked-slots', function ($doctorId) {
        $start = now()->startOfDay();
        $end = now()->addDays(30)->endOfDay();
        $slots = \App\Models\Appointment::where('doctor_id', $doctorId)
            ->whereBetween('appointment_date', [$start, $end])
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->map(function ($apt) {
                return [
                    'date' => $apt->appointment_date->format('Y-m-d'),
                    'time' => $apt->appointment_date->format('H:i'),
                ];
            });
        return response()->json(['booked_slots' => $slots]);
    });

    Route::get('api/patients/search', function (Request $request) {
        $q = trim((string) ($request->get('q') ?: $request->get('phone')));
        $id = $request->get('id');

        $query = \App\Models\Patient::query();

        if ($id) {
            $query->where('id', $id);
        } elseif ($q !== '' && mb_strlen($q) >= 2) {
            $term = '%' . $q . '%';
            $query->where(function ($qb) use ($term, $q) {
                $qb->where('full_name', 'like', $term)
                    ->orWhere('phone_number', 'like', $term)
                    ->orWhere('national_id', 'like', $term);
            });
            // ترتيب النتائج: تطابق كامل للهاتف أولاً، ثم الاسم يبدأ بالبحث، ثم الباقي
            $query->orderByRaw(
                "CASE
                    WHEN phone_number = ? THEN 0
                    WHEN phone_number LIKE ? THEN 1
                    WHEN full_name LIKE ? THEN 2
                    WHEN national_id LIKE ? THEN 3
                    ELSE 4
                END",
                [$q, $q . '%', $q . '%', $q . '%']
            );
        } else {
            return response()->json(['patients' => []]);
        }

        $patients = $query->limit(15)
            ->get(['id', 'full_name', 'phone_number', 'national_id', 'gender', 'age']);

        return response()->json(['patients' => $patients]);
    });

    Route::get('api/patients/{id}', function ($id) {
        $patient = \App\Models\Patient::findOrFail($id);
        return response()->json([
            'id' => $patient->id,
            'full_name' => $patient->full_name,
            'phone_number' => $patient->phone_number,
            'gender' => $patient->gender,
            'age' => $patient->age
        ]);
    });

    // Patient Management
    Route::middleware('role:admin,receptionist,call_center')->group(function () {
        Route::resource('patients', PatientController::class)->except(['index', 'show']);
    });
    Route::get('patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

    // Appointment Management
    Route::middleware('role:admin,receptionist,call_center')->group(function () {
        Route::resource('appointments', AppointmentController::class)->except(['index', 'show']);
    });
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');

    // Doctor Routes
    Route::prefix('doctor')->name('doctor.')->middleware('role:doctor,admin')->group(function () {
        Route::resource('prescriptions', PrescriptionController::class);
        Route::get('prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])->name('prescriptions.print');
    });

    // Invoice Management
    Route::middleware('role:admin,receptionist,accountant')->group(function () {
        Route::resource('invoices', InvoiceController::class)->except(['show']);
        Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    });
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

    // Payment Management
    Route::middleware('role:admin,accountant')->group(function () {
        Route::resource('payments', PaymentController::class);
    });


    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('users', AdminUserController::class);
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
        Route::resource('specializations', \App\Http\Controllers\Admin\SpecializationController::class);
        Route::resource('role-permissions', \App\Http\Controllers\Admin\RolePermissionController::class)->parameters([
            'role-permissions' => 'role'
        ]);
        Route::get('users/{user}/permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'userPermissions'])->name('users.permissions');
        Route::put('users/{user}/permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'updateUserPermissions'])->name('users.permissions.update');
    });

    // API Routes for departments and specializations
    Route::get('api/departments/{department}/specializations', function ($departmentId) {
        $specializations = \App\Models\Specialization::where('department_id', $departmentId)
            ->where('is_active', true)
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
