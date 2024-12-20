@extends('layout')

@section('title')
    {{ get_label('warehouses', 'Warehouses') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
    <div class="container-fluid">
    <table class="table table-striped table-hover"
           id="data-table"
           data-url="{{ route('data.samisams') }}"
           data-toggle="table"
           data-search="true"
           data-show-columns="true"
           data-pagination="true"
           data-side-pagination="server"
           data-query-params="queryParams"
           data-icons-prefix="bx bx-"
           data-icons='{"refresh": "refresh", "plus": "plus", "edit": "edit", "trash": "trash"}'
           data-trim-on-search="true"
           data-sort-name="name"
           data-sort-order="asc">
        <thead>
            <tr>
                <th data-field="id">ID</th>
                <th data-field="name">Name</th>
                <th data-field="email">Email</th>
            </tr>
        </thead>
    </table>

    </div>
@endsection
@push('scripts')
    <script>
        function queryParams(params) {
            return {
                search: params.search,
                limit: params.limit,
                offset: params.offset,
                sort: params.sort,
                order: params.order
            };
        }
    </script>
@endpush