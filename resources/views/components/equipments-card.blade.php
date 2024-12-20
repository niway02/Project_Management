@if (is_countable($equipments) && count($equipments) > 0)
<div class="card">
    <div class="card-body">
        {{ $slot }}
        <div class="row">
            @if(isAdminOrHasAllDataAccess())
            <div class="col-md-4 mb-3">
                <!-- Button to trigger modal -->
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEquipmentModal">New Equipment</a>
            </div>
            @endif
        </div>

        <!-- New Equipment Modal -->
        <div class="modal fade" id="newEquipmentModal" tabindex="-1" aria-labelledby="newEquipmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newEquipmentModalLabel">Create Equipment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="equipmentForm" action="{{ route('equipments.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="item" class="form-label">ITEM<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="item" id="item" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">QUANTITY<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="quantity" id="quantity" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rate_with_vat" class="form-label">RATE WITH VAT<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="rate_with_vat" id="rate_with_vat" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">AMOUNT<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="amount" id="amount" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">STATUS<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="status" id="status" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="type" class="form-label">TYPE<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="type" id="type" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reorder_quantity" class="form-label">REORDER QUANTITY<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="reorder_quantity" id="reorder_quantity" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="min_quantity" class="form-label">MIN QUANTITY<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="min_quantity" id="min_quantity" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label">UNIT ID<span class="text-danger">*</span></label>
                                        <select class="form-control" name="unit_id" id="unit_id" required>
                                            <option value="">Select a Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="warehouse_id" class="form-label">WAREHOUSE ID<span class="text-danger">*</span></label>
                                        <select class="form-control" name="warehouse_id" id="warehouse_id" required>
                                            <option value="">Select a warehouse</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="remark" class="form-label">REMARK</label>
                                <textarea class="form-control" name="remark" id="remark" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="equipmentForm">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <input type="hidden" id="data_type" value="equipments">
            <table id="equipments-table"
                   class="table table-striped table-bordered table-hover"
                   data-toggle="table"
                   data-url="{{ route('equipments.data') }}"
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
                   data-query-params="queryParamsEquipments">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="id">{{ get_label('id', 'ID') }}</th>
                        <th data-sortable="true" data-field="item">{{ get_label('name', 'Name') }}</th>
                        <th data-sortable="true" data-field="quantity">{{ get_label('quantity', 'Quantity') }}</th>
                        <th data-sortable="true" data-field="status">{{ get_label('status', 'Status') }}</th>
                        <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@else
<?php $type = 'equipments'; ?>
<x-empty-state-card :type="$type" />
@endif

<script src="{{ asset('assets/js/pages/equipment-list.js') }}"></script>