@extends('layout')

@section('title')
<?= get_label('pusher_settings', 'Pusher settings') ?>
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
                        <?= get_label('pusher', 'Pusher') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="alert alert-primary" role="alert"><?= get_label('important_settings_for_chat_feature_to_be_work', 'Important settings for chat feature to be work') ?>, <a href="https://dashboard.pusher.com/apps" target="_blank"><?= get_label('click_here_to_find_these_settings_on_your_pusher_account', 'Click here to find these settings on your pusher account') ?></a>.</div>
    <div class="card">
        <div class="card-body">
            <form action="{{url('/settings/store_pusher')}}" class="form-submit-event" method="POST">
            <input type="hidden" name="redirect_url" value="/settings/pusher">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="company_title" class="form-label"><?= get_label('pusher_app_id', 'Pusher APP ID') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="pusher_app_id" placeholder="<?= get_label('please_enter_pusher_app_id', 'Please enter pusher APP ID') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? str_repeat('*', strlen($pusher_settings['pusher_app_id'])) : $pusher_settings['pusher_app_id'] ?>">

                        @error('pusher_app_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="company_title" class="form-label"><?= get_label('pusher_app_key', 'Pusher APP key') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="pusher_app_key" placeholder="<?= get_label('please_enter_pusher_app_key', 'Please enter pusher APP key') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? str_repeat('*', strlen($pusher_settings['pusher_app_key'])) : $pusher_settings['pusher_app_key'] ?>">

                        @error('pusher_app_key')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="company_title" class="form-label"><?= get_label('pusher_app_secret', 'Pusher APP secret') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="pusher_app_secret" placeholder="<?= get_label('please_enter_pusher_app_secret', 'Please enter pusher APP secret') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? str_repeat('*', strlen($pusher_settings['pusher_app_secret'])) : $pusher_settings['pusher_app_secret'] ?>">

                        @error('pusher_app_secret')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="company_title" class="form-label"><?= get_label('pusher_app_cluster', 'Pusher APP cluster') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="pusher_app_cluster" placeholder="<?= get_label('please_enter_pusher_app_cluster', 'Please enter pusher APP cluster') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? str_repeat('*', strlen($pusher_settings['pusher_app_cluster'])) : $pusher_settings['pusher_app_cluster'] ?>">

                        @error('pusher_app_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('update', 'Update') ?></button>
                        <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection