<?php

namespace App\Providers;

use App\Models\Faq;
use App\Models\ImpactMetric;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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
        Paginator::useBootstrapFive();

        foreach (array_keys(config('admin_permissions.permissions', [])) as $permission) {
            Gate::define($permission, fn ($user): bool => $user->hasPermission($permission));
        }

        View::composer('pages.resources', function ($view): void {
            $view->with('faqs', Faq::publishedFrontendFaqs());
        });

        View::composer('pages.home', function ($view): void {
            $view->with('impactMetrics', ImpactMetric::publishedFrontendMetrics());
        });

        RateLimiter::for('public', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.public', 120))
                ->by($request->ip());
        });

        RateLimiter::for('admin-auth', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin_auth', 5))
                ->by($request->ip());
        });

        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin', 120))
                ->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-write', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin_write', 60))
                ->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-upload', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin_upload', 20))
                ->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-import', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin_import', 5))
                ->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
