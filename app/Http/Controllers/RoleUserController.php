<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function addRole(Request $request, $userId)
    {
        $validated = $request->validate([
            'role_id' => 'required|uuid|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);
        // Utiliza a função assignRole definida no model User
        $user->assignRole($validated['role_id']);

        return response()->json([
            'message' => 'Role adicionada ao usuário com sucesso.',
            'user' => $user->load('roles'),
        ], 200);
    }
}
