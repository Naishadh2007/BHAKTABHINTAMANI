<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Find the token in personal_access_tokens
        $pat = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

        if (!$pat || $pat->tokenable_type !== Admin::class) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $admin = Admin::find($pat->tokenable_id);

        if (!$admin) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $request->merge(['_admin' => $admin]);
        $request->setUserResolver(fn() => $admin);

        return $next($request);
    }
}
