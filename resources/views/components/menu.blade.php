<?php

use App\Models\User;
use App\Models\Workspace;
use App\Models\LeaveRequest;
use App\Models\ResourceRequest;
use Chatify\ChatifyMessenger;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\MaterialRequestResponse;
use App\Models\EquipmentRequestResponse;
use App\Models\LaborRequestResponse;
use App\Models\DamageController;
$user = getAuthenticatedUser();

if (isAdminOrHasAllDataAccess()) {
    $workspaces = Workspace::all()->take(5);
    $total_workspaces = Workspace::count();
} else {
    $workspaces = $user->workspaces;
    $total_workspaces = count($workspaces);
    $workspaces = $user->workspaces->skip(0)->take(5);
}
$countRequest = ResourceRequest::where('status', 'Pending')->count();
$countRequestLabor = ResourceRequest::where('status', 'Pending')->where('type','labor')->count();

$countmaterialresiurce =  MaterialRequestResponse::where('status', 'Pending')->count();
$countequipmentresource =  EquipmentRequestResponse::where('status', 'Pending')->count();
$countlaborrsource =  LaborRequestResponse::where('status', 'Pending')->count();
$totalresource = $countmaterialresiurce  +   $countequipmentresource + $countlaborrsource;
$current_workspace_id = session()->get('workspace_id');
$current_workspace = Workspace::find($current_workspace_id);

// Check if the current workspace is in the list of workspaces retrieved
$workspace_ids = $workspaces->pluck('id')->toArray();
if (!in_array($current_workspace_id, $workspace_ids)) {
    // If not, prepend the current workspace to the list
    $current_workspace = Workspace::find($current_workspace_id);
    $workspaces->prepend($current_workspace);
    // If there are more than 5 workspaces, remove the last one
    if ($workspaces->count() > 5) {
        $workspaces->pop();
    }
}


$current_workspace_title = $current_workspace->title ?? 'No workspace(s) found';

$messenger = new ChatifyMessenger();
$unread = $messenger->totalUnseenMessages();
$pending_todos_count = $user->todos(0)->count();
$ongoing_meetings_count = $user->meetings('ongoing')->count();
$query = LeaveRequest::where('status', 'pending')
    ->where('workspace_id', session()->get('workspace_id'));

if (!is_admin_or_leave_editor()) {
    $query->where('user_id', $user->id);
}
$pendingLeaveRequestsCount = $query->count();

