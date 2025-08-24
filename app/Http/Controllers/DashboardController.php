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
        // Get the authenticated user
        $user = Auth::user();
        
        // You can pass any data needed for the dashboard here
        return view('dashboard', compact('user'));
    }
}