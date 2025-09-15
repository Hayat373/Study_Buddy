<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardSet extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'title', 'description', 'subject', 'is_public', 'original_filename',
        'file_path',
        'file_type'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function flashcards()
    {
        return $this->hasMany(Flashcard::class);
    }

     // Add this method to get the file URL
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
    
}