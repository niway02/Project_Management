@extends('layout')

@section('title')
<?= get_label('create_meeting', 'Create meeting') ?>
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
                        <a href="{{url('/meetings')}}"><?= get_label('meetings', 'Meetings') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('create', 'Create') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{url('/meetings/store')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="redirect_url" value="/meetings">
                @csrf
                <div class="row">
                    <div class="mb-3">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for=""><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="start_date" name="start_date" class="form-control" value="">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label" for=""><?= get_label('time', 'Time') ?> <span class="asterisk">*</span></label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                        @error('start_time')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="end_date_time"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="end_date" name="end_date" class="form-control" value="">
                        @error('end_date_time')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label" for=""><?= get_label('time', 'Time') ?> <span class="asterisk">*</span></label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                        @error('end_time')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('selec_users', 'Select users') ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="user_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach($users as $user)
                                <?php $selected = $user->id == getAuthenticatedUser()->id  ? "selected" : "" ?>
                                <option value="{{$user->id}}" {{ (collect(old('user_ids'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
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
                                @foreach ($clients as $client)
                                <?php $selected = $client->id == getAuthenticatedUser()->id && $auth_user->hasRole('client') ? "selected" : "" ?>
                                <option value="{{$client->id}}" {{ (collect(old('client_ids'))->contains($client->id)) ? 'selected':'' }} <?= $selected ?>>{{$client->first_name}} {{$client->last_name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="alert alert-primary alert-dismissible" role="alert">
                    <?= get_label('you_will_be_meeting_participant_automatically', 'You will be meeting participant automatically.') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="mt-2">
                    <button type="submit" id="submit_btn" class="btn btn-primary me-2" id="showToastPlacement"><?= get_label('create', 'Create') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection