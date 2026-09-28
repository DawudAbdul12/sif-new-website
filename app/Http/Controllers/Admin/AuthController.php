<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in '.RateLimiter::availableIn($throttleKey).' seconds.',
            ]);
        }

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'is_admin' => true], $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match an admin account.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        $this->recordAuthActivity($request, Auth::user(), 'logged_in');

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->recordAuthActivity($request, Auth::user(), 'logged_out');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function recordAuthActivity(Request $request, ?User $user, string $action): void
    {
        if (! $user || ! $this->activityLogTableExists()) {
            return;
        }

        ActivityLog::withoutEvents(function () use ($request, $user, $action): void {
            ActivityLog::create([
                'action' => $action,
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'subject_label' => $user->name ?: $user->email,
                'causer_id' => $user->id,
                'causer_name' => $user->name,
                'causer_email' => $user->email,
                'metadata' => [
                    'guard' => 'admin',
                    'event' => $action,
                    'table' => $user->getTable(),
                    'model' => User::class,
                    'primary_key' => $user->getKeyName(),
                ],
                'url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });
    }

    private function activityLogTableExists(): bool
    {
        try {
            return Schema::hasTable('activity_logs');
        } catch (Throwable) {
            return false;
        }
    }
}
