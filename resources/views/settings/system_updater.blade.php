@extends('layout')

@section('title')
<?= get_label('system_updater', 'System updater') ?>
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
                        <?= get_label('settings', 'Settings') ?>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('system_updater', 'System updater') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="alert alert-primary alert-dismissible" role="alert"><?=get_label('post_update_clear_browser_cache','Clear your browser cache by pressing CTRL+F5 after updating the system.')?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center"><span class="badge bg-primary"><?= get_label('current_version', 'Current version') . ' - ' ?> {{get_current_version()}}</span>
                    </div>
                    <form class="form-horizontal" id="system-update" action="{{url('/settings/update-system')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="dropzone dz-clickable" id="system-update-dropzone">

                            </div>
                            <div class="form-group mt-4 text-center">
                                <button class="btn btn-primary" id="system_update_btn"><?= get_label('update_the_system', 'Update the system') ?></button>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection