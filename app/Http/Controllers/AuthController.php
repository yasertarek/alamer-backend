<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

use App\Models\User;

use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'type' => 'regular',
        ]);

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->where('role', 'user')->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'خطأ في عنوان البريد او كلمة المرور.'], 401);
        }

        $token = $user->createToken('user_token', ['user'])->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
    public function user(Request $request)
    {
        return new UserResource(Auth::user());
    }
}