?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme menu-container">
    <div class="app-brand demo">
        <a href="/home" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{asset($general_settings['full_logo'])}}" width="200px" alt="" />
            </span>
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Taskify</span> -->
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="btn-group dropend px-2">
        <button type="button" class="btn btn-primary {{getAuthenticatedUser()->hasVerifiedEmail() || getAuthenticatedUser()->hasRole('admin')?'dropdown-toggle':''}}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">            
            {{ strlen($current_workspace->title) > 22 ? substr($current_workspace->title, 0, 22) . '...' : $current_workspace->title }}
        </button>

        @if(getAuthenticatedUser()->hasVerifiedEmail() || getAuthenticatedUser()->hasRole('admin'))
        <ul class="dropdown-menu">
            @foreach ($workspaces as $workspace)
            <?php $checked = $workspace->id == session()->get('workspace_id') ? "<i class='menu-icon tf-icons bx bx-check-square text-primary'></i>" : "<i class='menu-icon tf-icons bx bx-square text-solid'></i>" ?>
            <li><a class="dropdown-item" href="/workspaces/switch/{{$workspace->id}}"><?= $checked ?>{{$workspace->title}}</a></li>
            @endforeach
            <li>
                <hr class="dropdown-divider" />
            </li>
            @if ($user->can('manage_workspaces'))
            <li><a class="dropdown-item" href="/workspaces"><i class='menu-icon tf-icons bx bx-bar-chart-alt-2 text-success'></i><?= get_label('manage_workspaces', 'Manage workspaces') ?> <?= $total_workspaces > 5 ? '<span class="badge badge-center bg-primary"> + ' . ($total_workspaces - 5) . '</span>' : "" ?></a></li>
            @if ($user->can('create_workspaces'))
            <li><a class="dropdown-item" href="/workspaces/create"><i class='menu-icon tf-icons bx bx-plus text-warning'></i><?= get_label('create_workspace', 'Create workspace') ?></a></span></li>
            <!-- <li><span data-bs-toggle="modal" data-bs-target="#create_workspace_modal"><a class="dropdown-item" href="javascript:void(0);"><i class='menu-icon tf-icons bx bx-plus text-warning'></i><?= get_label('create_workspace', 'Create workspace') ?></a></span></li> -->
            @endif
            @if ($user->can('create_workspaces'))
            <li><a class="dropdown-item" href="/workspaces/edit/<?= session()->get('workspace_id') ?>"><i class='menu-icon tf-icons bx bx-edit text-info'></i><?= get_label('edit_workspace', 'Edit workspace') ?></a></li>
            @endif
            @endif
            <li><a class="dropdown-item" href="#" id="remove-participant"><i class='menu-icon tf-icons bx bx-exit text-danger'></i><?= get_label('remove_me_from_workspace', 'Remove me from workspace') ?></a></li>
        </ul>
        @endif
    </div>
    <ul class="menu-inner py-1">
        <hr class="dropdown-divider" />
        <!-- Dashboard -->
        @if (getAuthenticatedUser()->hasRole('admin') || getAuthenticatedUser()->hasRole('Project Planner') )
        <li class="menu-item {{ Request::is('home') ? 'active' : '' }}">
            <a href="/home" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle text-danger"></i>
                <div><?= get_label('dashboard', 'Dashboard') ?></div>
            </a>
        </li>
        @endif
        @if (getAuthenticatedUser()->hasRole('member'))
        <li class="menu-item {{ Request::is('home') ? 'active' : '' }}">
            <a href="/home" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle text-danger"></i>
                <div><?= get_label('dashboard', 'Dashboard') ?></div>
            </a>
        </li>
@endif
@if (getAuthenticatedUser()->hasRole('member'))
        <li class="menu-item {{ Request::is('home') ? 'active' : '' }}">
            <a href="/home" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle text-danger"></i>
                <div><?= get_label('projects', 'Projects') ?></div>
            </a>
        </li>
@endif
@if (getAuthenticatedUser()->hasRole('member'))
        <li class="menu-item {{ Request::is('/user/task') ? 'active' : '' }}">
            <a href="/user/task" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle text-danger"></i>
                <div><?= get_label('tasks', 'Tasks') ?></div>
            </a>
        </li>
@endif
@if (getAuthenticatedUser()->hasRole('member'))
        <li class="menu-item {{ Request::is('/user/activity') ? 'active' : '' }}">
            <a href="/user/activity" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle text-danger"></i>
                <div><?= get_label('activities', 'Activities') ?></div>
            </a>
        </li>
