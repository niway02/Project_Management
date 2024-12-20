<div class="card-body">
        {{ $slot }}

        @if (is_countable($materials) && count($materials) > 0)
            <div class="row">
                @if (isAdminOrHasAllDataAccess() && (!isset($id) || (explode('_', $id)[0] != 'client' && explode('_', $id)[0] != 'user')))
                    <div class="col-md-4 mb-3"></div>
                    <div class="col-md-4 mb-3"></div>
                @endif
            </div>

            <table id="materials-table"
                   class="table table-striped table-bordered table-hover"
                   data-toggle="table"
                   data-url="{{ route('materials.data') }}"
                   data-loading-template="loadingTemplate"
                   data-icons-prefix="bx"
                   data-icons="icons"
                   data-show-refresh="true"
                   data-total-field="total"
                   data-trim-on-search="false"
                   data-data-field="rows"
                   data-page-list="[5, 10, 20, 50, 100, 200]"
                   data-search="true"
                   data-side-pagination="server"
                   data-show-columns="true"
                   data-pagination="true"
                   data-sort-name="id"
                   data-sort-order="desc"
                   data-mobile-responsive="true"
                   data-query-params="queryParamsProjects">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="item">{{ get_label('item', 'Item') }}</th>
                        <th data-sortable="true" data-field="quantity">{{ get_label('quantity', 'Quantity') }}</th>
                        <th data-sortable="true" data-field="rate_with_vat">{{ get_label('rate_with_vat', 'Rate with VAT') }}</th>
                        <th data-sortable="true" data-field="amount">{{ get_label('amount', 'Amount') }}</th>
                        <th data-sortable="true" data-field="remark">{{ get_label('remark', 'Remark') }}</th>
                        <th data-sortable="true" data-field="status">{{ get_label('status', 'Status') }}</th>
                        <th data-sortable="true" data-field="material_type">{{ get_label('material_type', 'Material Type') }}</th>
                        <th data-sortable="true" data-field="reorder_quantity">{{ get_label('reorder_quantity', 'Reorder Quantity') }}</th>
                        <th data-sortable="true" data-field="min_quantity">{{ get_label('min_quantity', 'Minimum Quantity') }}</th>
                        <th data-sortable="true" data-field="unit">{{ get_label('unit', 'Unit') }}</th>
                        <th data-sortable="true" data-field="action">{{ get_label('action', 'Action') }}</th>
                    </tr>
                </thead>
            </table>
        @else
            <x-empty-state-card type="Materials" />
        @endif
    </div>

<script>
    var label_update = '{{ get_label('update', 'Update') }}';
    var label_delete = '{{ get_label('delete', 'Delete') }}';
</script>
<script src="{{ asset('assets/js/pages/material-list.js') }}"></script>