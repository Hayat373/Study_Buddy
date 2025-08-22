<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:student,teacher,parent,lifelong learner',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profilepicturepath = null;
        if ($request->hasFile('profile_picture')) {
            $profilepicturepath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_picture' => $profilepicturepath,
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->username)->orWhere('email', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Here you can generate a token or session for the user
        // For simplicity, we'll just return the user data

        return response()->json(['message' => 'Login successful', 'user' => $user], 200);
    }
}
