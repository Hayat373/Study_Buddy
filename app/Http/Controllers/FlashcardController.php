<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function index()
    {
        return view('flashcards.index');
    }
    
    public function create()
    {
        return view('flashcards.create');
    }
    
    public function store(Request $request)
    {
        // Store logic here
    }
    
    public function show($id)
    {
        return view('flashcards.show', compact('id'));
    }
    
    public function edit($id)
    {
        return view('flashcards.edit', compact('id'));
    }
    
    public function update(Request $request, $id)
    {
        // Update logic here
    }
    
    public function destroy($id)
    {
        // Delete logic here
    }
}