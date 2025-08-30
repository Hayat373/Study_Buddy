<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'subject',
        'max_members',
        'is_public',
        'join_code'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
{
    return $this->hasMany(StudyGroupMember::class);
}

    

    


    public function membersCount()
{
    return $this->hasMany(StudyGroupMember::class)->count();
}


 /**
     * Get the users belonging to this study group.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'study_group_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the invitations for this study group.
     */
    public function invitations()
    {
        return $this->hasMany(StudyGroupInvitation::class);
    }

    /**
     * Check if a user is a member of this study group.
     */
    public function isMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * Check if a user is an admin of this study group.
     */
    public function isAdmin($userId)
    {
        return $this->members()->where('user_id', $userId)
                    ->where('role', 'admin')
                    ->exists();
    }

    /**
     * Generate a join code for the study group.
     */
    public function generateJoinCode()
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, 8);
    }

}