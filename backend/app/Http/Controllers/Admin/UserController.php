<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * GET /api/admin/users
     */
    public function index(): JsonResponse
    {
        $users = Admin::select(['id', 'name', 'email', 'is_super_admin', 'permissions', 'created_at'])
            ->orderBy('created_at')
            ->get()
            ->map(fn($a) => [
                'id'             => $a->id,
                'name'           => $a->name,
                'email'          => $a->email,
                'is_super_admin' => $a->is_super_admin,
                'permissions'    => $a->permissionsArray(),
                'created_at'     => $a->created_at,
            ]);

        return response()->json($users);
    }

    /**
     * POST /api/admin/users
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:admins,email',
            'password'    => 'required|string|min:8',
            'permissions' => 'nullable|array',
        ]);

        $admin = Admin::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => $data['password'],
            'is_super_admin' => false,
            'permissions'    => $data['permissions'] ?? [],
        ]);

        return response()->json([
            'id'             => $admin->id,
            'name'           => $admin->name,
            'email'          => $admin->email,
            'is_super_admin' => false,
            'permissions'    => $admin->permissionsArray(),
        ], 201);
    }

    /**
     * PUT /api/admin/users/{id}
     * Update name, email, password, or permissions.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $admin = Admin::findOrFail($id);

        // Prevent editing super admin via this endpoint
        if ($admin->is_super_admin) {
            return response()->json(['error' => 'Cannot modify super admin via this endpoint.'], 403);
        }

        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'email'       => "sometimes|email|unique:admins,email,{$id}",
            'password'    => 'sometimes|string|min:8',
            'permissions' => 'nullable|array',
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $admin->update($data);

        return response()->json([
            'id'             => $admin->id,
            'name'           => $admin->name,
            'email'          => $admin->email,
            'is_super_admin' => false,
            'permissions'    => $admin->permissionsArray(),
        ]);
    }

    /**
     * DELETE /api/admin/users/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $admin = Admin::findOrFail($id);

        if ($admin->is_super_admin) {
            return response()->json(['error' => 'Cannot delete the super admin.'], 403);
        }

        $admin->tokens()->delete();
        $admin->delete();

        return response()->json(['message' => 'User deleted.']);
    }
}
