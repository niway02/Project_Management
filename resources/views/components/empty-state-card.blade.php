@php
$flag = (
(Request::segment(1) == 'home' || Request::segment(1) == 'users' || Request::segment(1) == 'clients')
&&
(strtolower($type) == 'projects' || strtolower($type) == 'tasks')
) ? 0 : 1;
@endphp
<div class="<?= $flag == 1 ? 'card ' : '' ?>text-center empty-state">
    @if($flag == 1)
    <div class="card-body">
        @endif
        <div class="misc-wrapper">
            <h2 class="mb-2 mx-2"><?= get_label(strtolower($type), $type) . ' ' . get_label('not_found', 'Not Found') ?></h2>
            <p class="mb-4 mx-2"><?= get_label('oops!', 'Oops!') ?> 😖 <?= get_label('data_does_not_exists', 'Data does not exists') ?>.</p>
            @if ($type!='Notifications')
            <a href="<?= strtolower($type) == 'contracts' || strtolower($type) == 'todos' || strtolower($type) == 'tags' || strtolower($type) == 'status' || str_replace(' ', '-', strtolower($type)) == 'leave-requests' || str_replace(' ', '-', strtolower($type)) == 'contract-types' || str_replace(' ', '-', strtolower($type)) == 'payment-methods' || strtolower($type) == 'allowances' || strtolower($type) == 'deductions' || strtolower($type) == 'notes' || strtolower($type) == 'timesheet' || strtolower($type) == 'taxes' || strtolower($type) == 'units' || strtolower($type) == 'items' || str_replace(' ', '-', strtolower($type)) == 'expense-types' || strtolower($type) == 'expenses' || strtolower($type) == 'payments' || strtolower($type) == 'languages' || strtolower($type) == 'tasks' || strtolower($type) == 'priorities' || strtolower($type) == 'projects' ? 'javascript:void(0)' : '/' . ((isset($link) && !empty($link) ? $link : (str_replace(' ', '-', strtolower($type)) . '/create'))) ?>" <?= strtolower($type) == 'todos' ? 'data-bs-toggle="modal" data-bs-target="#create_todo_modal"' : (strtolower($type) == 'tags' ? 'data-bs-toggle="modal" data-bs-target="#create_tag_modal"' : (strtolower($type) == 'status' ? 'data-bs-toggle="modal" data-bs-target="#create_status_modal"' : (str_replace(' ', '-', strtolower($type)) == 'leave-requests' ? 'data-bs-toggle="modal" data-bs-target="#create_leave_request_modal"' : (str_replace(' ', '-', strtolower($type)) == 'contract-types' ? 'data-bs-toggle="modal" data-bs-target="#create_contract_type_modal"' : (strtolower($type) == 'contracts' ? 'data-bs-toggle="modal" data-bs-target="#create_contract_modal"' : (str_replace(' ', '-', strtolower($type)) == 'payment-methods' ? 'data-bs-toggle="modal" data-bs-target="#create_pm_modal"' : (strtolower($type) == 'allowances' ? 'data-bs-toggle="modal" data-bs-target="#create_allowance_modal"' : (strtolower($type) == 'deductions' ? 'data-bs-toggle="modal" data-bs-target="#create_deduction_modal"' : (strtolower($type) == 'notes' ? 'data-bs-toggle="modal" data-bs-target="#create_note_modal"' : (strtolower($type) == 'timesheet' ? 'data-bs-toggle="modal" data-bs-target="#timerModal"' : (strtolower($type) == 'taxes' ? 'data-bs-toggle="modal" data-bs-target="#create_tax_modal"' : (strtolower($type) == 'units' ? 'data-bs-toggle="modal" data-bs-target="#create_unit_modal"' : (strtolower($type) == 'items' ? 'data-bs-toggle="modal" data-bs-target="#create_item_modal"' : (str_replace(' ', '-', strtolower($type)) == 'expense-types' ? 'data-bs-toggle="modal" data-bs-target="#create_expense_type_modal"' : (strtolower($type) == 'expenses' ? 'data-bs-toggle="modal" data-bs-target="#create_expense_modal"' : (strtolower($type) == 'payments' ? 'data-bs-toggle="modal" data-bs-target="#create_payment_modal"' : (strtolower($type) == 'languages' ? 'data-bs-toggle="modal" data-bs-target="#create_language_modal"' : (strtolower($type) == 'tasks' ? 'data-bs-toggle="modal" data-bs-target="#create_task_modal"' : (strtolower($type) == 'priorities' ? 'data-bs-toggle="modal" data-bs-target="#create_priority_modal"' : (strtolower($type) == 'projects' ? 'data-bs-toggle="modal" data-bs-target="#create_project_modal"' : '')))))))))))))))))))) ?> class="btn btn-primary m-1"><?= get_label('create_now', 'Create now') ?></a>
            @endif
            <div class="mt-3">
                <img src="{{asset('/storage/no-result.png')}}" alt="page-misc-error-light" width="500" class="img-fluid" data-app-dark-img="illustrations/page-misc-error-dark.png" data-app-light-img="illustrations/page-misc-error-light.png" />
            </div>
        </div>
        @if($flag == 1)
    </div>
    @endif
</div>