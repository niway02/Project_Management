<!-- meetings -->

<div class="card">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}
 
            <input type="hidden" id="data_type" value="labor">
            <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/laborPossition/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="id"><?= get_label('id', '#') ?></th>
                        <th data-sortable="true" data-field="photo"><?= get_label('photo', 'photo') ?></th>
                        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                        <th data-sortable="true" data-field="name"><?= get_label('name', 'Full Name') ?></th>
                        <th data-sortable="true" data-field="position"><?= get_label('position', 'Role') ?></th>

                        <th data-sortable="true" data-field="position"><?= get_label('position', 'Terms') ?></th>
                        <th data-sortable="true" data-field="hourly_rate"><?= get_label('hourly_rate', 'Hourly Rate') ?></th>
                        <th data-sortable="true" data-field="availability"><?= get_label('availability', 'availability') ?></th>
                        <th data-sortable="true" data-field="current_site"><?= get_label('current_site', 'working site') ?></th>
                        <th data-sortable="true" data-field="address"><?= get_label('address', ' Address') ?></th>
                        <th data-sortable="true" data-field="status"><?= get_label('status', 'Status') ?></th>
                    </tr>
                </thead>
            </table>
      
        </div>
    </div>
</div>
<script>
    function userFormatter(value, row, index) {
    return '<div class="d-flex">' + row.photo + '<div class="mx-2 mt-2"><h6 class="mb-1">' + row.first_name + ' ' + row.last_name +
    (row.status === 1 ? ' <span class="badge bg-success">Active</span>' : ' <span class="badge bg-danger">Deactive</span>') +
    '</h6><p class="text-muted">' + row.email + '</p></div>' +
    '</div>';

}
</script>