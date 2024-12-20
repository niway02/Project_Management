@extends('layout')

@section('title')
    {{ get_label('warehouses', 'Warehouses') }} - {{ get_label('show', 'Show') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4 text-primary">Warehouse Details</h1>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-secondary"><strong>Warehouse Name:</strong> {{ $warehouse->name }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-secondary"><strong>Location:</strong> {{ $warehouse->location }}</p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-primary"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-secondary">{{ get_label('Total Equipment', 'Total Equipment') }}</span>
                            <h3 class="card-title mb-2 text-primary" id="total-equipment">{{$totalEquipment}}</h3>
                            <p class="text-success fw-bold">Min in qty {{$minEquipmentCount}}</p>
                        </div>
                    </div>
                </div>     
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="menu-icon tf-icons bx bx-package bx-md text-primary"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-secondary">{{ get_label('Total Material', 'Total Material') }}</span>
                            <h3 class="card-title mb-2 text-primary" id="total-material">{{$totalMaterial}}</h3>
                            <p class="text-success fw-bold">Min in qty {{$minMaterial}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="menu-icon tf-icons bx bx-package bx-md text-primary"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-secondary">{{ get_label('Total Assets', 'Total Assets') }}</span>
                            <h3 class="card-title mb-2 text-primary" id="total-assets">{{$totalEquipment + $totalMaterial }}</h3>
                            <p class="text-success fw-bold">Min in qty 30</p>
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="nav-align-top my-4">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-equipment" aria-controls="navs-top-equipment" aria-selected="true">
                <i class="menu-icon tf-icons bx bx-wrench text-warning"></i><?= get_label('equipment', 'Equipment') ?>
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-material" aria-controls="navs-top-material" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-box text-info"></i><?= get_label('material', 'Material') ?>
            </button>
        </li>
  
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active show" id="navs-top-equipment" role="tabpanel">
            <div class="d-flex justify-content-between">
                <h4 class="fw-bold"><?= get_label('equipment', 'Equipment') ?></h4>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
    <a href="{{ route('warehouses.export.pdf') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to PDF', 'Export to PDF') }}">
        <i class="bx bx-file-pdf"></i>
        <span class="btn-label fw-medium fs-6">PDF</span>
    </a>
    <a href="{{ route('warehouses.export.xlsx') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to XLSX', 'Export to XLSX') }}">
        <i class="bx bx-file-excel"></i>
        <span class="btn-label fw-medium fs-6">XLSX</span>
    </a>
    <a href="{{ route('warehouses.export.csv') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to CSV', 'Export to CSV') }}">
        <i class="bx bx-file-csv"></i>
        <span class="btn-label fw-medium fs-6">CSV</span>
    </a>
    <a href="{{ route('warehouses.import') }}" class="btn btn-primary btn-icon" title="{{ get_label('Import', 'Import') }}">
        <i class="bx bx-upload"></i>
    </a>
</div>
</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover"
                       id="equipment-table"
                       data-url="{{ route('warehouses.warehousesEquipments') }}"
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
            <th data-field="id">{{ get_label('Id', 'Id') }}</th>
            <th data-field="item">{{ get_label('Equipment', 'Equipment') }}</th>
            <th data-field="unit_name">{{ get_label('Unit', 'Unit') }}</th>
            <th data-field="quantity">{{ get_label('Quantity', 'Quantity') }}</th>
            <th data-field="reorder_quantity">{{ get_label('Reorder Quantity', 'Reorder Quantity') }}</th>
            <th data-field="min_quantity">{{ get_label('Min Quantity', 'Min Quantity') }}</th>
            <th data-field="status" data-formatter="statusFormatter">{{ get_label('status', 'Status') }}</th>
            <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="navs-top-material" role="tabpanel">
            <div class="d-flex justify-content-between">
                <h4 class="fw-bold"> <?= get_label('material', 'Material') ?></h4>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                <a href="{{ route('warehouses.export.pdf') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to PDF', 'Export to PDF') }}">
        <i class="bx bx-file-pdf"></i>
        <span class="btn-label fw-medium fs-6">PDF</span>
    </a>
    <a href="{{ route('warehouses.export.xlsx') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to XLSX', 'Export to XLSX') }}">
        <i class="bx bx-file-excel"></i>
        <span class="btn-label fw-medium fs-6">XLSX</span>
    </a>
    <a href="{{ route('warehouses.export.csv') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to CSV', 'Export to CSV') }}">
        <i class="bx bx-file-csv"></i>
        <span class="btn-label fw-medium fs-6">CSV</span>
    </a>
    <a href="{{ route('warehouses.import') }}" class="btn btn-primary btn-icon" title="{{ get_label('Import', 'Import') }}">
        <i class="bx bx-upload"></i>
    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover"
                       id="equipment-table"
                       data-url="{{ route('warehouses.warehousesMaterials') }}"
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
            <th data-field="id">{{ get_label('Id', 'Id') }}</th>
            <th data-field="item">{{ get_label('Equipment', 'Equipment') }}</th>
            <th data-field="unit_name">{{ get_label('Unit', 'Unit') }}</th>
            <th data-field="quantity">{{ get_label('Quantity', 'Quantity') }}</th>
            <th data-field="reorder_quantity">{{ get_label('Reorder Quantity', 'Reorder Quantity') }}</th>
            <th data-field="min_quantity">{{ get_label('Min Quantity', 'Min Quantity') }}</th>
            <th data-field="status" data-formatter="statusFormatter">{{ get_label('status', 'Status') }}</th>
            <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="navs-top-labor" role="tabpanel">
            <div class="d-flex justify-content-between">
                <h4 class="fw-bold"> <?= get_label('labor', 'Labor') ?></h4>
            </div>
            <div>
          
 
  

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                <a href="{{ route('warehouses.export.pdf') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to PDF', 'Export to PDF') }}">
        <i class="bx bx-file-pdf"></i>
        <span class="btn-label fw-medium fs-6">PDF</span>
    </a>
    <a href="{{ route('warehouses.export.xlsx') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to XLSX', 'Export to XLSX') }}">
        <i class="bx bx-file-excel"></i>
        <span class="btn-label fw-medium fs-6">XLSX</span>
    </a>
    <a href="{{ route('warehouses.export.csv') }}" class="btn btn-primary btn-icon me-3" title="{{ get_label('Export to CSV', 'Export to CSV') }}">
        <i class="bx bx-file-csv"></i>
        <span class="btn-label fw-medium fs-6">CSV</span>
    </a>
    <a href="{{ route('warehouses.import') }}" class="btn btn-primary btn-icon" title="{{ get_label('Import', 'Import') }}">
        <i class="bx bx-upload"></i>
    </a>
                </div>
         
        </div>
    </div>
</div>
           

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">{{ __('Equipment Edit') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('warehouses.index', $warehouse->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="equipment" class="form-label">{{ get_label('Equipment Name', 'Equipment Name') }}</label>
                        <input type="text" class="form-control" id="equipment" name="euipment" value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">{{ get_label('Quantity', 'quantity') }}</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">{{ get_label('Unit', 'unit') }}</label>
                        <input type="text" class="form-control" id="unit" name="unit" value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="reorder_quantity" class="form-label">{{ get_label('Reorder Quantity', 'reorder_quantity') }}</label>
                        <input type="text" class="form-control" id="reorder_quantity" name="reorder_quantity" value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="minimum_quantity" class="form-label">{{ get_label('Minimum Quantity', 'minimum_quantity') }}</label>
                        <input type="text" class="form-control" id="minimum_quantity" name="minimum_quantity" value="" required>
                    </div>
                    <!-- Add other form fields as needed -->
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

function actionsFormatter(value, row, index) {
    return [
        '<a href="#" class="newUpdateModal" data-id="' + row.id + '" data-bs-toggle="modal" data-bs-target="#updateModal" title="{{ get_label("Edit", "Edit") }}">' +
        '<i class="bx bx-edit mx-1"></i>' +
        '</a>',
        '<a href="javascript:void(0);" class="quick-view" data-id="' + row.id + '" data-type="warehouse" title="{{ get_label("Quick View", "Quick View") }}">' +
        '<i class="bx bx-info-circle text-primary mx-3"></i>' +
        '</a>'
    ].join('');
}
function statusFormatter(value, row, index) {
    let statusClass = '';
    let statusText = '';
    if (row.quantity >= row.reorder_quantity) {
        statusClass = 'bg-success';
        statusText = 'Available';
    } else if (row.quantity <= row.min_quantity) {
        statusClass = 'bg-danger';
        statusText = 'Low';
    } else if (row.quantity <= row.min_quantity && row.reorder_quantity >= row.min_quantity) {
        statusClass = 'bg-orange';
        statusText = 'Low';
    } else {
        statusClass = 'bg-danger';
        statusText = 'Low Quantity';
    }

    return `<span class="badge ${statusClass}">${statusText}</span>`;
}
</script>