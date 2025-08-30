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
            return response()->json(['message' => 'Only group admins can invite members'], 403);
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
            
            // Send email notification
            try {
                Mail::to($email)->send(new InvitationMail($invitation, $studyGroup, Auth::user()));
            } catch (\Exception $e) {
                // Log error but continue processing other invitations
                \Log::error('Failed to send invitation email: ' . $e->getMessage());
            }
            
            $invitations[] = $invitation;
        }

        return response()->json([
            'message' => 'Invitations sent successfully',
            'invitations' => $invitations
        ]);
    }

    public function accept(Request $request, $token)
    {
        $invitation = StudyGroupInvitation::where('token', $token)->firstOrFail();
        
        // Check if invitation is already accepted or declined
        if ($invitation->accepted_at || $invitation->declined_at) {
            return response()->json(['message' => 'This invitation has already been processed'], 400);
        }
        
        $studyGroup = $invitation->studyGroup;
        
        // Check if user is logged in and matches the invitation email
        if (Auth::check() && Auth::user()->email !== $invitation->email) {
            return response()->json(['message' => 'This invitation is not for your account'], 403);
        }
        
        // If user is not logged in, require them to log in first
        if (!Auth::check()) {
            return response()->json(['message' => 'Please log in to accept the invitation'], 401);
        }
        
        // Check if group has reached max members
        if ($studyGroup->members()->count() >= $studyGroup->max_members) {
            return response()->json(['message' => 'This group has reached maximum members'], 403);
        }
        
        // Add user to group
        $studyGroup->members()->create([
            'user_id' => Auth::id(),
            'role' => 'member'
        ]);
        
        // Mark invitation as accepted
        $invitation->update(['accepted_at' => now()]);
        
        return response()->json(['message' => 'Successfully joined the study group']);
    }

    public function decline(Request $request, $token)
    {
        $invitation = StudyGroupInvitation::where('token', $token)->firstOrFail();
        
        // Check if invitation is already accepted or declined
        if ($invitation->accepted_at || $invitation->declined_at) {
            return response()->json(['message' => 'This invitation has already been processed'], 400);
        }
        
        // Mark invitation as declined
        $invitation->update(['declined_at' => now()]);
        
        return response()->json(['message' => 'Invitation declined']);
    }

    public function pendingInvitations($groupId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        
        // Check if user is admin of the group
        if (!$studyGroup->isAdmin(Auth::id())) {
            return response()->json(['message' => 'Only group admins can view invitations'], 403);
        }
        
        $invitations = $studyGroup->invitations()
            ->whereNull('accepted_at')
            ->whereNull('declined_at')
            ->with('inviter')
            ->get();
            
        return response()->json($invitations);
    }
}