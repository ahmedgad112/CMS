<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $user->load('schedules');
        
        // Get statistics based on user role
        $stats = $this->getUserStats($user);
        
        return view('profile.show', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = auth()->user();
        $user->load('schedules');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'specialization' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

    private function getUserStats($user)
    {
        $stats = [];

        if ($user->isDoctor()) {
            $stats = [
                'appointments' => \App\Models\Appointment::where('doctor_id', $user->id)->count(),
                'today_appointments' => \App\Models\Appointment::where('doctor_id', $user->id)
                    ->whereDate('appointment_date', today())
                    ->count(),
                'prescriptions' => \App\Models\Prescription::where('doctor_id', $user->id)->count(),
                'completed_appointments' => \App\Models\Appointment::where('doctor_id', $user->id)
                    ->where('status', 'completed')
                    ->count(),
            ];
        } elseif ($user->isReceptionist() || $user->isCallCenter()) {
            $stats = [
                'patients' => \App\Models\Patient::where('created_by', $user->id)->count(),
                'appointments' => \App\Models\Appointment::where('created_by', $user->id)->count(),
            ];
        } elseif ($user->isAccountant()) {
            $stats = [
                'invoices' => \App\Models\Invoice::where('created_by', $user->id)->count(),
                'payments' => \App\Models\Payment::where('received_by', $user->id)->count(),
            ];
        }

        return $stats;
    }

    public function storeSchedule(Request $request)
    {
        $user = auth()->user();

        if (!$user->isDoctor()) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'day_of_week' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Check for time overlap with existing schedules on the same day
        $overlapping = \App\Models\DoctorSchedule::where('doctor_id', $user->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    // New schedule starts during an existing schedule
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })->orWhere(function($q) use ($validated) {
                    // New schedule ends during an existing schedule
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                })->orWhere(function($q) use ($validated) {
                    // New schedule completely contains an existing schedule
                    $q->where('start_time', '>=', $validated['start_time'])
                      ->where('end_time', '<=', $validated['end_time']);
                });
            })
            ->first();

        if ($overlapping) {
            return back()->withErrors(['start_time' => 'هناك تداخل في الأوقات مع موعد موجود في نفس اليوم.']);
        }

        // Check for exact duplicate
        $duplicate = \App\Models\DoctorSchedule::where('doctor_id', $user->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time'])
            ->first();

        if ($duplicate) {
            return back()->withErrors(['start_time' => 'هذا الموعد موجود بالفعل.']);
        }

        \App\Models\DoctorSchedule::create([
            'doctor_id' => $user->id,
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'تم إضافة الموعد بنجاح.');
    }

    public function updateSchedule(Request $request, \App\Models\DoctorSchedule $schedule)
    {
        $user = auth()->user();

        if (!$user->isDoctor() || $schedule->doctor_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'day_of_week' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Check for time overlap with existing schedules on the same day (excluding current schedule)
        $overlapping = \App\Models\DoctorSchedule::where('doctor_id', $user->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $schedule->id)
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    // New schedule starts during an existing schedule
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })->orWhere(function($q) use ($validated) {
                    // New schedule ends during an existing schedule
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                })->orWhere(function($q) use ($validated) {
                    // New schedule completely contains an existing schedule
                    $q->where('start_time', '>=', $validated['start_time'])
                      ->where('end_time', '<=', $validated['end_time']);
                });
            })
            ->first();

        if ($overlapping) {
            return back()->withErrors(['start_time' => 'هناك تداخل في الأوقات مع موعد موجود في نفس اليوم.']);
        }

        // Check for exact duplicate (excluding current schedule)
        $duplicate = \App\Models\DoctorSchedule::where('doctor_id', $user->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time'])
            ->where('id', '!=', $schedule->id)
            ->first();

        if ($duplicate) {
            return back()->withErrors(['start_time' => 'هذا الموعد موجود بالفعل.']);
        }

        $schedule->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'تم تحديث الموعد بنجاح.');
    }

    public function destroySchedule(\App\Models\DoctorSchedule $schedule)
    {
        $user = auth()->user();

        if (!$user->isDoctor() || $schedule->doctor_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $schedule->delete();

        return redirect()->route('profile.show')
            ->with('success', 'تم حذف الموعد بنجاح.');
    }
}