@endif
       
        @if ($user->can('manage_projects'))
        <li class="menu-item {{ Request::is('projects') || Request::is('tags/*') || Request::is('projects/*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-briefcase-alt-2 text-success"></i>
                <div><?= get_label('projects', 'Projects') ?></div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('projects') || Request::is('projects/*') && !Request::is('projects/favorite') ? 'active' : '' }}">
                    <a href="/projects" class="menu-link">
                        <div><?= get_label('manage_projects', 'Manage projects') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('projects/favorite') ? 'active' : '' }}">
                    <a href="/projects/favorite" class="menu-link">
                        <div><?= get_label('favorite_projects', 'Favorite projects') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('tags/*') ? 'active' : '' }}">
                    <a href="/tags/manage" class="menu-link">
                        <div><?= get_label('tags', 'Tags') ?></div>
                    </a>
                </li>
            </ul>
        </li>


        @endif
        @if ($user->can('manage_tasks'))
        @if(getAuthenticatedUser()->hasVerifiedEmail())
        <li class="menu-item {{ Request::is('tasks') || Request::is('tasks/*') ? 'active' : '' }}">
            <a href="/tasks" class="menu-link">
                <i class="menu-icon tf-icons bx bx-task text-primary"></i>
                <div><?= get_label('tasks', 'Tasks') ?></div>
            </a>
        </li>
        @endif
      
        @endif
        @if(getAuthenticatedUser()->hasVerifiedEmail() && getAuthenticatedUser()->hasRole('admin'))
        <li class="menu-item {{ Request::is('work-progress') || Request::is('work-progress/*') ? 'active' : '' }}">
            <a href="/work-progress" class="menu-link">
                <i class="menu-icon tf-icons bx bx-task text-primary"></i>
                <div><?= get_label('Work Progress', 'Work Progress') ?></div>
            </a>
        </li>  
        @endif
        @if( $user->can('manage_admin_schedule') && getAuthenticatedUser()->hasRole('admin'))
        <li class="menu-item {{ Request::is('schedule') || Request::is('schedule/*') ? 'active' : '' }}">
            <a href="/schedule" class="menu-link">
                <i class="menu-icon tf-icons bx bx-task text-primary"></i>
                <div><?= get_label('Schedule', 'Schedule') ?></div>
            </a>
        </li>  
        @endif
        @if( $user->can('manage_member_schedule') &&  getAuthenticatedUser()->hasRole('member'))
        <li class="menu-item {{ Request::is('schedule') || Request::is('schedule/*') ? 'active' : '' }}">
            <a href="/member/schedule" class="menu-link">
                <i class="menu-icon tf-icons bx bx-task text-primary"></i>
                <div><?= get_label('Schedule', 'Schedule') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_master_schedule') )
        <li class="menu-item {{ Request::is('master-schedule') || Request::is('master-schedule/*') ? 'active' : '' }}">
            <a href="/master-schedule" class="menu-link">
                <i class="menu-icon tf-icons bx bx-task text-primary"></i>
                <div><?= get_label('Master schedule', 'Master-schedule') ?></div>
            </a>
        </li>
        @endif
        @if (($user->can('manage_projects') || $user->can('manage_tasks')) && getAuthenticatedUser()->hasRole('admin'))
        <li class="menu-item {{ Request::is('status/manage') ? 'active' : '' }}">
            <a href="/status/manage" class="menu-link">
                <i class='menu-icon tf-icons bx bx-grid-small text-secondary'></i>
                <div><?= get_label('statuses', 'Statuses') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_resource_allocation'))
        <li class="menu-item {{ Request::is('todos') || Request::is('requests-resource') ? 'active' : '' }}">
    <a href="{{ route('request') }}" class="menu-link" aria-label="Requests Resource">
        <i class='menu-icon tf-icons bx bx-folder text-primary'></i> <!-- Changed icon to bx-folder -->
        <div class="d-flex align-items-center">
            <span class="me-2">{{ get_label('requests-resource', 'Requests Resource') }}</span>
        </div>
    </a>
</li>
@endif
@if ($user->can('manage_incoming_requests') && (getAuthenticatedUser()->hasRole('admin') || getAuthenticatedUser()->hasRole('Warehouse Manager')))
<li class="menu-item {{ Request::is('todos') || Request::is('requests/*') ? 'active' : '' }}">
    <a href="{{ route('requests.index') }}" class="menu-link" aria-label="Incoming Requests">
        <i class='menu-icon tf-icons bx bx-list-check text-primary'></i> <!-- Changed text-dark to text-primary -->
        <div class="d-flex align-items-center">
            <span class="me-2">{{ get_label('requests', 'Incoming Requests') }}</span>
            <span class="flex-shrink-0 badge bg-danger rounded-circle d-flex justify-content-center align-items-center" style="width: 20px; height: 20px;">
                {{ $countRequestLabor }}
            </span>
        </div>
    </a>
