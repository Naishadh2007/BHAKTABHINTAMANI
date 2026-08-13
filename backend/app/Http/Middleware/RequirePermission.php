<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        /** @var \App\Models\Admin $admin */
        $admin = $request->user();

        if (!$admin || !$admin->hasPermission($permission)) {
            return response()->json([
                'error' => 'You do not have permission to perform this action.',
            ], 403);
        }

        return $next($request);
    }
}
