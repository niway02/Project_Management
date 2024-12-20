@extends('layout')

@section('title')
<?= get_label('projects', 'Projects') ?> - <?= get_label('list_view', 'List view') ?>
@endsection

@section('content')
<div class="container-fluid">
<div class="col-lg-12 col-md-12 order-1">
<div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/projects')}}"><?= get_label('projects', 'Projects') ?></a>
                    </li>
                    @if ($is_favorites==1)
                    <li class="breadcrumb-item"><a href="{{url('/projects/favorite')}}"><?= get_label('favorite', 'Favorite') ?></a></li>
                    @endif
                    <li class="breadcrumb-item active"><?= get_label('list', 'List') ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_project_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_project', 'Create project') ?>"><i class='bx bx-plus'></i></button></a>
            <a href="{{url(request()->has('status') ? '/projects?status=' . request()->status : '/projects')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('grid_view', 'Grid view') ?>"><i class='bx bxs-grid-alt'></i></button></a>
        </div>
        <input type="hidden" id="type">
        <input type="hidden" id="typeId">
    </div>
        <div class="row mt-4"> 
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #71dd37;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('completed', 'completed') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($projects) && count($projects) > 0?count($projects):0}}</h3>
                        <a href="/projects"><small class="text-success fw-semibold" style="color: #71dd37;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md " style="color: #696cff;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('in progress', 'In progress') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($projects) && count($projects) > 0?count($projects):0}}</h3>
                        <a href="/projects"><small style="color: #696cff;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #ffab00;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('Not started', 'Not started') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($projects) && count($projects) > 0?count($projects):0}}</h3>
                        <a href="/projects"><small style="color: #ffab00;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
         <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #ff3e1d;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('cancelled', 'Cancelled') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($projects) && count($projects) > 0?count($projects):0}}</h3>
                        <a href="/projects"><small style="color: #ff3e1d;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            <div>
 
    <div>
    <x-projects-card :projects="$projects" :users="$users" :clients="$clients" :favorites="$is_favorites" />
</div>
@endsection