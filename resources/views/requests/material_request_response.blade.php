@extends('layout')

@section('title')
<?= get_label('tasks', 'Tasks') ?> - <?= get_label('list_view', 'List view') ?>
@endsection

@section('content')
<div class="container">
    <h1 class="my-4"> Requests Respons</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12 mb-4">
            <h2>Materials</h2>
            <div class="row">
               
            </div>
        </div>
    </div>

</div>
@endsection