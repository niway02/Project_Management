<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Workspace;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\DeletionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class NotificationsController extends Controller
{
    protected $workspace;
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $types = [
            'project',
            'task',
            'workspace'
            // Add more types as needed
        ];
        $notifications_count = $this->user->notifications()->count();
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;

        return view('notifications.list', ['notifications_count' => $notifications_count, 'users' => $users, 'clients' => $clients, 'types' => $types]);
    }

    public function mark_all_as_read()
    {
        $notifications = $this->user->notifications()->get();

        foreach ($notifications as $notification) {
            $this->user->notifications()->updateExistingPivot($notification->id, ['read_at' => now()]);
        }
        Session::flash('message', 'All notifications marked as read.');
        return response()->json(['error' => false]);
    }



    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = (request('status')) ? request('status') : "";
        $type = (request('type')) ? request('type') : "";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $client_id = (request('client_id')) ? request('client_id') : "";
        if ($user_id && isAdminOrHasAllDataAccess()) {
            $user = User::findOrFail($user_id);
            $notifications = $user->notifications();
        } elseif ($client_id && isAdminOrHasAllDataAccess()) {
            $client = Client::findOrFail($client_id);
            $notifications = $client->notifications();
        } else {
            $notifications = isAdminOrHasAllDataAccess() ? $this->workspace->notifications() : $this->user->notifications();
        }
        if ($search) {
            $notifications = $notifications->where(function ($query) use ($search) {
                $query->where('id', 'like', '%' . $search . '%')
                    ->orWhere('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        // Check if the logged-in user is a user or a client
        if (isClient()) {
            $pivotTable = 'client_notifications';
        } else {
            $pivotTable = 'notification_user';
        }

        if ($status === "read") {
            $notifications = $notifications->where(function ($query) use ($pivotTable) {
                $query->whereNotNull("{$pivotTable}.read_at");
            });
        } elseif ($status === "unread") {
            $notifications = $notifications->where(function ($query) use ($pivotTable) {
                $query->whereNull("{$pivotTable}.read_at");
            });
        }

        if ($type) {
            $notifications = $notifications->where('type', $type);
        }

        $total = $notifications->count();


        $notifications = $notifications->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($notification) {
                // Construct the base URL based on the notification type
                $baseUrl = '';
                if ($notification->type == 'project') {
                    $baseUrl = '/projects/information/' . $notification->type_id;
                } else if ($notification->type == 'task') {
                    $baseUrl = '/tasks/information/' . $notification->type_id;
                }
                $readAt = isset($notification->pivot->read_at) ? $notification->pivot->read_at : $notification->read_at;
                $markAsAction = is_null($readAt) ? get_label('mark_as_read', 'Mark as read') : get_label('mark_as_unread', 'Mark as unread');
                $iconClass = is_null($readAt) ? 'bx bx-check text-secondary mx-1' : 'bx bx-check-double text-success mx-1';

                // Check if the notification is assigned to the currently logged-in user or client
                $isAssignedToCurrentUser = $notification->users->contains('id', $this->user->id) || $notification->clients->contains('id', $this->user->id);

                // Construct the HTML for the mark as read/unread action only if the notification is assigned to the current user
                if ($isAssignedToCurrentUser) {
                    $actionsHtml = '<a href="javascript:void(0)" data-id="' . $notification->id . '" data-needconfirm="true" title="' . $markAsAction . '" class="card-link update-notification-status"><i class="' . $iconClass . '"></i></a>';
                } else {
                    // If the notification is not assigned to the current user, do not display mark as read/unread option
                    $actionsHtml = '';
                }

                $statusBadge = is_null($readAt) ? '<span class="badge bg-danger">' . get_label('unread', 'Unread') . '</span>' : '<span class="badge bg-success">' . get_label('read', 'Read') . '</span>';

                // Append view and delete options
                $actionsHtml .= '<a href="' . $baseUrl . '" title="' . get_label('view', 'View') . '" class="card-link update-notification-status" data-id="' . $notification->id . '"><i class="bx bx-info-circle mx-1"></i></a>' .
                    '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $notification->id . '" data-type="notifications">' .
                    '<i class="bx bx-trash text-danger mx-1"></i>' .
                    '</button>';

                return [
                    'id' => $notification->id,
                    'title' => $notification->title . '<br><span class="text-muted">' . $notification->created_at->diffForHumans() . ' (' . format_date($notification->created_at, true) . ')' . '</span>',
                    'users' => $notification->users,
                    'clients' => $notification->clients,
                    'type' => $notification->type,
                    'type_id' => $notification->type_id,
                    'message' => $notification->message,
                    'status' => $statusBadge,
                    'type' => ucfirst(str_replace('_', ' ', $notification->type)),
                    'read_at' => format_date($readAt, true),
                    'created_at' => format_date($notification->created_at, true),
                    'updated_at' => format_date($notification->updated_at, true),
                    'actions' => $actionsHtml,
                ];
            });

        foreach ($notifications->items() as $notification => $collection) {
            foreach ($collection['clients'] as $i => $client) {
                $collection['clients'][$i] = "<a href='/clients/profile/" . $client->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $client['first_name'] . " " . $client['last_name'] . "'>
                    <img src='" . ($client['photo'] ? asset('storage/' . $client['photo']) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle' />
                    </li></a>";
            };
        }

        foreach ($notifications->items() as $notification => $collection) {
            foreach ($collection['users'] as $i => $user) {
                $collection['users'][$i] = "<a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user['first_name'] . " " . $user['last_name'] . "'>
                    <img src='" . ($user['photo'] ? asset('storage/' . $user['photo']) : asset('storage/photos/no-image.jpg')) . "' class='rounded-circle' />
                    </li></a>";
            };
        }

        return response()->json([
            "rows" => $notifications->items(),
            "total" => $total,
        ]);
    }



    public function destroy($id)
    {
        // Find the notification
        $notification = Notification::findOrFail($id);

        // Detach the notification from the user
        $this->user->notifications()->detach($notification);

        // Check if the notification is still associated with any users or clients
        if ($notification->users()->count() === 0 && $notification->clients()->count() === 0) {
            // If not associated with any users or clients, delete the notification
            $notification->delete();
        }

        return response()->json(['message' => 'Notification deleted successfully']);
    }


    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:notifications,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $notification = Notification::findOrFail($id);
            $this->user->notifications()->detach($notification);

            // Check if the notification is still associated with any users or clients
            if ($notification->users()->count() === 0 && $notification->clients()->count() === 0) {
                // If not associated with any users or clients, delete the notification
                $notification->delete();
            }
        }

        return response()->json(['error' => false, 'message' => 'Notification(s) deleted successfully.']);
    }

    public function update_status(Request $request)
    {
        $notificationId = $request->input('id');
        $needConfirm = $request->input('needConfirm') || false;
        // Find the notification
        $notification =  $this->user->notifications()->findOrFail($notificationId);
        $readAt = isset($notification->pivot->read_at) ? $notification->pivot->read_at : $notification->read_at;
        if ($needConfirm) {
            // Toggle the status
            if (is_null($readAt)) {
                // If the notification is currently unread, mark it as read
                $this->user->notifications()->updateExistingPivot($notification->id, ['read_at' => now()]);
                $message = 'Notification marked as read successfully';
            } else {
                // If the notification is currently read, mark it as unread
                $this->user->notifications()->updateExistingPivot($notification->id, ['read_at' => null]);
                $message = 'Notification marked as unread successfully';
            }

            // Return a response indicating success
            return response()->json(['error' => false, 'message' => $message]);
        } else {
            if (is_null($readAt)) {
                $this->user->notifications()->updateExistingPivot($notification->id, ['read_at' => now()]);
            }
        }
    }

    public function getUnreadNotifications()
    {
        $unreadNotificationsCount = $this->user->notifications->where('pivot.read_at', null)->count();
        $unreadNotifications = $this->user->notifications()
            ->wherePivot('read_at', null)
            ->getQuery()
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();
        $unreadNotificationsHtml = view('partials.unread_notifications')
            ->with('unreadNotificationsCount', $unreadNotificationsCount)
            ->with('unreadNotifications', $unreadNotifications)
            ->render();

        // Return JSON response with count and HTML
        return response()->json([
            'count' => $unreadNotificationsCount,
            'html' => $unreadNotificationsHtml
        ]);
    }
}
