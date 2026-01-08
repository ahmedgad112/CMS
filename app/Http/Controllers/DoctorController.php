<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'doctor');

        // البحث بالاسم
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // البحث بالقسم
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // البحث بالتخصص
        if ($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $doctors = $query->with(['department', 'specialization'])
            ->withCount(['doctorAppointments', 'prescriptions'])
            ->latest()
            ->paginate(15);

        // الحصول على جميع الأقسام والتخصصات للفلترة
        $departments = \App\Models\Department::where('is_active', true)->get();
        $specializations = \App\Models\Specialization::where('is_active', true)->get();

        return view('doctors.index', compact('doctors', 'departments', 'specializations'));
    }

    public function show(User $doctor)
    {
        // التأكد من أن المستخدم طبيب
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $doctor->load(['department', 'specialization']);
        $doctor->loadCount(['doctorAppointments', 'prescriptions']);
        
        // إحصائيات إضافية
        $stats = [
            'total_appointments' => $doctor->doctorAppointments()->count(),
            'confirmed_appointments' => $doctor->doctorAppointments()->where('status', 'confirmed')->count(),
            'completed_appointments' => $doctor->doctorAppointments()->where('status', 'completed')->count(),
            'total_prescriptions' => $doctor->prescriptions()->count(),
        ];

        return view('doctors.show', compact('doctor', 'stats'));
    }
}
