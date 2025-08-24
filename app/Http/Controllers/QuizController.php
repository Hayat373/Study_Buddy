<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return view('quizzes.index');
    }
    
    // Add other methods as needed
}