</li>
@endif
@if ($user->can('manage_incoming_requests') &&  getAuthenticatedUser()->hasRole('HR Manager') )
<li class="menu-item {{ Request::is('todos') || Request::is('requests/*') ? 'active' : '' }}">
    <a href="{{ route('requests.index') }}" class="menu-link" aria-label="Incoming Requests">
        <i class='menu-icon tf-icons bx bx-list-check text-primary'></i> <!-- Changed text-dark to text-primary -->
        <div class="d-flex align-items-center">
            <span class="me-2">{{ get_label('requests', 'Incoming Requests') }}</span>
            <span class="flex-shrink-0 badge bg-danger rounded-circle d-flex justify-content-center align-items-center" style="width: 20px; height: 20px;">
                {{ $countRequestLabor }}
            </span>
        </div>
    </a>
</li>
@endif
      @if ($user->can('manage_incoming_requests') && getAuthenticatedUser()->hasRole('finance'))
<li class="menu-item {{ Request::is('todos') || Request::is('requests/*') ? 'active' : '' }}">
    <a href="{{ route('requests.index') }}" class="menu-link" aria-label="Incoming Requests">
        <i class='menu-icon tf-icons bx bx-list-check text-primary'></i> <!-- Changed text-dark to text-primary -->
        <div class="d-flex align-items-center">
            <span class="me-2">{{ get_label('requests', 'Incoming Requests') }}</span>
            <span class="flex-shrink-0 badge bg-danger rounded-circle d-flex justify-content-center align-items-center" style="width: 20px; height: 20px;">
                {{ $countRequest }}
            </span>
        </div>
    </a>
</li>
@endif
@if ($user->can('manage_inventories'))
<li class="menu-item {{ Request::is('todos') || Request::is('requests/*') ? 'active' : '' }}">
    <a href="{{ route('warehouse.my') }}" class="menu-link" aria-label="My Warehouse">
        <i class='menu-icon tf-icons bx bx-list-check text-primary'></i> <!-- Changed text-dark to text-primary -->
        <div class="d-flex align-items-center">
            <span class="me-2">{{ get_label('mywarehouse', 'My Warehouse') }}</span>
        </div>
    </a>
</li>
<li class="menu-item {{ Request::is('todos') || Request::is('requests/*') ? 'active' : '' }}">
    <a href="{{ route('material2.index') }}" class="menu-link" aria-label="Material">
        <i class='menu-icon tf-icons bx bx-list-check text-primary'></i> <!-- Changed text-dark to text-primary -->
        <div class="d-flex align-items-center">
            <span class="me-2">{{ get_label('mywarehouse', 'Material') }}</span>
        </div>
    </a>
</li>
@endif
@if ($user->can('manage_inventories'))
<li class="menu-item {{ Request::is('todos') || Request::is('requests/*') ? 'active' : '' }}">
    <a href="{{ route('warehouse.my') }}" class="menu-link" aria-label="My Warehouse">
        <i class='menu-icon tf-icons bx bx-list-check text-primary'></i> <!-- Changed text-dark to text-primary -->
        <div class="d-flex align-items-center">
            <span class="me-2">{{ get_label('all_my_warehouse', 'Warehouse Balance') }}</span>
        </div>
    </a>
</li>
@endif
        @if ($user->can('manage_contracts'))

