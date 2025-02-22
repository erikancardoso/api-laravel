<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $users->load('roles');

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birthday' => 'required|date',
            'cpf' => 'nullable|string|max:14|unique:users,cpf',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Lembre-se de hashear a senha antes de salvar
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return response()->json(null, 204);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles.abilities');

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birthday' => 'required|date',
            'cpf' => 'nullable|string|max:14|unique:users,cpf,'.$user->id,
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            // O campo password pode ser opcional na atualização
            'password' => 'sometimes|nullable|string|min:8',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
