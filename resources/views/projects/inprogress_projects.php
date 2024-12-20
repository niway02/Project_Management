@extends('layout')

@section('title')
<?= get_label('create_project', 'Create project') ?>
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
                        <a href="{{url('/projects')}}"><?= get_label('projects', 'Projects') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?= get_label('create', 'Create') ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{url('/projects/store')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="redirect_url" value="/projects">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status_id"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-select" id="status_id" name="status_id">
                                @foreach($statuses as $status)
                                <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}" {{ old('status_id') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_status_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_status', 'Manage status') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('status_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-select" id="priority_id" name="priority_id">                            
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority_id') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$priority->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_priority_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priority', 'Manage priority') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('priority_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="budget" class="form-label"><?= get_label('budget', 'Budget') ?></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="budget" name="budget" placeholder="<?= get_label('please_enter_budget', 'Please enter budget') ?>" value="{{ old('budget') }}">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="start_date" name="start_date" class="form-control" value="">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="end_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="end_date" name="end_date" class="form-control" value="">
                        @error('end_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach($users as $user)
                                <?php $selected = $user->id == getAuthenticatedUser()->id ? "selected" : "" ?>
                                <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="client_id"><?= get_label('select_clients', 'Select clients') ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="client_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach ($clients as $client)
                                <?php $selected = $client->id == getAuthenticatedUser()->id && $auth_user->hasRole('client') ? "selected" : "" ?>
                                <option value="{{$client->id}}" {{ (collect(old('client_id'))->contains($client->id)) ? 'selected':'' }} <?= $selected ?>>{{$client->first_name}} {{$client->last_name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for=""><?= get_label('select_tags', 'Select tags') ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="tag_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach($tags as $tag)
                                <option value="{{$tag->id}}" {{ (collect(old('tag_ids'))->contains($tag->id)) ? 'selected':'' }}>{{$tag->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_tag_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_tag', 'Create tag') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/tags/manage"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_tags', 'Manage tags') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="mb-3 col-md-12">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?> <span class="asterisk">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="alert alert-primary alert-dismissible" role="alert">

                    <?= get_label('you_will_be_project_participant_automatically', 'You will be project participant automatically.') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('create', 'Create') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection