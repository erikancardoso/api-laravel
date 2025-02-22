<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registra um novo usuário.
     *
     * Espera um JSON com os seguintes campos:
     * - name
     * - surname
     * - nickname
     * - birthday (formato YYYY-MM-DD)
     * - phone
     * - email
     * - password
     * - password_confirmation
     *
     * Exemplo de JSON:
     * {
     *   "name": "João",
     *   "surname": "Silva",
     *   "nickname": "joaosilva",
     *   "birthday": "1980-01-01",
     *   "phone": "11987654321",
     *   "email": "joao.silva@example.com",
     *   "password": "senha123",
     *   "password_confirmation": "senha123"
     * }
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birthday' => 'required|date',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Hasheia a senha antes de salvar
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Cria um token de autenticação para o usuário
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Realiza o login do usuário.
     *
     * Espera um JSON com os campos:
     * - email
     * - password
     *
     * Exemplo de JSON:
     * {
     *   "email": "joao.silva@example.com",
     *   "password": "senha123"
     * }
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        // Cria o token de autenticação
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login efetuado com sucesso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * Efetua o logout do usuário, revogando seus tokens.
     *
     * Este método deve ser protegido (middleware auth:sanctum).
     */
    public function logout(Request $request)
    {
        // Revoga todos os tokens do usuário logado
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout efetuado com sucesso.',
        ]);
    }
}