@endif
        @if (($user->can('manage_projects') || $user->can('manage_tasks')) && getAuthenticatedUser()->hasRole('admin'))
        <li class="menu-item {{ Request::is('priority/manage') ? 'active' : '' }}">
            <a href="/priority/manage" class="menu-link">
                <i class='menu-icon tf-icons bx bx-up-arrow-alt text-success'></i>
                <div><?= get_label('priorities', 'Priorities') ?></div>
            </a>
        </li>
        @endif


        @if ($user->can('manage_workspaces'))
        <li class="menu-item {{ Request::is('workspaces') || Request::is('workspaces/*') ? 'active' : '' }}">
            <a href="/workspaces" class="menu-link">
                <i class='menu-icon tf-icons bx bx-check-square text-danger'></i>
                <div><?= get_label('workspaces', 'Workspaces') ?></div>
            </a>
        </li>
        @endif


        @if (Auth::guard('web')->check() && getAuthenticatedUser()->hasRole('admin'))
        <li class="menu-item {{ Request::is('chat') || Request::is('chat/*') ? 'active' : '' }}">
            <a href="/chat" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chat text-warning"></i>
                <div><?= get_label('chat', 'Chat') ?> <span class="flex-shrink-0 badge badge-center bg-danger w-px-20 h-px-20">{{$unread}}</span></div>

            </a>
        </li>
        @endif
        <li class="menu-item {{ Request::is('todos') || Request::is('todos/*') ? 'active' : '' }}">
            <a href="/todos" class="menu-link">
                <i class='menu-icon tf-icons bx bx-list-check text-dark'></i>
                <div><?= get_label('todos', 'Todos') ?> <span class="flex-shrink-0 badge badge-center bg-danger w-px-20 h-px-20">{{0}}</span></div>
            </a>
        </li>
        @if ($user->can('manage_meetings'))
        <li class="menu-item {{ Request::is('meetings') || Request::is('meetings/*') ? 'active' : '' }}">
            <a href="/meetings" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shape-polygon text-success"></i>
                <div><?= get_label('meetings', 'Meetings') ?> <span class="flex-shrink-0 badge badge-center bg-success w-px-20 h-px-20">{{$ongoing_meetings_count}}</span></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_users'))
        <li class="menu-item {{ Request::is('users') || Request::is('users/*') ? 'active' : '' }}">
            <a href="/users" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-primary"></i>
                <div><?= get_label('users', 'Users') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_clients'))
        <li class="menu-item {{ Request::is('clients') || Request::is('clients/*') ? 'active' : '' }}">
            <a href="/clients" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-warning"></i>
                <div><?= get_label('clients', 'Clients') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_clients'))
        <li class="menu-item {{ Request::is('sites') || Request::is('sites/*') ? 'active' : '' }}">
            <a href="/sites" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-warning"></i>
                <div><?= get_label('sites', 'Sites') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('department'))
       <li class="menu-item {{ Request::is('/departments') ? 'active' : '' }}">
            <a href="/departments" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle text-danger"></i>
                <div><?= get_label('debpartments', 'Department') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_contracts'))

        <li class="menu-item {{ Request::is('contracts') || Request::is('contracts/*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-news text-success"></i>
                <?= get_label('contracts', 'Contracts') ?>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('contracts') ? 'active' : '' }}">
                    <a href="/contracts" class="menu-link">
                        <div><?= get_label('manage_contracts', 'Manage contracts') ?></div>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('contracts/contract-types') ? 'active' : '' }}">
                    <a href="/contracts/contract-types" class="menu-link">
                        <div><?= get_label('contract_types', 'Contract types') ?></div>
                    </a>
                </li>
            </ul>
        </li>

        @endif   
        @if ($user->can('manage_clients'))
        <li class="menu-item {{ Request::is('budget') || Request::is('budget/*') ? 'active' : '' }}">
            <a href="/budget/allocate" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-warning"></i>
                <div><?= get_label('budget', 'Budget') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_resource_allocation'))
        <li class="menu-item {{ Request::is('budgetsOverview/*') ? 'active' : '' }}">
            <a href="/budgets/overview" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-warning"></i>
                <div><?= get_label('budgets_overview', 'Budbudget Overview') ?></div>
            </a>
        </li>  
        @endif
        @if ($user->can('manage_resource_allocation'))
