<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\User;

class AppointmentInvoiceFactory
{
    /**
     * ينشئ فاتورة (كشف/استشارة) مرتبطة بالموعد إذا لم تكن موجودة.
     */
    public static function ensureUnpaidInvoiceForAppointment(Appointment $appointment, ?int $createdBy = null): ?Invoice
    {
        $appointment->loadMissing('doctor');

        $existing = Invoice::query()->where('appointment_id', $appointment->id)->first();
        if ($existing) {
            return $existing;
        }

        $doctor = $appointment->doctor;
        $type = $appointment->appointment_type ?: 'checkup';
        $fee = $type === 'consultation'
            ? (float) ($doctor?->consultation_fee ?? 0)
            : (float) ($doctor?->checkup_fee ?? 0);

        $actor = $createdBy ?? auth()->id() ?? $appointment->created_by;
        if (! $actor) {
            $actor = User::query()->where('role', 'admin')->value('id');
        }
        if (! $actor) {
            return null;
        }

        return Invoice::create([
            'patient_id' => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'consultation_fee' => $fee,
            'total_amount' => $fee,
            'status' => 'unpaid',
            'created_by' => $actor,
            'clinic_id' => $appointment->clinic_id,
        ]);
    }
}
