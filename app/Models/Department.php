<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function specializations(): HasMany
    {
        return $this->hasMany(Specialization::class);
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(User::class, 'department_id')->where('role', 'doctor');
    }
}
