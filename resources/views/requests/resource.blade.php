@extends('layout')

@section('title')
    {{ get_label('Resource_allocation', 'Resource Allocation') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4 text-primary">{{ get_label('resource_allocation', 'Resource Allocation') }}</h1>
            <div class="table-responsive">
                <table class="table table-striped table-hover"
                       id="equipment-table"
                       data-url="{{ route('projects.data') }}"
                       data-toggle="table"
                       data-search="true"
                       data-show-columns="true"
                       data-pagination="true"
                       data-side-pagination="server"
                       data-query-params="queryParams"
                       data-sort-name="item"
                       data-data-field="rows"
                       data-page-list="[5, 10, 20, 50, 100, 200]"
                       data-sort-order="asc">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id">{{ get_label('id', 'ID') }}</th>
                            <th data-field="title">{{ get_label('project_name', 'Project Name') }}</th>
                            <th data-field="total_tasks">{{ get_label('', 'Total Tasks') }}</th>
                            <th data-field="total_activity">{{ get_label('planned_bug', 'Total Activity') }}</th>
                            <th data-field="budget">{{ get_label('budget', 'Estimated Cost') }}</th>
                            <th data-field="actual_cost">{{ get_label('actual_cost', 'Actual Cost') }}</th>
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
        '<a href="{{ route("rquestTask.show", ":id") }}" class="view-detail" data-id="' + row.id + '">' +
        '<i class="bx bx-info-circle text-primary mx-3"></i>' +
        '</a>'
    ].join('').replace(':id', row.id);
}

function statusFormatter(value, row, index) {
    // Define a mapping for backend color values to CSS classes
    const colorMapping = {
        'SUCCESS': 'bg-success',
        'DANGER': 'bg-danger',
        'WARNING': 'bg-warning',
        'SECONDARY': 'bg-success',
        'PRIMARY': 'bg-primary',
        'INFO': 'bg-info',
        'LIGHT': 'bg-light',
        'DARK': 'bg-dark',
        // Add any additional mappings as needed
    };

    // Get the appropriate class from the mapping or default to 'bg-secondary'
    let statusClass = colorMapping[row.color.toUpperCase()] || 'bg-secondary'; 
    let statusText = row.status || 'Unknown';  // Fallback text if status is undefined

    return `<span class="badge ${statusClass}">${statusText}</span>`;
}
</script>
@endsection