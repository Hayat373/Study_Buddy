<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\FlashcardSet;
use App\Models\Flashcard;
use App\Models\QuizQuestion; // Ensure you have this model imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    // Display a list of quizzes for the authenticated user
    public function index()
{
    $quizzes = Quiz::with('flashcardSet')
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $flashcardSets = FlashcardSet::all(); // Fetch all flashcard sets

    return view('quizzes.index', [
        'quizzes' => $quizzes,
        'flashcardSets' => $flashcardSets, // Pass flashcard sets to the view
    ]);
}
    // Show the form to create a new quiz from a flashcard set
    public function create($setId)
    {
        $flashcardSet = FlashcardSet::findOrFail($setId);
        $maxQuestions = $flashcardSet->flashcards()->count();

        return view('quizzes.create', [
            'setId' => $setId,
            'maxQuestions' => $maxQuestions,
        ]);
    }

  

public function store(Request $request, $setId)
{
    // Log incoming request data
    Log::info('Quiz creation request received:', $request->all());

    try {
        $request->validate([
            'question_count' => 'required|integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'shuffle_questions' => 'boolean',
            'show_correct_answers' => 'boolean'
        ]);

        // Check if the flashcard set exists
        $flashcardSet = FlashcardSet::findOrFail($setId);

        // Create the quiz
        $quiz = Quiz::create([
            'title' => 'Quiz for Set ' . $setId,
            'flashcard_set_id' => $setId,
            // Add other fields as necessary
        ]);

        return response()->json(['success' => 'Quiz created successfully!', 'quiz' => $quiz], 201);
    } catch (\Exception $e) {
        Log::error('Quiz creation error: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to create quiz. Please try again.'], 500);
    }
}


    // Start a quiz attempt
    public function startAttempt(Request $request, $quizId)
    {
        $quiz = Quiz::with('questions.flashcard')->findOrFail($quizId);

        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quizId,
            'total_questions' => $quiz->questions->count(),
            'started_at' => now()
        ]);

        return response()->json([
            'attempt' => $attempt,
            'quiz' => $quiz,
            'questions' => $quiz->questions
        ]);
    }

    // Submit an answer for a question
    public function submitAnswer(Request $request, $attemptId)
    {
        $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'user_answer' => 'required|string'
        ]);

        $attempt = QuizAttempt::findOrFail($attemptId);
        
        if ($attempt->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $question = QuizQuestion::with('flashcard')->findOrFail($request->question_id);
        
        $isCorrect = strtolower(trim($request->user_answer)) === 
                     strtolower(trim($question->flashcard->answer));

        $answer = $attempt->answers()->create([
            'quiz_question_id' => $request->question_id,
            'user_answer' => $request->user_answer,
            'is_correct' => $isCorrect
        ]);

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => $question->flashcard->answer,
            'answer' => $answer
        ]);
    }

    // Complete a quiz attempt
    public function completeAttempt(Request $request, $attemptId)
    {
        $attempt = QuizAttempt::with('answers')->findOrFail($attemptId);
        
        if ($attempt->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $correctAnswers = $attempt->answers->where('is_correct', true)->count();
        
        $attempt->update([
            'score' => $correctAnswers,
            'completed_at' => now(),
            'time_taken' => now()->diffInSeconds($attempt->started_at) // Fixed to use started_at
        ]);

        return response()->json($attempt);
    }

    // Get quiz results
    public function getResults($attemptId)
    {
        $attempt = QuizAttempt::with('quiz', 'answers.question.flashcard')
            ->findOrFail($attemptId);
        
        if ($attempt->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($attempt);
    }

    // Get user's quiz history
    public function getHistory()
    {
        $attempts = QuizAttempt::with('quiz.flashcardSet')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($attempts);
    }

    // Display user's completed quiz history
    public function history()
    {
        $attempts = QuizAttempt::with(['quiz.flashcardSet'])
            ->where('user_id', Auth::id())
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->paginate(10);

        return view('quiz.history', ['attempts' => $attempts]);
    }
}