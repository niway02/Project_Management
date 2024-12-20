@if ($unreadNotificationsCount > 0)
@foreach ($unreadNotifications as $notification)

<li>
    <a class="dropdown-item update-notification-status" data-id="{{$notification->id}}" href="{{$notification->type=='project' ? '/projects/information/'.$notification->type_id : ($notification->type=='task' ? '/tasks/information/'.$notification->type_id : ($notification->type=='workspace' ? '/workspaces' : '/meetings'))}}">
        <div class="d-flex align-items-center">
            <div class="me-auto fw-semibold">{{$notification->title}} <small class="text-muted mx-2">{{ $notification->created_at->diffForHumans() }}</small></div>
            <i class="bx bx-bell me-2"></i>
        </div>
        <div class="mt-2">{{ strlen($notification->message) > 50 ? substr($notification->message, 0, 50) . '...' : $notification->message }}</div>
    </a>
</li>
<li>
    <div class="dropdown-divider"></div>
</li>

@endforeach
@else
<li class="p-5 d-flex align-items-center justify-content-center">
    <span>{{ get_label('no_unread_notifications', 'No unread notifications') }}</span>
</li>
<li>
    <div class="dropdown-divider"></div>
</li>
@endif