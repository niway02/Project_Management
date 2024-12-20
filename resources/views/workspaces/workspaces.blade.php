@extends('layout')

@section('title')
<?= get_label('workspaces', 'Workspaces') ?>
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
                    <li class="breadcrumb-item">
                        <?= get_label('workspaces', 'Workspaces') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/workspaces/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_workspace', 'Create workspace') ?>"><i class='bx bx-plus'></i></button></a>
        </div>
    </div>
    <x-workspaces-card :workspaces="$workspaces" :users="$users" :clients="$clients" />
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
</script>
<script src="{{asset('assets/js/pages/workspaces.js')}}"></script>
@endsection