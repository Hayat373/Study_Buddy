<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Debug: check if user is authenticated
        if (!Auth::check()) {
            abort(403, 'User not authenticated');
        }
        
        $user = Auth::user();
        
        // Debug: check what user data we have
        \Log::info('Dashboard accessed by user: ' . $user->id . ' - ' . $user->email);
        
        return view('dashboard', compact('user'));
    }
}