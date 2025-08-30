<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\StudyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index($groupId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        $resources = Resource::with('user')
            ->where('study_group_id', $groupId)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('study-groups.resources.index', compact('studyGroup', 'resources'));
    }

    public function create($groupId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        return view('study-groups.resources.create', compact('studyGroup'));
    }

    public function store(Request $request, $groupId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240' // 10MB max
        ]);

        $studyGroup = StudyGroup::findOrFail($groupId);
        $file = $request->file('file');

        // Store file
        $filePath = $file->store('study-group-resources/' . $studyGroup->id, 'public');

        Resource::create([
            'study_group_id' => $studyGroup->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize()
        ]);

        return redirect()->route('study-groups.resources.index', $studyGroup->id)
            ->with('success', 'Resource uploaded successfully!');
    }

    public function download($groupId, $resourceId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        $resource = Resource::where('study_group_id', $groupId)->findOrFail($resourceId);

        // Check if user is member of the group
        if (!$studyGroup->isMember(Auth::id()) && !$studyGroup->is_public) {
            return redirect()->back()->with('error', 'You must be a member to download resources.');
        }

        // Increment download count
        $resource->incrementDownloadCount();

        return Storage::disk('public')->download($resource->file_path, $resource->file_name);
    }

    public function destroy($groupId, $resourceId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        $resource = Resource::where('study_group_id', $groupId)->findOrFail($resourceId);

        // Check if user owns the resource or is admin
        if ($resource->user_id !== Auth::id() && !$studyGroup->isAdmin(Auth::id())) {
            return redirect()->back()->with('error', 'You are not authorized to delete this resource.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($resource->file_path);

        // Delete record
        $resource->delete();

        return redirect()->back()->with('success', 'Resource deleted successfully!');
    }
}