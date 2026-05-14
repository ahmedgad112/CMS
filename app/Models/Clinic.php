<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'phone',
        'email',
        'address',
        'city',
        'description',
        'working_hours',
        'is_main',
        'is_active',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'clinic_user', 'clinic_id', 'user_id')
            ->where('role', 'doctor')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'clinic_user', 'clinic_id', 'user_id')
            ->withTimestamps();
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function appointmentRequests(): HasMany
    {
        return $this->hasMany(AppointmentRequest::class, 'preferred_clinic_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
