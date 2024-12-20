@extends('layout')

@section('title')
<?= get_label('sub tasks', 'Sub Tasks') ?> - <?= get_label('list_view', 'List view') ?>
@endsection

@section('content')
    <div class="container">
        <h1>Requests</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
<h1>Subtask handle here </h1>

       
    </div>
@endsection