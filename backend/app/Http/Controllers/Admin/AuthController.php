<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/admin/login
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Auto-create or repair main admin if missing or incorrect
        if (Admin::count() === 0) {
            Admin::create([
                'name'           => 'Main Admin',
                'email'          => 'naishad@ssgd.com',
                'password'       => Hash::make('naishad@123'),
                'is_super_admin' => true,
            ]);
        }

        $admin = Admin::where('email', $request->email)->first();

        // Auto-repair password for main admin if naishad@123 is used
        if ($admin && $admin->email === 'naishad@ssgd.com' && $request->password === 'naishad@123') {
            if (!Hash::check($request->password, $admin->password)) {
                $admin->password = Hash::make('naishad@123');
                $admin->save();
            }
        }

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke all previous tokens for a clean session
        $admin->tokens()->delete();

        $token = $admin->createToken('admin-panel')->plainTextToken;

        return response()->json([
            'token'       => $token,
            'admin'       => [
                'id'             => $admin->id,
                'name'           => $admin->name,
                'email'          => $admin->email,
                'is_super_admin' => $admin->is_super_admin,
                'permissions'    => $admin->permissionsArray(),
            ],
        ]);
    }

    /**
     * POST /api/admin/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * GET /api/admin/me
     */
    public function me(Request $request): JsonResponse
    {
        $admin = $request->user();

        return response()->json([
            'id'             => $admin->id,
            'name'           => $admin->name,
            'email'          => $admin->email,
            'is_super_admin' => $admin->is_super_admin,
            'permissions'    => $admin->permissionsArray(),
        ]);
    }
}
