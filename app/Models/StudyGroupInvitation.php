<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyGroupInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_group_id',
        'invited_by',
        'user_id',
        'email',
        'token',
        'accepted_at',
        'declined_at'
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending()
    {
        return is_null($this->accepted_at) && is_null($this->declined_at);
    }
}