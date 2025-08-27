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
use Illuminate\Validation\ValidationException;

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

  



// ...

public function store(Request $request, $setId)
{
    Log::info('Quiz creation request received:', $request->all());

    try {
        $request->validate([
            'question_count' => 'required|integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'shuffle_questions' => 'boolean',
            'show_correct_answers' => 'boolean'
        ]);

        $flashcardSet = FlashcardSet::findOrFail($setId);
        $flashcards = $flashcardSet->flashcards;

        if ($flashcards->count() < $request->question_count) {
            return $request->expectsJson()
                ? response()->json(['errors' => ['question_count' => ['Not enough flashcards in this set']]], 422)
                : back()->withErrors(['question_count' => ['Not enough flashcards in this set']]);
        }

        $quiz = Quiz::create([
            'user_id' => Auth::id(),
            'flashcard_set_id' => $setId,
            'title' => 'Quiz: ' . $flashcardSet->title,
            'description' => 'Quiz generated from ' . $flashcardSet->title,
            'question_count' => $request->question_count,
            'time_limit' => $request->time_limit,
            'shuffle_questions' => $request->shuffle_questions ?? false,
            'show_correct_answers' => $request->show_correct_answers ?? true
        ]);

        $selectedFlashcards = $request->shuffle_questions
            ? $flashcards->shuffle()->take($request->question_count)
            : $flashcards->take($request->question_count);

        foreach ($selectedFlashcards as $index => $flashcard) {
            $quiz->questions()->create([
                'flashcard_id' => $flashcard->id,
                'order' => $index
            ]);
        }

        return $request->expectsJson()
            ? response()->json(['success' => 'Quiz created successfully!', 'id' => $quiz->id], 201)
            : redirect()->route('quizzes.show', $quiz->id)->with('success', 'Quiz created successfully!');
    } catch (ValidationException $e) {
        return $request->expectsJson()
            ? response()->json(['errors' => $e->errors()], 422)
            : back()->withErrors($e->errors());
    } catch (\Exception $e) {
        Log::error('Quiz creation error: ' . $e->getMessage());
        return $request->expectsJson()
            ? response()->json(['error' => 'Failed to create quiz. Please try again.'], 500)
            : back()->withErrors(['error' => 'Failed to create quiz. Please try again.']);
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

    public function show($id)
{
    $quiz = Quiz::with('questions.flashcard')->findOrFail($id);

    if ($quiz->user_id !== Auth::id()) {
        abort(403, 'Unauthorized');
    }

    return view('quizzes.show', ['quiz' => $quiz]);
}

}