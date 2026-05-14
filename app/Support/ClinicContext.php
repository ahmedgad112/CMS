<?php

namespace App\Support;

use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ClinicContext
{
    public const SESSION_KEY = 'current_clinic_id';

    /**
     * Get the active clinic ID for the current request.
     *
     * Logic:
     * - Branch users (non-admin with assigned clinic_id) → always their own clinic.
     * - Admin → optional selected clinic from session, or null (= all clinics).
     * - Guest / no clinic → null.
     */
    public static function currentId(): ?int
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        // Non-admin staff with a clinic assignment: locked to their clinic
        if (! $user->isAdmin() && $user->clinic_id) {
            return (int) $user->clinic_id;
        }

        // Admin: can switch clinic via session
        if ($user->isAdmin()) {
            $sessionId = Session::get(self::SESSION_KEY);
            if ($sessionId && Clinic::query()->whereKey($sessionId)->exists()) {
                return (int) $sessionId;
            }
        }

        return null;
    }

    /**
     * Get the active clinic model (or null when viewing all clinics).
     */
    public static function current(): ?Clinic
    {
        $id = self::currentId();
        if (! $id) {
            return null;
        }

        return Clinic::query()->find($id);
    }

    /**
     * True when the request is currently scoped to a single clinic.
     */
    public static function isScoped(): bool
    {
        return self::currentId() !== null;
    }

    /**
     * True if the current user can switch clinics (admins only).
     */
    public static function canSwitch(): bool
    {
        $user = Auth::user();

        return $user && $user->isAdmin();
    }

    /**
     * Set the admin's selected clinic (or clear it).
     */
    public static function setCurrent(?int $clinicId): void
    {
        if ($clinicId === null) {
            Session::forget(self::SESSION_KEY);

            return;
        }

        if (Clinic::query()->whereKey($clinicId)->exists()) {
            Session::put(self::SESSION_KEY, $clinicId);
        }
    }

    /**
     * Clear the admin's selected clinic (show all clinics).
     */
    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