<li class="menu-item {{ Request::is('resource-allocation/*') ? 'active' : '' }}">
    <a href="/resource-allocation" class="menu-link">
        <i class='menu-icon tf-icons bx bx-list-check text-primary'></i> <!-- Changed text-dark to text-primary -->
        <div class="d-flex align-items-center">
        <div>{{ get_label('resource-allocation', 'Resource Allocation') }}</div>
            <span class="flex-shrink-0 badge bg-danger rounded-circle d-flex justify-content-center align-items-center" style="width: 20px; height: 20px;">
                {{ $totalresource }}
            </span>
        </div>
    </a>
</li>

@endif
@if ($user->can('manage_inventories'))
    <li class="menu-item {{ Request::is('inventories') || Request::is('tags/*') || Request::is('inventories/*') ? 'active open' : '' }}">
        <a href="javascript:void(0)" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 text-success"></i>
            <div><?= get_label('inventories', 'Inventories') ?></div>
        </a>
        <ul class="menu-sub">
            @if (Auth::guard('web')->check() && getAuthenticatedUser()->hasRole('admin'))
                <li class="menu-item {{ Request::is('warehouses') ? 'active' : '' }}">
                    <a href="/warehouses" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-building text-primary"></i>
                        <div><?= get_label('manage_warehouses', 'Manage Warehouses') ?></div>
                    </a>
                </li>
            @endif
          
            <li class="menu-item {{ Request::is('materials/*') || Request::is('equipments/*') || Request::is('labors/*') || Request::is('transfer/*') || Request::is('inventory-history/*') || Request::is('inventory-report/*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-clipboard text-warning"></i>
                    <div><?= get_label('inventories', 'Resource') ?></div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::is('materials') || (Request::is('manage_material/*') && !Request::is('manage_material/')) ? 'active' : '' }}">
                        <a href="/materials" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cube text-info"></i>
                            <div><?= get_label('manage_material', 'Manage Materials') ?></div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('equipments') ? 'active' : '' }}">
                        <a href="/equipments" class="menu-link">
                            <div><?= get_label('manage_equipment', 'Manage Equipment') ?></div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('equipment_types') ? 'active' : '' }}">
                        <a href="/equipment_types" class="menu-link">
                            <div><?= get_label('equipment_types', 'Equipment Types') ?></div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item {{ Request::is('transfer') ? 'active' : '' }}">
                <a href="/transfer" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-transfer text-secondary"></i>
                    <div><?= get_label('transfers', 'Transfers') ?></div>
                </a>
            </li>
        @if (Auth::guard('web')->check())
        <li class="menu-item {{ Request::is('incoming/transfer') || Request::is('incoming/transfer/*') ? 'active' : '' }}">
            <a href="/incoming/transfer" class="menu-link">
                <div><?= get_label('incoming_transfer', 'Incoming Trasfer') ?> <span class="flex-shrink-0 badge badge-center bg-danger w-px-20 h-px-20">{{$pendingLeaveRequestsCount}}</span></div>
            </a>
        </li>
        @endif
          <li class="menu-item {{ Request::is('dispatch') ? 'active' : '' }}">
                <a href="/dispatch" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-transfer text-secondary"></i>
                    <div><?= get_label('dispatch', 'Dispatch') ?></div>
                </a>
            </li>
            @if (Auth::guard('web')->check())
        <li class="menu-item {{ Request::is('delivery') || Request::is('delivery/*') ? 'active' : '' }}">
            <a href="/delivery" class="menu-link">
                <div><?= get_label('delivery', 'Delivery(GRN)') ?></div>
            </a>
        </li>
        @endif
        <li class="menu-item {{ Request::is('transfer') ? 'active' : '' }}">
                <a href="/transfer" class="menu-link">
                    <div><?= get_label('purchase_requisition', 'Purchase Requisition') ?></div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('damages') || Request::is('damages/*') ? 'active' : '' }}">
            <a href="/damages" class="menu-link">
                <i class='menu-icon tf-icons bx bx-list-check text-dark'></i>
                <div><?= get_label('damages', 'Damages & Return') ?> </div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('inventory-history') ? 'active' : '' }}">
                <a href="/inventory-history" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history text-dark"></i>
                    <div><?= get_label('inventory-history', 'Inventory History') ?></div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('inventory-report') ? 'active' : '' }}">
                <a href="/inventory-report" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file text-muted"></i>
                    <div><?= get_label('report', 'Report') ?></div>
                </a>
            </li>
        </ul>
    </li>
