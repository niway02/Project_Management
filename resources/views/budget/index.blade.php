@extends('layout')

@section('title')
    {{ get_label('budget_allocation', 'Budget Allocation') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4 text-primary">{{ get_label('budget_allocation', 'Budget Allocation') }}</h1>
            <div class="row mb-4 mt-4">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ url('/budget-allocations') }}">{{ get_label('budget_allocation', 'Budget Allocation') }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
               
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover"
                       id="equipment-table"
                       data-url="{{ route('projetBudgetData') }}"
                       data-toggle="table"
                       data-search="true"
                       data-show-columns="true"
                       data-pagination="true"
                       data-side-pagination="server"
                       data-query-params="queryParams"
                       data-icons-prefix="bx bx-"
                       data-icons='{"refresh": "refresh", "plus": "plus", "edit": "edit", "trash": "trash"}'
                       data-trim-on-search="true"
                       data-sort-name="item"
                       data-data-field="rows"
                       data-page-list="[5, 10, 20, 50, 100, 200]"
                       data-mobile-responsive="true"
                       data-sort-order="asc">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id">{{ get_label('id', 'ID') }}</th>
                            <th data-field="project.title">{{ get_label('project_name', 'Project Name') }}</th>
                            <th data-formatter="amountFormatter">{{ get_label('estimated_budget', 'Estimated Budget') }}</th>
                            <th data-field="planned_bug">{{ get_label('planned_bug', 'Planned Bug') }}</th>
                            <th data-field="priority">{{ get_label('priority', 'Priority') }}</th>
                            <th data-field="payment_method">{{ get_label('payment_method', 'Payment Method') }}</th>
                            <th data-field="billing_type">{{ get_label('billing_type', 'Billing Type') }}</th>
                            <th data-field="milestone">{{ get_label('milestone', 'Milestone') }}</th>
                            <th data-formatter="statusFormatter">{{ get_label('status', 'Status') }}</th>
                            <th data-formatter="actionsFormatter">{{ get_label('actions', 'Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">{{ __('Budget Allocation') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('budget.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select class="form-select" id="project_filter" aria-label="Default select example">
                            <option value=""><?= get_label('select_project', 'Select project') ?></option>
                            @foreach ($projects as $project)
                            <option value="{{$project->id}}">{{$project->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">{{ get_label('amount', 'Amount') }}</label>
                        <input type="text" class="form-control" id="amount" name="amount" value="{{ $project->amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">{{ get_label('PAYMENT METHOD', 'PAYMENT METHOD') }}</label>
                        <select class="form-select" id="paymentMethod_filter" >
                            <option value=""><?= get_label('select_payment_method', 'Select Payment Method') ?></option>
                            @foreach ($paymentMethods as $paymentMethod)
                            <option value="{{$project->id}}">{{$paymentMethod->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">{{ get_label('Priority', 'Priority') }}</label>
                        <select class="form-select" id="status_filter" >
                            <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                            @foreach ($priorities as $priority)
                            <option value="{{$priority->title}}">{{$priority->title}}</option>
                            @endforeach      
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="planned_bug" class="form-label">{{ get_label('Planned Bug', 'Planned Bug') }}</label>
                        <input type="text" class="form-control" id="planned_bug" name="planned_bug" value="{{ $project->planned_bug }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ get_label('status', 'Status') }}</label>
                        <select class="form-select" id="status_filter" >
                            <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                            @foreach ($status as $paymentMethod)
                            <option value="{{$paymentMethod['id']}}">{{$paymentMethod['title']}}</option>
                            @endforeach      
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function queryParams(params) {
    return {
        search: params.search,
        limit: params.limit,
        offset: params.offset,
        order: params.order,
        sort: params.sort
    };
}

function actionsFormatter(value, row, index) {
    return [
        '<a href="#" class="newUpdateModal" data-id="' + row.id + '" data-bs-toggle="modal" data-bs-target="#updateModal" title="{{ get_label("Edit", "Edit") }}">' +
        '<i class="bx bx-edit mx-1"></i>' +
        '</a>',
        '<a href="{{ route("budget.show", "1") }}" class="view-detail" data-id="' + row.id + '">' +
        '<i class="bx bx-info-circle text-primary mx-3"></i>' +
        '</a>'
    ].join('');
}

function statusFormatter(value, row, index) {
    let statusClass = '';
    let statusText = '';
    if (row.status == "paid") {
        statusClass = 'bg-success';
        statusText = 'paid';
    } else if (row.status == "unpaid") {
        statusClass = 'bg-danger';
        statusText = 'unpaid';
    } else if (row.status == "credit") {
        statusClass = 'bg-orange';
        statusText = 'credit';
    } else {
        statusClass = 'bg-danger';
        statusText = 'something';
    }

    return `<span class="badge ${statusClass}">${statusText}</span>`;
}

function amountFormatter(value, row, index) {
    return row.amount + ' ' + row.currency;
}
</script>