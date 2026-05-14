<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\PlatformSetting;
use App\Observers\AppointmentObserver;
use App\Support\ClinicContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider; // موجودة تمام

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // السطر ده هو اللي هيحل مشكلة الـ 1071 Specified key was too long
        Schema::defaultStringLength(191);

        Appointment::observe(AppointmentObserver::class);

        Blade::if('perm', function (?string $slug = null): bool {
            if ($slug === null || $slug === '') {
                return false;
            }

            $user = Auth::user();

            return $user !== null && $user->hasPermission($slug);
        });

        // Share clinic context with all views (for navbar switcher + indicators)
        View::composer('*', function ($view) {
            $platformOrganizationName = PlatformSetting::getValue('organization_display_name') ?: config('app.name');
            $view->with('platformOrganizationName', $platformOrganizationName);

            if (Auth::check()) {
                $canSwitchClinic = ClinicContext::canSwitch();
                $view->with('currentClinic', ClinicContext::current());
                $view->with('canSwitchClinic', $canSwitchClinic);
                $view->with('availableClinics', $canSwitchClinic
                    ? Clinic::query()->active()->orderByDesc('is_main')->orderBy('name')->get()
                    : collect());
            }
        });
    }
}
