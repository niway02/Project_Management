<!-- projects card -->
@php
$flag = (Request::segment(1) == 'home' || Request::segment(1) == 'users' || Request::segment(1) == 'clients') ? 0 : 1;
@endphp
<div class="<?= $flag == 1 ? 'card ' : '' ?>mt-2">
    @if($flag == 1)
    <div class="card-body">
    @endif
        {{$slot}}
        @if (is_countable($projects) && count($projects) > 0)
        <div class="row">
            <!-- Filters and other input elements -->
        </div>

        <div class="table-responsive text-nowrap">
            <input type="hidden" id="data_type" value="projects">
            <input type="hidden" id="data_table" value="projects_table">
            <div class="d-flex justify-content-end mb-3">
                <button id="print_button" class="btn btn-primary me-2">Print</button>
                <button id="export_button" class="btn btn-secondary">Export to PDF</button>
            </div>
            <table id="projects_table" 
                   data-toggle="table" 
                   data-loading-template="loadingTemplate" 
                   data-url="/projects/listing{{ !empty($id) ? '/' . $id : '' }}" 
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
                <thead class="tablehead">
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                        <th data-sortable="true" data-field="title"><?= get_label('title', 'Title') ?></th>
                        <th data-field="users" data-formatter="ProjectUserFormatter"><?= get_label('users', 'Users') ?></th>
                        <th data-field="clients" data-formatter="ProjectClientFormatter"><?= get_label('clients', 'Clients') ?></th>
                        <th data-sortable="true" data-field="status_id" class="status-column"><?= get_label('status', 'Status') ?></th>
                        <th data-sortable="true" data-field="priority_id" class="priority-column"><?= get_label('priority', 'Priority') ?></th>
                        <th data-sortable="true" data-formatter="ProgressFormatter"><?= get_label('Progress', 'Progress') ?></th>
                        <th data-sortable="true" data-field="start_date"><?= get_label('starts_at', 'Starts at') ?></th>
                        <th data-sortable="true" data-field="end_date"><?= get_label('ends_at', 'Ends at') ?></th>
                        <th data-sortable="true" data-field="task_accessibility" data-visible="false"><?= get_label('task_accessibility', 'Task Accessibility') ?></th>
                        <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                        <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                        <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
                        <th data-sortable="true" data-field="status" class="status-column"><?= get_label('update status', 'update Status') ?></th>
                    </tr>
                </thead>
            </table>
        </div>
        @else
        <?php
        $type = 'Projects'; ?>
        <x-empty-state-card :type="$type" />
        @endif
        @if($flag == 1)
    </div>
    @endif
</div>

<!-- Include jsPDF and html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<script>
    document.getElementById('print_button').addEventListener('click', function() {
        var printContents = document.getElementById('projects_table').outerHTML;
        var newWindow = window.open('', '', 'height=500,width=800');
        newWindow.document.write('<html><head><title>Print</title>');
        newWindow.document.write('</head><body>');
        newWindow.document.write(printContents);
        newWindow.document.write('</body></html>');
        newWindow.document.close();
        newWindow.print();
    });

    document.getElementById('export_button').addEventListener('click', function() {
        html2canvas(document.getElementById('projects_table')).then(function(canvas) {
            var imgData = canvas.toDataURL('image/png');
            var pdf = new jsPDF();
            pdf.addImage(imgData, 'PNG', 10, 10);
            pdf.save('projects.pdf');
        });
    });
</script>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
    var add_favorite = '<?= get_label('add_favorite', 'Click to mark as favorite') ?>';
    var remove_favorite = '<?= get_label('remove_favorite', 'Click to remove from favorite') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
</script>
<script src="{{asset('assets/js/pages/project-list.js')}}"></script>
<style>
    .tablehead {
        background-color: #1B8596; /* Your preferred header background color */
        color: white; /* White text color for header */
    }
    .table:not(.table-dark) th {
        color: #ffffff !important;
    }
</style>