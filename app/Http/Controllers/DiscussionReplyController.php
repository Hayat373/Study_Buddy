<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionReplyController extends Controller
{
    public function store(Request $request, $groupId, $discussionId)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:discussion_replies,id'
        ]);

        $discussion = Discussion::where('study_group_id', $groupId)->findOrFail($discussionId);

        DiscussionReply::create([
            'discussion_id' => $discussion->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Reply posted successfully!');
    }

    public function destroy($groupId, $discussionId, $replyId)
    {
        $discussion = Discussion::where('study_group_id', $groupId)->findOrFail($discussionId);
        $reply = DiscussionReply::findOrFail($replyId);

        // Check if user owns the reply or is admin
        $studyGroup = $discussion->studyGroup;
        if ($reply->user_id !== Auth::id() && !$studyGroup->isAdmin(Auth::id())) {
            return redirect()->back()->with('error', 'You are not authorized to delete this reply.');
        }

        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted successfully!');
    }
}