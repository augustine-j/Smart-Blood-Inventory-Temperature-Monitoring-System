<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required' ,'email'],
            'password' => ['required', 'string'],
            
        ]);

        $user = User::where('email',$validated['email'])->first();

        if(! $user || ! Hash::check($validated['password'], $user->password))
            {
                throw ValidationException::withMessages([
                    'email' => ['Invalid login credentials.'],
                ]);
            }
        
        $token =$user->createToken('default-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token_type' => 'Bearer',
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('bloodBanks'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout Successful',
        ]);
    }
}
