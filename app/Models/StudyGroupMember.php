<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyGroupMember extends Model
{
    use HasFactory;

    protected $table = 'study_group_members';

    protected $fillable = [
        'study_group_id',
        'user_id',
        'role'
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}