<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_group_id',
        'user_id',
        'title',
        'content',
        'is_pinned'
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(DiscussionReply::class)->whereNull('parent_id');
    }

    public function allReplies()
    {
        return $this->hasMany(DiscussionReply::class);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}