@extends('layout')

@section('title')
<?= get_label('update_workspace', 'Update workspace') ?>
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
                        <a href="{{url('/workspaces')}}"><?= get_label('workspaces', 'Workspaces') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <?= $workspace->title ?>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('update', 'Update') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{url('/workspaces/update/' . $workspace->id)}}" class="form-submit-event" method="POST">
                <input type="hidden" name="redirect_url" value="/workspaces">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ $workspace->title }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="user_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                <?php
                                $workspace_users = $workspace->users;
                                ?>
                                @foreach($users as $user)
                                <option value="{{$user->id}}" <?php if ($workspace_users->contains($user)) {
                                                                    echo "selected";
                                                                } ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="client_id"><?= get_label('select_clients', 'Select clients') ?></label>
                        <div class="input-group">

                            <select id="" class="form-control js-example-basic-multiple" name="client_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                <?php
                                $workspace_clients = $workspace->clients;
                                ?>
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}" <?php if ($workspace_clients->contains($client)) {
                                                                    echo "selected";
                                                                } ?>>{{$client->first_name}} {{$client->last_name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('update', 'Update') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection