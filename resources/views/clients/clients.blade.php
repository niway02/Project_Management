@extends('layout')

@section('title')
<?= get_label('clients', 'Clients') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('clients', 'Clients') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/clients/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_client', 'Create client') ?>"><i class='bx bx-plus'></i></button></a>
        </div>
    </div>
    @if (is_countable($clients) && count($clients) > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="clients">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/clients/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>

                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                            <th data-formatter="clientFormatter" data-sortable="true"><?= get_label('client', 'Client') ?></th>
                            <th data-field="company" data-sortable="true"><?= get_label('company', 'Company') ?></th>
                            <th data-field="phone" data-sortable="true"><?= get_label('phone_number', 'Phone number') ?></th>
                            <th data-formatter="assignedFormatter"><?= get_label('assigned', 'Assigned') ?></th>
                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                            <th data-formatter="actionFormatter"><?= get_label('actions', 'Actions') ?></th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    @else
    <?php
    $type = 'Clients'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_projects = '<?= get_label('projects', 'Projects') ?>';
    var label_tasks = '<?= get_label('tasks', 'Tasks') ?>';
</script>

<script src="{{asset('assets/js/pages/clients.js')}}"></script>
@endsection