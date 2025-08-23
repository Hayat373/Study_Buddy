<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:student,teacher,parent,lifelong_learner',
            'faceDescriptor' => 'required|json',
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
             $user->faceDescriptor = json_encode($request->faceDescriptor);
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
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

        return response()->json(['message' => 'Login successful', 'user' => $user], 200);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            // Find or create the user
            $existingUser = User::where('email', $user->getEmail())->first();
            if ($existingUser) {
                auth()->login($existingUser);
            } else {
                $newUser = User::create([
                    'username' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => Hash::make(Str::random(16)), // Optionally set a default password or manage it differently
                    // You may want to add a profile picture if available
                    // 'profile_picture' => $user->getAvatar(),
                ]);
                auth()->login($newUser);
            }

            return redirect('/home'); // Adjust this route as needed
        } catch (\Exception $e) {
            // Handle exception, e.g., log error, redirect with error message
            return redirect('/login')->withErrors('Google login failed.');
        }
    }

    public function recognizeFace(Request $request) {
    $faceDescriptor = $request->input('faceDescriptor');

    // Compare with stored descriptors (this requires facial descriptors to be stored)
    $users = User::all(); // Retrieve all users from the database
    foreach ($users as $user) {
        // Implement your logic to compare descriptors
        // You might use a library or a custom function
        if (compareFaceDescriptors($user->faceDescriptor, $faceDescriptor)) {
            // Successful recognition
            return response()->json(['message' => 'Face recognized, login successful'], 200);
        }
    }

    return response()->json(['message' => 'Face not recognized'], 401);
}

    private function compareFaceDescriptors($descriptor1, $descriptor2) {
        // Implement your comparison logic here
        // This is a placeholder function
        return false; // Change this to actual comparison result
    }

    public function verifyFace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'face_data' => 'required|array', // Assuming face_data is sent as an array of numbers
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Here you would implement the actual face verification logic
        // This is a placeholder for demonstration purposes
        $isFaceMatch = $this->mockFaceVerification($user->face_data, $request->face_data);

        if ($isFaceMatch) {
            return response()->json(['message' => 'Face verification successful'], 200);
        } else {
            return response()->json(['message' => 'Face verification failed'], 401);
        }
    }

    private function mockFaceVerification($storedFaceData, $inputFaceData)
    {
        // This is a mock function. Replace with actual face verification logic.
        return $storedFaceData === $inputFaceData;
    }


}