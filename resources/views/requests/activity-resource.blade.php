@extends('layout')

@section('title')
    {{ get_label('Resource Allocation', 'Resource Allocation') }}
@endsection

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ get_label('Resource Allocation', 'Resource Allocation') }}
                </li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="{{ route('allMaterialAlloation.activity', $activity->task->id) }}" class="text-success font-weight-bold" style="text-decoration: none;">
                    View Allocate Resource
                </a>
            </div>
            <div class="card-body">

                <div class="row project-task-activity-hierarchy">
                    <div class="col-md-4">
                        <div class="project">
                            <div class="project-title">{{ $activity->task->project->title }}</div>
                            <div class="project-dates">
                                <span class="start-date">{{ $activity->task->project->start_date }}</span>
                                <span class="arrow">&#8594;</span>
                                <span class="end-date">{{ $activity->task->project->end_date }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="task">
                            <div class="task-title">{{ $activity->task->title }}</div>
                            <div class="task-dates">
                                <span class="start-date">{{ $activity->task->start_date }}</span>
                                <span class="arrow">&#8594;</span>
                                <span class="end-date">{{ $activity->task->end_date }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="activity">
                            <div class="activity-title text-primary">{{ $activity->name }}</div>
                            <div class="activity-dates">
                                <span class="start-date">{{ $activity->start_date }}</span>
                                <span class="arrow">&#8594;</span>
                                <span class="end-date">{{ $activity->end_date }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs for Materials, Equipment, Labor -->
                <div class="nav-align-top my-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="materials-tab" data-bs-toggle="tab" href="#materials" role="tab">
                                <i class="menu-icon tf-icons bx bx-box text-success"></i> Materials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="equipment-tab" data-bs-toggle="tab" href="#equipment" role="tab">
                                <i class="menu-icon tf-icons bx bx-wrench text-info"></i> Equipment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="labor-tab" data-bs-toggle="tab" href="#labor" role="tab">
                                <i class="menu-icon tf-icons bx bx-user-check text-primary"></i> Labor
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="materials" role="tabpanel">
                            <h5 class="mt-4">Materials for this Activity</h5>
                            <div class="table-responsive text-nowrap">
                                <form action="{{ route('sellect-material.request') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="selected_subtask_id" id="selected_subtask_id" value="{{ $activity->id }}">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Rate with VAT</th>
                                                <th>Select</th>
                                            </tr> 
                                        </thead>
                                        <tbody>
                                        @foreach($materials as $key => $material)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $material['material']['item'] }}</td>
                                                <td>{{ $material['material']['item'] }}</td>
                                                <td>{{ $material['quantity'] }}</td>
                                                <td>{{ $material['material']['rate_with_vat'] }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="selected_materials[]" value="{{ json_encode($material) }}">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Continue</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="equipment" role="tabpanel">
                            <h5 class="mt-4">Equipment for this Activity</h5>
                            <div class="table-responsive text-nowrap">
                                <form action="{{ route('sellect-material.request') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="selected_subtask_id" id="selected_subtask_id" value="{{ $activity->id }}">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Rate with VAT</th>
                                                <th>Reorder Quantity</th>
                                                <th>min_quantity </th>
                                                <th>Select</th>
                                            </tr> 
                                        </thead> 
                                        <tbody>
                                        @foreach($equipment as $key => $material)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $material->item }}</td>
                                                <td>{{ $material->id }}</td>
                                                <td>{{ $material->quantity }}</td>
                                                <td>{{ $material->rate_with_vat }}</td>
                                                <td>{{ $material->reorder_quantity}} </td>
                                                <td>{{ $material->min_quantity}} </td>
                                             
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="selected_materials[]" value="{{ json_encode($material) }}">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Continue</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="labor" role="tabpanel">
                            <h5 class="mt-4">Labor for this Activity</h5>
                            <div class="table-responsive text-nowrap">
                                <form action="{{ route('sellect-labor.request') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="selected_subtask_id" id="selected_subtask_id" value="{{ $activity->id }}">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                            <th>No</th>
                                            <th>Possition</th>
                                            <th>Total Labor</th>
                                            <th>Hourly Rate</th>
                                            <th>Skill Level</th>
                                            <th>Select</th>
                                            </tr> 
                                        </thead> 
                                        <tbody>
                                        @foreach($labors as $key => $labor)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
            
            <td>{{ $labor['labor_type_name'] }}</td>
            <td>{{ $labor['total_labor'] }}</td>
            <td>{{ $labor['hourly_rate'] }}</td>
            <td>{{ $labor['skill_level'] }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="selected_labors[]" value="{{ json_encode($labor) }}">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Continue</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Initialize DataTables
    $(document).ready(function() {
        $('table').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [5, 10, 25, 50],
            language: {
                search: "Filter records:",
                lengthMenu: "Display _MENU_ records per page"
            },
            // Custom styles for the table
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var select = $('<select class="form-select"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^'+val+'$' : '', true, false).draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="'+d+'">'+d+'</option>')
                    });
                });
            }
        });
    });
</script>
@endsection

@section('scripts')
    <!-- Include Boxicons for icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css">
    <style>
        /* Custom styles for tabs */
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-radius: 0.5rem;
            color: #555;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-tabs .nav-link.active {
            background-color: #71dd37; /* Active tab color */
            color: white;
            border-color: #71dd37; /* Match border to active color */
        }

        .nav-tabs .nav-link:hover {
            background-color: #e2e2e2; /* Hover effect */
        }

        .table {
            margin-top: 20px;
        }
    </style>