// app/Models/Quiz.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'flashcard_set_id', 'title', 'description', 
        'question_count', 'time_limit', 'shuffle_questions', 'show_correct_answers'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flashcardSet()
    {
        return $this->belongsTo(FlashcardSet::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}