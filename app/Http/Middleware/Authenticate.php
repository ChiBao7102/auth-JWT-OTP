<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class Authenticate extends Middleware
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::guard($guards[0])->check() && Auth::guard($guards[0])->user()) {
            return $next($request);
        } else {
            return $this->error(null, 'You should be login before use it', 401);
        }
    }
}
