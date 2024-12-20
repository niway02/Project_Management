@extends('layout')

@section('title')
<?= get_label('update_meeting', 'Update meeting') ?>
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
                    <li class="breadcrumb-item">
                        <?= $meeting->title ?>
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
            <form action="{{url('/meetings/update/' . $meeting->id)}}" class="form-submit-event" method="POST">
                <input type="hidden" name="redirect_url" value="/meetings">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ $meeting->title }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="start_date" name="start_date" class="form-control" value="{{ format_date($meeting->start_date_time, null, 'Y-m-d H:i:s')}}">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label" for="start_time"><?= get_label('time', 'Time') ?> <span class="asterisk">*</span></label>
                        <input type="time" name="start_time" class="form-control" value="{{ format_date($meeting->start_date_time, null, 'Y-m-d H:i:s', 'H:i:s')}}">
                        @error('start_time')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="end_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="end_date" name="end_date" class="form-control" value="{{ format_date($meeting->end_date_time, null, 'Y-m-d H:i:s')}}">
                        @error('end_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label" for="end_time"><?= get_label('time', 'Time') ?> <span class="asterisk">*</span></label>
                        <input type="time" name="end_time" class="form-control" value="{{ format_date($meeting->end_date_time, null, 'Y-m-d H:i:s', 'H:i:s')}}">
                        @error('end_time')
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
                                $meeting_users = $meeting->users;
                                ?>
                                @foreach($users as $user)
                                <option value="{{$user->id}}" <?php if ($meeting_users->contains($user)) {
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
                                $meeting_clients = $meeting->clients;
                                ?>
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}" <?php if ($meeting_clients->contains($client)) {
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