@endif
        @if ($user->can('manage_hr'))
        <li class="menu-item {{ Request::is('hr') || Request::is('hr/*') ? 'active' : '' }}">
            <a href="/hrm" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-warning"></i>
                <div><?= get_label('hr', 'Hr Dashboard') ?></div>
            </a>
        </li>  
        @endif
        @if ($user->can('manage_hr'))
        <li class="menu-item {{ Request::is('employee_possition') || Request::is('employee_possition/*') ? 'active' : '' }}">
            <a href="/employee_possition" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-warning"></i>
                <div><?= get_label('employee_possition', 'Job Position') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_hr'))
        <li class="menu-item {{ Request::is('labor') || Request::is('labor/*') ? 'active' : '' }}">
            <a href="/labors" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-warning"></i>
                <div><?= get_label('labor', 'Labor') ?></div>
            </a>
        </li>
        @endif
        @if ($user->can('manage_payslips'))
        <li class="menu-item {{ Request::is('payslips') || Request::is('payslips/*') || Request::is('allowances') || Request::is('deductions') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box text-warning"></i>
                <?= get_label('payslips', 'Payslips') ?>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('payslips') || Request::is('payslips/*') ? 'active' : '' }}">
                    <a href="/payslips" class="menu-link">
                        <div><?= get_label('manage_payslips', 'Manage payslips') ?></div>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('allowances') ? 'active' : '' }}">
                    <a href="/allowances" class="menu-link">
                        <div><?= get_label('allowances', 'Allowances') ?></div>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('deductions') ? 'active' : '' }}">
                    <a href="/deductions" class="menu-link">
                        <div><?= get_label('deductions', 'Deductions') ?></div>
                    </a>
                </li>
            </ul>
        </li>
        @endif


        @if ($user->can('manage_estimates_invoices') || $user->can('manage_expenses'))
        <li class="menu-item {{ Request::is('estimates-invoices') || Request::is('estimates-invoices/*') || Request::is('taxes') || Request::is('payment-methods') || Request::is('payments') || Request::is('units') || Request::is('items') || Request::is('expenses') || Request::is('expenses/*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box text-success"></i>
                <?= get_label('finance', 'Finance') ?>
            </a>
            <ul class="menu-sub">
                @if ($user->can('manage_expenses'))
                <li class="menu-item {{ Request::is('expenses') || Request::is('expenses/*') ? 'active' : '' }}">
                    <a href="/expenses" class="menu-link">
                        <div><?= get_label('expenses', 'Expenses') ?></div>
                    </a>
                </li>
                @endif

                @if ($user->can('manage_estimates_invoices'))
                <li class="menu-item {{ Request::is('estimates-invoices') || Request::is('estimates-invoices/*') ? 'active' : '' }}">
                    <a href="/estimates-invoices" class="menu-link">
                        <div><?= get_label('etimates_invoices', 'Estimates/Invoices') ?></div>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('payments') ? 'active' : '' }}">
                    <a href="/payments" class="menu-link">
                        <div><?= get_label('payments', 'Payments') ?></div>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('payment-methods') ? 'active' : '' }}">
                    <a href="/payment-methods" class="menu-link">
                        <div><?= get_label('payment_methods', 'Payment methods') ?></div>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('taxes') ? 'active' : '' }}">
                    <a href="/taxes" class="menu-link">
                        <div><?= get_label('taxes', 'Taxes') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('units') ? 'active' : '' }}">
                    <a href="/units" class="menu-link">
                        <div><?= get_label('units', 'Units') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('items') ? 'active' : '' }}">
                    <a href="/items" class="menu-link">
                        <div><?= get_label('items', 'Items') ?></div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif


        <li class="menu-item {{ Request::is('notes') || Request::is('notes/*') ? 'active' : '' }}">
            <a href="/notes" class="menu-link">
                <i class='menu-icon tf-icons bx bx-notepad text-primary'></i>
                <div><?= get_label('notes', 'Notes') ?></div>
            </a>
        </li>


        @if (Auth::guard('web')->check())
        <li class="menu-item {{ Request::is('leave-requests') || Request::is('leave-requests/*') ? 'active' : '' }}">
            <a href="/leave-requests" class="menu-link">
                <i class='menu-icon tf-icons bx bx-right-arrow-alt text-danger'></i>
                <div><?= get_label('leave_requests', 'Leave requests') ?> <span class="flex-shrink-0 badge badge-center bg-danger w-px-20 h-px-20">{{$pendingLeaveRequestsCount}}</span></div>
            </a>
        </li>
        @endif
      
        @if ($user->can('manage_activity_log'))
        <li class="menu-item {{ Request::is('activity-log') || Request::is('activity-log/*') ? 'active' : '' }}">
            <a href="/activity-log" class="menu-link">
                <i class='menu-icon tf-icons bx bx-line-chart text-warning'></i>
                <div><?= get_label('activity_log', 'Activity log') ?></div>
            </a>
        </li>
        @endif
        @role('admin')
        <li class="menu-item {{ Request::is('settings') || Request::is('roles/*') || Request::is('settings/*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box text-success"></i>
                <div data-i18n="User interface"><?= get_label('settings', 'Settings') ?></div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('settings/general') ? 'active' : '' }}">
                    <a href="/settings/general" class="menu-link">
                        <div><?= get_label('general', 'General') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/permission') || Request::is('roles/*') ? 'active' : '' }}">
                    <a href="/settings/permission" class="menu-link">
                        <div><?= get_label('permissions', 'Permissions') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/languages') || Request::is('settings/languages/create') ? 'active' : '' }}">
                    <a href="/settings/languages" class="menu-link">
                        <div><?= get_label('languages', 'Languages') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/email') ? 'active' : '' }}">
                    <a href="/settings/email" class="menu-link">
                        <div><?= get_label('email', 'Email') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/sms-gateway') ? 'active' : '' }}">
                    <a href="/settings/sms-gateway" class="menu-link">
                        <div><?= get_label('sms_gateway', 'SMS gateway') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/pusher') ? 'active' : '' }}">
                    <a href="/settings/pusher" class="menu-link">
                        <div><?= get_label('pusher', 'Pusher') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/media-storage') ? 'active' : '' }}">
                    <a href="/settings/media-storage" class="menu-link">
                        <div><?= get_label('media_storage', 'Media storage') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/templates') ? 'active' : '' }}">
                    <a href="/settings/templates" class="menu-link">
                        <div><?= get_label('templates', 'Templates') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/system-updater') ? 'active' : '' }}">
                    <a href="/settings/system-updater" class="menu-link">
                        <div><?= get_label('system_updater', 'System updater') ?></div>
                    </a>
                </li>

            </ul>
        </li>
        @endrole
        <li class="menu-item {{ Request::is('report') || Request::is('roles/*') || Request::is('report/*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box text-success"></i>
                <div data-i18n="User interface"><?= get_label('report', 'Report') ?></div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('project/report') ? 'active' : '' }}">
                    <a href="/project/report" class="menu-link">
                        <div><?= get_label('project', 'Projects') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('report/taskReport') || Request::is('roles/*') ? 'active' : '' }}">
                    <a href="/task/report" class="menu-link">
                        <div><?= get_label('tasks', 'Tasks') ?></div>
                    </a>
                </li>
            </ul>
        </li>

@if (getAuthenticatedUser()->hasRole('member'))
       
@endif
    </ul>
</aside>