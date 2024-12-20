<!-- resources/views/components/warehouses-card.blade.php -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWarehouseModal">
    {{ __('Create Warehouse') }}
</button> 
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
            <th data-sortable="true" data-field="id">{{ __('ID') }}</th>
            <th data-sortable="true" data-field="name">{{ __('Name') }}</th>
            <th data-sortable="true" data-field="location">{{ __('Site') }}</th>
            <th data-sortable="true" data-field="contact_info">{{ __('Contact') }}</th>
            <th data-sortable="true" data-field="first_name">{{ __('Manager') }}</th>
            <th data-sortable="true" data-field="created_at" data-visible="false">{{ __('Created at') }}</th>
            <th data-sortable="true" data-field="updated_at" data-visible="false">{{ __('Updated at') }}</th>
            <th data-formatter="actionsFormatter">{{ __('Actions') }}</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="createWarehouseModal" tabindex="-1" aria-labelledby="createWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createWarehouseModalLabel">{{ __('Create Warehouse') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="warehouseName" class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-control" id="warehouseName" placeholder="{{ __('Name') }}">
                </div>
                <div class="mb-3">
                    <label for="warehouseDescription" class="form-label">{{ __('Description') }}</label>
                    <textarea class="form-control" id="warehouseDescription" rows="3" placeholder="{{ __('Description') }}"></textarea>
                </div>
                <div class="mb-3">
                    <label for="warehouseLocation" class="form-label">{{ __('Location') }}</label>
                    <input type="text" class="form-control" id="warehouseLocation" placeholder="{{ __('Location') }}">
                </div>
                <div class="mb-3">
                    <label for="warehouseManager" class="form-label">{{ __('Manager') }}</label>
                    <input type="text" class="form-control" id="warehouseManager" placeholder="{{ __('Manager') }}">
                </div>
                <div class="mb-3">
                    <label for="warehouseContactInfo" class="form-label">{{ __('Contact Information') }}</label>
                    <input type="text" class="form-control" id="warehouseContactInfo" placeholder="{{ __('Contact Information') }}">
                </div>
                <div class="mb-3">
                    <label for="warehouseCreatedBy" class="form-label">{{ __('Created By') }}</label>
                    <input type="text" class="form-control" id="warehouseCreatedBy" placeholder="{{ __('Created By') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="saveWarehouse">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
   function loadingTemplate() {
        return '<div class="spinner-border text-primary"></div>';
    }
</script>

<script>
    var label_show = '{{ __('Show') }}';
</script>
<script src="{{ asset('assets/js/pages/warehouses.js') }}"></script>