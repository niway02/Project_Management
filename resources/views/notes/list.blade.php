@extends('layout')

@section('title')
<?= get_label('notes', 'Notes') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/home') }}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('notes', 'Notes') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <span data-bs-toggle="modal" data-bs-target="#create_note_modal">
                <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_note', 'Create note') ?>">
                    <i class='bx bx-plus'></i>
                </a>
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($notes->count() > 0)
            <div class="row  mt-4 sticky-notes">
                @foreach ($notes as $note)
                <div class="col-md-4 sticky-note">
                    <div class="sticky-content sticky-note-bg-<?= $note->color ?>">
                        <div class="text-end">
                            <!-- <span data-bs-toggle="modal" data-bs-target="#create_note_modal"> -->
                            <!-- <span data-bs-toggle="modal" data-bs-target="#create_note_modal"> -->
                            <a href="javascript:void(0);" class="btn btn-primary btn-xs edit-note" data-id='{{$note->id}}' data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('update', 'Update') ?>">
                                <i class="bx bx-edit"></i>
                            </a>
                            <!-- </span> -->
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs mx-1 delete" data-id='{{$note->id}}' data-type='notes' data-reload='true' data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('delete', 'Delete') ?>">
                                <i class="bx bx-trash"></i>
                            </a>
                            <!-- </span> -->
                        </div>
                        <h4><?= $note->title ?></h4>
                        <p><?= $note->description ?></p>
                        <b><?= get_label('created_at', 'Created at') ?> : </b><span class="text-primary">{{ format_date($note->created_at,true)}}</span>
                    </div>
                </div>


                @endforeach
            </div>
            @else
            <?php
            $type = 'Notes';
            ?>
            <x-empty-state-card :type="$type" />
            @endif
        </div>
    </div>
</div>
@endsection