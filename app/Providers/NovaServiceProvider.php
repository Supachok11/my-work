<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Nova::initialPath('/mywork-hub');

        Nova::withBreadcrumbs();

        Nova::withoutThemeSwitcher();
        
        Nova::footer(function () {
            return '© ' . date('Y') . ' MY WORK. All rights reserved.';
        });
    }

    /**
     * Register the configurations for Laravel Fortify.
     */
    protected function fortify(): void
    {
        Nova::fortify()
            ->features([
                Features::updatePasswords(),
                // Features::emailVerification(),
                // Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
            ])
            ->register();
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->withoutEmailVerificationRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewNova', function (User $user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Dashboard>
     */
    protected function dashboards(): array
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    // /**
    //  * Get the resources that should be listed in the Nova sidebar.
    //  *
    //  * @return array<int, \Laravel\Nova\Resource>
    //  */
    // protected function resources(): array
    // {
    //     return [
    //         new \App\Nova\User,
    //         new \App\Nova\LeaveRequest,
    //         new \App\Nova\TimeLog,
    //     ];
    // }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Tool>
     */
    public function tools(): array
    {
        return [
            new \Company\MyworkHub\MyworkHub,
            new \Company\TimeAttendance\TimeAttendance,
            new \Leave\LeavePortal\LeavePortal,
        ];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();
        //
    }
}
