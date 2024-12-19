<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        return User::select(
            'id',
            'name',
            'email',
            'phone',
            'created_at',
            'updated_at'
        )->orderBy('updated_at DESC')->get();
    }

    public function show($id)
    {
        return User::select(
            'id',
            'name',
            'email',
            'phone',
            'created_at',
            'updated_at'
        )->where('id', '=', $id)->firstOrFail();
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'created_at' => 'required|date',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create($validated);

        $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user
        ], 201);
    }
}
