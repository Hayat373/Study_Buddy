<x-mail::message>
# Study Group Invitation

You have been invited by {{ $inviter->name }} to join the study group "{{ $studyGroup->name }}".

**Group Description:**  
{{ $studyGroup->description }}

**Subject:** {{ $studyGroup->subject }}

<x-mail::button :url="route('study-groups.invitation.accept', ['token' => $invitation->token])">
Accept Invitation
</x-mail::button>

If you prefer not to join this study group, you can decline the invitation by clicking the link below:

[Decline Invitation]({{ route('study-groups.invitation.decline', ['token' => $invitation->token]) }})

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>