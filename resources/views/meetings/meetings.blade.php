@extends('layout')

@section('title')
<?= get_label('meetings', 'Meetings') ?>
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
                        <?= get_label('meetings', 'Meetings') ?>
                    </li>

                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/meetings/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_meeting', 'Create meeting') ?>"><i class='bx bx-plus'></i></button></a>
        </div>
    </div>
    <x-meetings-card :meetings="$meetings" :users="$users" :clients="$clients" />
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
    var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
</script>
<script src="{{asset('assets/js/pages/meetings.js')}}"></script>
@endsection