<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use App\Models\StudyGroupInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudyGroupInvitation as InvitationMail;

class StudyGroupInvitationController extends Controller
{
    public function invite(Request $request, $groupId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        
        // Check if user is admin of the group
        if (!$studyGroup->isAdmin(Auth::id())) {
            return redirect()->back()->with('error', 'Only group admins can invite members.');
        }

        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'required|email'
        ]);

        $invitations = [];
        
        foreach ($request->emails as $email) {
            // Check if user exists
            $user = User::where('email', $email)->first();
            
            // Check if user is already a member
            if ($user && $studyGroup->isMember($user->id)) {
                continue;
            }
            
            // Check if there's already a pending invitation
            $existingInvitation = StudyGroupInvitation::where('study_group_id', $studyGroup->id)
                ->where('email', $email)
                ->whereNull('accepted_at')
                ->whereNull('declined_at')
                ->first();
                
            if ($existingInvitation) {
                continue;
            }
            
            // Create invitation
            $invitation = StudyGroupInvitation::create([
                'study_group_id' => $studyGroup->id,
                'invited_by' => Auth::id(),
                'user_id' => $user ? $user->id : null,
                'email' => $email,
                'token' => bin2hex(random_bytes(16))
            ]);
            
            // Send email notification (you can uncomment this when you set up mail)
            /*
            try {
                Mail::to($email)->send(new InvitationMail($invitation, $studyGroup, Auth::user()));
            } catch (\Exception $e) {
                // Log error but continue processing other invitations
                \Log::error('Failed to send invitation email: ' . $e->getMessage());
            }
            */
            
            $invitations[] = $invitation;
        }

        if (count($invitations) > 0) {
            return redirect()->back()->with('success', 'Invitations sent successfully.');
        } else {
            return redirect()->back()->with('info', 'No new invitations were sent. Users may already be members or have pending invitations.');
        }
    }

    public function accept(Request $request, $token)
    {
        $invitation = StudyGroupInvitation::where('token', $token)->firstOrFail();
        
        // Check if invitation is already accepted or declined
        if ($invitation->accepted_at || $invitation->declined_at) {
            return redirect()->route('study-groups.show', $invitation->study_group_id)
                ->with('error', 'This invitation has already been processed.');
        }
        
        $studyGroup = $invitation->studyGroup;
        
        // Check if user is logged in and matches the invitation email
        if (Auth::check() && Auth::user()->email !== $invitation->email) {
            return redirect()->route('study-groups.show', $invitation->study_group_id)
                ->with('error', 'This invitation is not for your account.');
        }
        
        // If user is not logged in, require them to log in first
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to accept the invitation.');
        }
        
        // Check if group has reached max members
        if ($studyGroup->members()->count() >= $studyGroup->max_members) {
            return redirect()->route('study-groups.show', $invitation->study_group_id)
                ->with('error', 'This group has reached maximum members.');
        }
        
        // Add user to group
        $studyGroup->members()->create([
            'user_id' => Auth::id(),
            'role' => 'member'
        ]);
        
        // Mark invitation as accepted
        $invitation->update(['accepted_at' => now()]);
        
        return redirect()->route('study-groups.show', $invitation->study_group_id)
            ->with('success', 'Successfully joined the study group!');
    }

    public function decline(Request $request, $token)
    {
        $invitation = StudyGroupInvitation::where('token', $token)->firstOrFail();
        
        // Check if invitation is already accepted or declined
        if ($invitation->accepted_at || $invitation->declined_at) {
            return redirect()->route('study-groups.show', $invitation->study_group_id)
                ->with('error', 'This invitation has already been processed.');
        }
        
        // Mark invitation as declined
        $invitation->update(['declined_at' => now()]);
        
        return redirect()->route('study-groups.index')
            ->with('info', 'Invitation declined.');
    }

    public function pendingInvitations($groupId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        
        // Check if user is admin of the group
        if (!$studyGroup->isAdmin(Auth::id())) {
            return redirect()->back()->with('error', 'Only group admins can view invitations.');
        }
        
        $invitations = $studyGroup->invitations()
            ->whereNull('accepted_at')
            ->whereNull('declined_at')
            ->with('inviter')
            ->get();
            
        return view('study-groups.invitations', compact('studyGroup', 'invitations'));
    }

    public function cancel($invitationId)
{
    $invitation = StudyGroupInvitation::findOrFail($invitationId);
    $studyGroup = $invitation->studyGroup;
    
    // Check if user is admin of the group
    if (!$studyGroup->isAdmin(Auth::id())) {
        return redirect()->back()->with('error', 'Only group admins can cancel invitations.');
    }
    
    // Check if invitation is already accepted or declined
    if ($invitation->accepted_at || $invitation->declined_at) {
        return redirect()->back()->with('error', 'This invitation has already been processed.');
    }
    
    $invitation->delete();
    
    return redirect()->back()->with('success', 'Invitation cancelled successfully.');
}

}