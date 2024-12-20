<?php

/** custom taskhub config */
return [
    /*
    |--------------------------------------------------------------------------
    | Project status labels
    |-------------------------------------------------------------------------- 
    */
    'project_status_labels' => [
        'completed' => "success",
        "onhold" => "warning",
        "ongoing" => "info",
        "started" => "primary",
        "cancelled" => "danger"
    ],
    'task_status_labels' => [
        'completed' => "success",
        "onhold" => "warning",
        "started" => "primary",
        "cancelled" => "danger",
        "ongoing" => "info"
    ],
    'role_labels' => [
        'admin' => "info",
        "Super Admin" => "danger",
        "HR" => "primary",
        "member" => "warning",
        'default' => "dark"
    ],
    'priority_labels' => [
        'low' => "success",
        "high" => "danger",
        "medium" => "warning"
    ],
    'permissions' => [
        'Projects' =>  array('create_projects', 'manage_projects', 'edit_projects', 'delete_projects'),
        'Tasks' =>  array('create_tasks', 'manage_tasks', 'edit_tasks', 'delete_tasks'),
        'Users' =>  array('create_users', 'manage_users', 'edit_users', 'delete_users'),
        'Clients' =>  array('create_clients', 'manage_clients', 'edit_clients', 'delete_clients'),
        'Workspaces' =>  array('create_workspaces', 'manage_workspaces', 'edit_workspaces', 'delete_workspaces'),
        'Meetings' =>  array('create_meetings', 'manage_meetings', 'edit_meetings', 'delete_meetings'),
        'Contracts' =>  array('create_contracts', 'manage_contracts', 'edit_contracts', 'delete_contracts'),
        'Timesheet' =>  array('create_timesheet', 'manage_timesheet', 'delete_timesheet'),
        'Payslips' =>  array('create_payslips', 'manage_payslips', 'edit_payslips', 'delete_payslips'),
        'Activity Log' =>  array('manage_activity_log', 'delete_activity_log'),
        'Estimates Invoices' =>  array('create_estimates_invoices', 'manage_estimates_invoices', 'edit_estimates_invoices', 'delete_estimates_invoices'),
        'Expenses' =>  array('create_expenses', 'manage_expenses', 'edit_expenses', 'delete_expenses'),
        'Milestones' =>  array('create_milestones', 'manage_milestones', 'edit_milestones', 'delete_milestones'),
        'System Notifications' =>  array('manage_system_notifications', 'delete_system_notifications'),
        'Inventories' =>  array('manage_inventories'),
        'Resource Allocation' =>array('manage_resource_allocation'),
        'Human Resource' => array('manage_hr'),
        'Manage Incoming Requests' =>array('manage_incoming_requests'),
        'Master Schedule' =>array('manage_master_schedule'),
        'Manage Admin Schedule' =>array('manage_admin_schedule'),
        'Manage Memeber Schedule' =>array('manage_member_schedule'),
        'Department' =>array('department'),
        'Consultatnt' =>array('consultant'),
        'Supervisor' =>array('department'),
        'Project Manager' =>array('project_manager'),
        //'Finance' => array('finance')
    ]
];
