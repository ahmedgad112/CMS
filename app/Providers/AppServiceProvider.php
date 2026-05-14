<?php

namespace App\Providers;

use App\Models\Clinic;
use App\Support\ClinicContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Blade::if('perm', function (?string $slug = null): bool {
            if ($slug === null || $slug === '') {
                return false;
            }

            $user = Auth::user();

            return $user !== null && $user->hasPermission($slug);
        });

        // Share clinic context with all views (for navbar switcher + indicators)
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('currentClinic', ClinicContext::current());
                $view->with('canSwitchClinic', ClinicContext::canSwitch());
                $view->with('availableClinics', ClinicContext::canSwitch()
                    ? Clinic::where('is_active', true)->orderBy('is_main', 'desc')->orderBy('name')->get()
                    : collect());
            }
        });
    }
}
