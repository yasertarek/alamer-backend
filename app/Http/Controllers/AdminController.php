<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;


use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::paginate(10);
        return response()->json($admins);
    }

    public function store(Request $request)
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

    public function show($id)
    {
        $admin = Admin::where('role', 'admin')->findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'role' => ['sometimes', 'required', Rule::in(['moderator', 'supervisor'])],
        ]);

        $admin->update([
            'name' => $request->name ?? $admin->name,
            'email' => $request->email ?? $admin->email,
            'password' => $request->password ? Hash::make($request->password) : $admin->password,
            'role' => $request->role ?? $admin->role,
        ]);

        return response()->json($admin);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
                $admin = Auth::guard('admin')->user();
                $token = $admin->createToken('token', ['admin'])->plainTextToken;

                return response()->json(['token' => $token]);
            }else{
                return response()->json(['message' => 'بيانات دخول غير صحيحة'], 401);
            }

        } catch (QueryException $e) {
            return response()->json(['message' => 'بيانات دخول غير صحيحة', 'error' => $e], 401);
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function destroy($id)
    {
        $admin = Admin::where('role', 'admin')->findOrFail($id);
        $admin->delete();
        return response()->json(null, 204);
    }
}
