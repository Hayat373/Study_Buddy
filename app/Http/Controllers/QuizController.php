// app/Http/Controllers/QuizController.php
namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\FlashcardSet;
use App\Models\Flashcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // Create a new quiz from a flashcard set
    public function create(Request $request, $setId)
    {
        $request->validate([
            'question_count' => 'required|integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'shuffle_questions' => 'boolean',
            'show_correct_answers' => 'boolean'
        ]);

        $flashcardSet = FlashcardSet::findOrFail($setId);
        $flashcards = $flashcardSet->flashcards;

        if ($flashcards->count() < $request->question_count) {
            return response()->json([
                'error' => 'Not enough flashcards in this set'
            ], 422);
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

        // Select random flashcards for the quiz
        $selectedFlashcards = $request->shuffle_questions 
            ? $flashcards->shuffle()->take($request->question_count)
            : $flashcards->take($request->question_count);

        // Create quiz questions
        foreach ($selectedFlashcards as $index => $flashcard) {
            $quiz->questions()->create([
                'flashcard_id' => $flashcard->id,
                'order' => $index
            ]);
        }

        return response()->json($quiz->load('questions.flashcard'));
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

        $question = QuizQuestion::with('flashcard')->find($request->question_id);
        
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
            'time_taken' => now()->diffInSeconds($attempt->created_at)
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
}