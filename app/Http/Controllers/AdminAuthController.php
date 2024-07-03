<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:supervisor,moderator',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $admin->assignRole($request->role);

        return response()->json(['message' => 'Admin registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }
}