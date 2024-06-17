<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->type === 'E') {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
