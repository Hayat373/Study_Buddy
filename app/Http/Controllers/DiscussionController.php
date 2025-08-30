<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\StudyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index($groupId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        
        // Check if user is member (unless group is public)
        if (!$studyGroup->is_public && !$studyGroup->isMember(Auth::id())) {
            return redirect()->route('study-groups.show', $studyGroup->id)
                ->with('error', 'You must be a member to view discussions.');
        }

        $discussions = Discussion::with(['user', 'replies.user'])
            ->where('study_group_id', $groupId)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('study-groups.discussions.index', compact('studyGroup', 'discussions'));
    }

    public function create($groupId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        
        // Check if user is member (unless group is public)
        if (!$studyGroup->is_public && !$studyGroup->isMember(Auth::id())) {
            return redirect()->route('study-groups.show', $studyGroup->id)
                ->with('error', 'You must be a member to create discussions.');
        }

        return view('study-groups.discussions.create', compact('studyGroup'));
    }

    public function store(Request $request, $groupId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $studyGroup = StudyGroup::findOrFail($groupId);
        
        // Check if user is member (unless group is public)
        if (!$studyGroup->is_public && !$studyGroup->isMember(Auth::id())) {
            return redirect()->route('study-groups.show', $studyGroup->id)
                ->with('error', 'You must be a member to create discussions.');
        }

        Discussion::create([
            'study_group_id' => $studyGroup->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content
        ]);

        return redirect()->route('study-groups.discussions.index', $studyGroup->id)
            ->with('success', 'Discussion created successfully!');
    }

    public function show($groupId, $discussionId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        
        // Check if user is member (unless group is public)
        if (!$studyGroup->is_public && !$studyGroup->isMember(Auth::id())) {
            return redirect()->route('study-groups.show', $studyGroup->id)
                ->with('error', 'You must be a member to view discussions.');
        }

        $discussion = Discussion::with(['user', 'allReplies.user', 'allReplies.replies.user'])
            ->where('study_group_id', $groupId)
            ->findOrFail($discussionId);

        // Increment view count or add other tracking if needed

        return view('study-groups.discussions.show', compact('studyGroup', 'discussion'));
    }

    public function pin($groupId, $discussionId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        $discussion = Discussion::where('study_group_id', $groupId)->findOrFail($discussionId);

        // Check if user is admin
        if (!$studyGroup->isAdmin(Auth::id())) {
            return redirect()->back()->with('error', 'Only group admins can pin discussions.');
        }

        $discussion->update(['is_pinned' => !$discussion->is_pinned]);

        return redirect()->back()->with('success', 
            $discussion->is_pinned ? 'Discussion pinned!' : 'Discussion unpinned!');
    }

    public function destroy($groupId, $discussionId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        $discussion = Discussion::where('study_group_id', $groupId)->findOrFail($discussionId);

        // Check if user owns the discussion or is admin
        if ($discussion->user_id !== Auth::id() && !$studyGroup->isAdmin(Auth::id())) {
            return redirect()->back()->with('error', 'You are not authorized to delete this discussion.');
        }

        $discussion->delete();

        return redirect()->route('study-groups.discussions.index', $studyGroup->id)
            ->with('success', 'Discussion deleted successfully!');
    }
}