<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'role_permissions',
            'permission_id',
            'role',
            'id',
            'role'
        )->wherePivot('role', '!=', null);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('role', $role)->exists();
    }
}
