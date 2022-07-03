<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VenkonAuth
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('AuthKey') == "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNjZiODBmZmUyNmQ") {
            return $next($request);
        } else {
            return response(["message" => "Unauthorized"], 401);
        }
    }
}
