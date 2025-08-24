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
        'faceDescriptor' => 'required|json', // Add this line
        'role' => 'required|in:student,teacher,parent,lifelong_learner',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'faceDescriptor' => $request->faceDescriptor, // Store the face descriptor
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

    // Retrieve users and compare descriptors
    $users = User::all();
    foreach ($users as $user) {
        if ($this->compareFaceDescriptors(json_decode($user->faceDescriptor), json_decode($faceDescriptor))) {
            // Successful recognition
            auth()->login($user); // Log the user in
            return response()->json(['message' => 'Face recognized, login successful', 'user' => $user], 200);
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

    public function updateUser(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,' . auth()->id(),
        'password' => 'sometimes|nullable|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = auth()->user();
    if ($request->has('email')) {
        $user->email = $request->email;
    }
    if ($request->has('password')) {
        $user->password = Hash::make($request->password);
    }
    $user->save();

    return response()->json(['message' => 'User information updated successfully'], 200);
}


 public function registerFace(Request $request)
    {
        $request->validate([
            'faceDescriptor' => 'required|array',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string',
        ]);
        
        // Create user with facial data
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make(Str::random(24)), // Random password for facial login users
            'facial_data' => json_encode($request->faceDescriptor),
        ]);
        
        Auth::login($user);
        
        return response()->json([
            'success' => true,
            'redirect' => route('dashboard')
        ]);
    }
    
    public function loginFace(Request $request)
    {
        $request->validate([
            'faceDescriptor' => 'required|array',
        ]);
        
        $users = User::whereNotNull('facial_data')->get();
        $inputDescriptor = $request->faceDescriptor;
        
        foreach ($users as $user) {
            $storedDescriptor = json_decode($user->facial_data, true);
            
            // Calculate Euclidean distance between descriptors
            $distance = $this->calculateDescriptorDistance($storedDescriptor, $inputDescriptor);
            
            // Threshold for face recognition match (adjust as needed)
            if ($distance < 0.6) {
                Auth::login($user);
                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard')
                ]);
            }
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No matching face found'
        ], 401);
    }
    
    private function calculateDescriptorDistance($descriptor1, $descriptor2)
    {
        // Calculate Euclidean distance between two face descriptors
        $sum = 0;
        for ($i = 0; $i < count($descriptor1); $i++) {
            $diff = $descriptor1[$i] - $descriptor2[$i];
            $sum += $diff * $diff;
        }
        return sqrt($sum);
    }
    

}