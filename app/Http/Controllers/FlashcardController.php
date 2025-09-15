<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlashcardSet;
use App\Models\Flashcard;
use App\Services\AIService; 
use Illuminate\Support\Facades\Auth;

class FlashcardController extends Controller
{
    protected $aiService;

     public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth');
    }


    public function index()
    {
        $flashcardSets = FlashcardSet::with('flashcards')
            ->where('user_id', auth()->id())
            ->orWhere('is_public', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('flashcards.index', compact('flashcardSets'));
    }
    
    public function create()
    {
        return view('flashcards.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'flashcards' => 'required|array|min:1',
            'flashcards.*.question' => 'required|string',
            'flashcards.*.answer' => 'required|string',
            'original_filename' => 'nullable|string',
            'file_path' => 'nullable|string',
            'file_type' => 'nullable|string',
        ]);
        $setData = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'is_public' => $request->has('is_public'),
        ];

         if ($request->filled('original_filename')) {
            $setData['original_filename'] = $request->original_filename;
            $setData['file_path'] = $request->file_path;
            $setData['file_type'] = $request->file_type;
        }

        
        $set = FlashcardSet::create($setData);
        
        foreach ($request->flashcards as $flashcardData) {
            $set->flashcards()->create([
                'question' => $flashcardData['question'],
                'answer' => $flashcardData['answer'],
            ]);
        }
        
        return redirect()->route('flashcards.show', $set->id)
            ->with('success', 'Flashcard set created successfully!');
    }
    
    public function show($id)
    {
        $flashcardSet = FlashcardSet::with('flashcards')->findOrFail($id);
        
        // Check if user owns the set or it's public
        if ($flashcardSet->user_id !== auth()->id() && !$flashcardSet->is_public) {
            abort(403);
        }
        
        return view('flashcards.show', compact('flashcardSet'));
    }
    
    public function edit($id)
    {
        $flashcardSet = FlashcardSet::with('flashcards')->findOrFail($id);
        
        if ($flashcardSet->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('flashcards.edit', compact('flashcardSet'));
    }
    
    public function update(Request $request, $id)
    {
        $flashcardSet = FlashcardSet::findOrFail($id);
        
        if ($flashcardSet->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'flashcards' => 'required|array|min:1',
            'flashcards.*.question' => 'required|string',
            'flashcards.*.answer' => 'required|string',
        ]);
        
        $flashcardSet->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_public' => $request->has('is_public'),
        ]);
        
        // First, delete all existing flashcards
        $flashcardSet->flashcards()->delete();
        
        // Then create new ones
        foreach ($request->flashcards as $flashcardData) {
            $flashcardSet->flashcards()->create([
                'question' => $flashcardData['question'],
                'answer' => $flashcardData['answer'],
            ]);
        }
        
        return redirect()->route('flashcards.show', $id)
            ->with('success', 'Flashcard set updated successfully!');
    }
    
    
   
    
    public function destroy($id)
    {
        $flashcardSet = FlashcardSet::findOrFail($id);
        
        if ($flashcardSet->user_id !== auth()->id()) {
            abort(403);
        }
        
        $flashcardSet->delete();
        
        return redirect()->route('flashcards.index')
            ->with('success', 'Flashcard set deleted successfully!');
    }

   public function generateAI(Request $request)
{
    $request->validate([
        'topic' => 'required|string|max:255',
        'count' => 'required|integer|min:1|max:20',
    ]);
    
    // Check if API key is configured
    if (empty(config('services.openai.api_key'))) {
        return response()->json([
            'success' => false,
            'message' => 'OpenAI API key is not configured. Please contact administrator.'
        ], 500);
    }
    
    try {
        $flashcards = $this->aiService->generateFlashcards(
            $request->topic, 
            $request->count
        );
        
        return response()->json([
            'success' => true,
            'flashcards' => $flashcards
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate flashcards: ' . $e->getMessage()
        ], 500);
    }
}
    
    public function share(Request $request, $id)
    {
        $flashcardSet = FlashcardSet::findOrFail($id);
        
        if ($flashcardSet->user_id !== auth()->id()) {
            abort(403);
        }
        
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
        ]);
        
        // Logic to share flashcard set to chat
        // This would depend on your chat implementation
        
        return response()->json([
            'success' => true,
            'message' => 'Flashcard set shared successfully!'
        ]);
    }

    public function generateFromFile(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:txt,pdf,docx,md|max:10240',
        'count' => 'required|integer|min:1|max:20',
    ]);

    // Check if API key is configured
    if (empty(config('services.openai.api_key'))) {
        return response()->json([
            'success' => false,
            'message' => 'OpenAI API key is not configured. Please contact administrator.'
        ], 500);
    }

    try {
        // ... rest of your file handling code ...
    } catch (\Exception $e) {
        Log::error('File flashcard generation error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate flashcards from file: ' . $e->getMessage()
        ], 500);
    }
}

}