<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail; // Create this Mailable

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        // Validate the request
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Generate a verification code
        $verificationCode = rand(100000, 999999);

        // Store the verification code in the database (optional)
        $user = User::where('email', $request->email)->first();
        $user->verification_code = $verificationCode;
        $user->save();

        // Send verification code to the user's email
        Mail::to($request->email)->send(new PasswordResetMail($verificationCode));

        return response()->json(['message' => 'Verification code sent to your email.']);
    }
}