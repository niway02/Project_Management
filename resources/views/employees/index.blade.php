@extends('layout')
@section('title')
    {{ get_label('employees', 'Employees') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
    <div class="container-fluid">
        <table class="table table-striped table-hover"
               id="data-table"
               data-url="{{ route('employees.data') }}"
               data-toggle="table"
               data-search="true"
               data-show-columns="true"
               data-pagination="true"
               data-side-pagination="server"
               data-query-params="queryParams"
               data-icons-prefix="bx bx-"
               data-icons='{"refresh": "refresh", "plus": "plus", "edit": "edit", "trash": "trash"}'
               data-sort-order="asc"
               data-icons-prefix="bx" 
               data-total-field="total" 
               data-trim-on-search="false" 
               data-data-field="rows" 
               data-page-list="[5, 10, 20, 50, 100, 200]" 
               data-sort-name="id"
                data-sort-order="desc" 
                data-mobile-responsive="true" 
               >
            <thead>
                <tr>
                    <th data-field="checkbox" data-checkbox="true"></th>
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