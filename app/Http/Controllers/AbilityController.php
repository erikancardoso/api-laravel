<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use Illuminate\Http\Request;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $abilities = Ability::all();

        return response()->json($abilities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Ajuste os campos conforme a estrutura do seu model Ability
            'name' => 'required|string|max:255|unique:abilities,name',
        ]);

        $ability = Ability::create($validated);

        return response()->json($ability, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ability $ability)
    {
        return response()->json($ability);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ability $ability)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:abilities,name,'.$ability->id,
        ]);

        $ability->update($validated);

        return response()->json($ability);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();

        return response()->json(null, 204);
    }
}
