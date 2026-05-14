<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'guest_payload',
        'service_type',
        'specialization_id',
        'preferred_doctor_id',
        'preferred_clinic_id',
        'status',
        'notes',
        'processed_by',
        'appointment_id',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'guest_payload' => 'array',
    ];

    /**
     * Patient row after confirmation, or guest data from public form before a row exists.
     */
    public function displayPatient(): Patient|\stdClass
    {
        if ($this->patient_id) {
            $p = $this->relationLoaded('patient') ? $this->patient : $this->patient()->first();
            if ($p) {
                return $p;
            }
        }

        $g = $this->guest_payload ?? [];

        return (object) [
            'full_name' => $g['full_name'] ?? '—',
            'phone_number' => $g['phone_number'] ?? '—',
            'gender' => $g['gender'] ?? 'male',
            'age' => $g['age'] ?? 0,
            'medical_history' => $g['medical_history'] ?? null,
            'chronic_diseases' => $g['chronic_diseases'] ?? null,
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }

    public function preferredDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'preferred_doctor_id');
    }

    public function preferredClinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'preferred_clinic_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return $this->service_type === 'consultation' ? 'استشارة' : 'كشف جديد';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'processed' => 'تم الحجز',
            'canceled' => 'ملغي',
            default => $this->status,
        };
    }
}
