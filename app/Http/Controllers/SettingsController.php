<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    // Show settings page
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    // Update profile information
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('settings.index')
            ->with('success', 'Profile updated successfully!');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('settings.index')
                ->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('settings.index')
            ->with('success', 'Password updated successfully!');
    }

    // Update notification preferences
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        // Assuming you have notification preferences in your users table
        // or a separate user_settings table
        $user->email_notifications = $request->has('email_notifications');
        $user->push_notifications = $request->has('push_notifications');
        $user->save();

        return redirect()->route('settings.index')
            ->with('success', 'Notification preferences updated!');
    }

    // Update privacy settings
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();
        
        // Update privacy settings
        $user->profile_visibility = $request->profile_visibility;
        $user->save();

        return redirect()->route('settings.index')
            ->with('success', 'Privacy settings updated!');
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}