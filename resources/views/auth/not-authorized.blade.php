@extends('layout')

@section('title')
<?= get_label('not_authorized', 'Not authorized') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card text-center mt-4">
        <div class="card-body">
            <div class="misc-wrapper">
                <h2 class="mb-2 mx-2"><?= get_label('un_authorized_action', 'Un authorized action!') ?></h2>
                <p class="mb-4 mx-2"><?= get_label('not_authorized_notice', 'Sorry for the inconvenience but you are not authorized to perform this action') ?>.</p>
                <a href="/home" class="btn btn-primary"><?= get_label('home', 'Home') ?></a>
                <div class="mt-3">
                    <img src="{{asset('/storage/man-with-laptop-light.png')}}" alt="page-misc-error-light" width="500" class="img-fluid" data-app-dark-img="illustrations/page-misc-error-dark.png" data-app-light-img="illustrations/page-misc-error-light.png" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection