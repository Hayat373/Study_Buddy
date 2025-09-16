<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       'username', 'email', 'password', 'profile_picture', 'role', 'verification_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


     public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        
        // Default profile picture if none is set
        return asset('images/default-profile.png');
    }

    

   
   

     /**
     * Get the study groups that the user belongs to.
     */
    public function studyGroups()
    {
        return $this->belongsToMany(StudyGroup::class, 'study_group_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the study groups created by the user.
     */
    public function createdStudyGroups()
    {
        return $this->hasMany(StudyGroup::class, 'created_by');
    }

    /**
     * Get the study group memberships.
     */
    public function studyGroupMembers()
    {
        return $this->hasMany(StudyGroupMember::class);
    }

    /**
     * Get the study group invitations for the user.
     */
    public function studyGroupInvitations()
    {
        return $this->hasMany(StudyGroupInvitation::class);
    }


}
