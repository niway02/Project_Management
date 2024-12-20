@extends('layout')

@section('title')
    {{ get_label('warehouses', 'Warehouses') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
<h1>Warehouses</h1>  

<table class="table table-striped table-hover"
                       id="warehouse-table"
                       data-url="{{ route('warehouses.data') }}"
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
                            <th data-field="name">{{ get_label('name', 'name') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
                    </div>
                    </div>
                    </div>
                    <div>
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
</script>