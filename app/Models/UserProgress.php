<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'flashcard_set_id', 'type', 'study_time', 'mastery_level', 'description'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function flashcardSet()
    {
        return $this->belongsTo(FlashcardSet::class);
    }
}