<?php

namespace App\Mail;

use App\Models\StudyGroup;
use App\Models\StudyGroupInvitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudyGroupInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $studyGroup;
    public $inviter;

    public function __construct(StudyGroupInvitation $invitation, StudyGroup $studyGroup, User $inviter)
    {
        $this->invitation = $invitation;
        $this->studyGroup = $studyGroup;
        $this->inviter = $inviter;
    }

    public function build()
    {
        return $this->subject('Invitation to join ' . $this->studyGroup->name)
                    ->markdown('emails.study-group-invitation');
    }
}