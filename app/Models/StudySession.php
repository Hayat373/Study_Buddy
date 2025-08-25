<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudySession extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'title', 'description', 'scheduled_at', 'participants_count'];
    
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}