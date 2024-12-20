@extends('layout')

@section('title')
<?= get_label('timesheet', 'Timesheet') ?>
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
                        <?= get_label('time_sheets', 'Timesheets') ?>
                    </li>

                </ol>
            </nav>
        </div>
        @if (getAuthenticatedUser()->can('create_timesheet'))
        <div>
            <span data-bs-toggle="modal" data-bs-target="#timerModal"><a href="javascript:void(0);"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('time_tracker', 'Time tracker') ?>"><i class='bx bx-plus'></i></button></a></span>
        </div>
        @endif
    </div>
    <x-timesheet-card :timesheet="$timesheet" :users="$users" />
</div>
<script>
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var total_records = '<?= is_countable($timesheet) ? count($timesheet) : 0 ?>';
</script>
@endsection