<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;

class CheckSeller
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()->isSeller()) {
            abort(403, 'Only sellers can perform this action');
        }
        return $next($request);
    }
}
