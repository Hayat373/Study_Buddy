<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use App\Models\StudyGroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class StudyGroupController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    // Get groups user is member of (using the correct relationship)
    $userGroups = $user->studyGroups()->with('creator')->withCount('members')->get();
    
    // Add is_admin flag to each group
    $userGroups->each(function ($group) use ($user) {
        $group->is_admin = $group->isAdmin($user->id);
    });
    
    // Get public groups user is not member of
    $publicGroups = StudyGroup::where('is_public', true)
        ->whereDoesntHave('members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('creator')
        ->withCount('members')
        ->get();

    return view('study-groups.index', compact('userGroups', 'publicGroups'));
}

  public function show($id)
{
    $studyGroup = StudyGroup::with(['creator', 'members.user'])->withCount('members')->findOrFail($id);
    $user = Auth::user();
    
    $isMember = $studyGroup->isMember($user->id);
    $isAdmin = $studyGroup->isAdmin($user->id);
    
    // Check if user is member (unless group is public)
    if (!$studyGroup->is_public && !$isMember) {
        return redirect()->route('study-groups.index')->with('error', 'This is a private group.');
    }
    
    $members = $studyGroup->members()->with('user')->get();
    $pendingInvitations = $studyGroup->invitations()
        ->whereNull('accepted_at')
        ->whereNull('declined_at')
        ->get();

    // Get recent discussions
    $recentDiscussions = \App\Models\Discussion::with(['user', 'replies'])
        ->where('study_group_id', $id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // Get recent resources
    $recentResources = \App\Models\Resource::where('study_group_id', $id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    return view('study-groups.show', compact(
        'studyGroup', 'isMember', 'isAdmin', 'members', 
        'pendingInvitations', 'recentDiscussions', 'recentResources'
    ));
}

// Add this method for the edit form
public function edit($id)
{
    $studyGroup = StudyGroup::findOrFail($id);
    
    // Check if user is admin of the group
    if (!$studyGroup->isAdmin(Auth::id())) {
        return redirect()->route('study-groups.show', $studyGroup->id)
            ->with('error', 'Only group admins can edit the group.');
    }

    return view('study-groups.edit', compact('studyGroup'));
}


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:255',
            'max_members' => 'nullable|integer|min:2|max:50',
            'is_public' => 'boolean'
        ]);

        $studyGroup = StudyGroup::create([
            'name' => $request->name,
            'description' => $request->description,
            'subject' => $request->subject,
            'created_by' => Auth::id(),
            'max_members' => $request->max_members ?? 10,
            'is_public' => $request->is_public ?? true,
            'join_code' => (new StudyGroup)->generateJoinCode()
        ]);

        // Add creator as admin member
        StudyGroupMember::create([
            'study_group_id' => $studyGroup->id,
            'user_id' => Auth::id(),
            'role' => 'admin'
        ]);

        return response()->json([
            'message' => 'Study group created successfully',
            'group' => $studyGroup->load('creator', 'members')
        ], 201);
    }

    

    public function update(Request $request, $id)
    {
        $studyGroup = StudyGroup::findOrFail($id);
        
        // Check if user is admin of the group
        if (!$studyGroup->isAdmin(Auth::id())) {
            return response()->json(['message' => 'Only group admins can update the group'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:255',
            'max_members' => 'sometimes|integer|min:2|max:50',
            'is_public' => 'sometimes|boolean'
        ]);

        $studyGroup->update($request->only([
            'name', 'description', 'subject', 'max_members', 'is_public'
        ]));

        return response()->json([
            'message' => 'Study group updated successfully',
            'group' => $studyGroup
        ]);
    }

    public function destroy($id)
    {
        $studyGroup = StudyGroup::findOrFail($id);
        
        // Check if user is admin of the group
        if (!$studyGroup->isAdmin(Auth::id())) {
            return response()->json(['message' => 'Only group admins can delete the group'], 403);
        }

        $studyGroup->delete();

        return response()->json(['message' => 'Study group deleted successfully']);
    }

    public function join(Request $request, $id)
    {
        $studyGroup = StudyGroup::findOrFail($id);
        
        // Check if group is joinable
        if (!$studyGroup->is_public && !$request->has('join_code')) {
            return response()->json(['message' => 'This group requires a join code'], 403);
        }

        if (!$studyGroup->is_public && $request->join_code !== $studyGroup->join_code) {
            return response()->json(['message' => 'Invalid join code'], 403);
        }

        // Check if user is already a member
        if ($studyGroup->isMember(Auth::id())) {
            return response()->json(['message' => 'You are already a member of this group'], 409);
        }

        // Check if group has reached max members
        if ($studyGroup->members()->count() >= $studyGroup->max_members) {
            return response()->json(['message' => 'This group has reached maximum members'], 403);
        }

        // Add user as member
        StudyGroupMember::create([
            'study_group_id' => $studyGroup->id,
            'user_id' => Auth::id(),
            'role' => 'member'
        ]);

        return response()->json(['message' => 'Successfully joined the study group']);
    }

    public function leave($id)
    {
        $studyGroup = StudyGroup::findOrFail($id);
        
        // Check if user is a member
        if (!$studyGroup->isMember(Auth::id())) {
            return response()->json(['message' => 'You are not a member of this group'], 403);
        }

        // Check if user is the last admin
        if ($studyGroup->isAdmin(Auth::id()) && $studyGroup->members()->where('role', 'admin')->count() === 1) {
            return response()->json(['message' => 'You cannot leave as the last admin. Please assign another admin first or delete the group.'], 403);
        }

        // Remove user from group
        $studyGroup->members()->where('user_id', Auth::id())->delete();

        return response()->json(['message' => 'Successfully left the study group']);
    }

    public function create()
{
    return view('study-groups.create');
}

}