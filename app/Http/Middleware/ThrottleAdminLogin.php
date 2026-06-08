<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleAdminLogin
{
    /**
     * Limit admin login attempts per IP address.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->routeIs('admin.session.store')) {
            return $next($request);
        }

        $key = 'admin-login:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return redirect()
                ->back()
                ->withInput($request->only('email'))
                ->with('error', trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => (int) ceil($seconds / 60),
                ]));
        }

        RateLimiter::hit($key, decaySeconds: 60);

        $response = $next($request);

        if (auth()->guard('admin')->check()) {
            RateLimiter::clear($key);
        }

        return $response;
    }
}
