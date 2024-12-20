@extends('layout')

@section('title')
<?= get_label('search_results', 'Search results') ?>
@endsection

@section('content')

<div class="container mt-4">
    <div>
        <h4 class="fw-bold mb-0">
        <?= get_label('search_results', 'Search results') ?>
        </h4>
    </div>

    @if ($results->count() > 0)
    @foreach ($results as $result)
    <div class="card my-4">
        <div class="mx-4 my-2 d-flex align-items-baseline">
            <h4 class="mb-0">{{class_basename($result)}}: </h4><span class="lead mx-2"><a href="{{$result->getlink()}}" target="_blank">{{$result->getresult()}}</a></span>
        </div>
    </div>
    @endforeach

    @else
    <div class="card my-4">
        <h4 class="mb-0 p-4"><?= get_label('no_results_found', 'No Results Found!') ?></h4>
    </div>
    @endif
</div>
@endsection