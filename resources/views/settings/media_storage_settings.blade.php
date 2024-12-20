@extends('layout')

@section('title')
<?= get_label('media_storage_settings', 'Media storage settings') ?>
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
                        <?= get_label('media_storage', 'Media storage') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ url('/settings/store_media_storage') }}" class="form-submit-event" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/settings/media-storage">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= get_label('select_storage_type', 'Select storage type') ?><span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-select" id="media_storage_type" name="media_storage_type">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                <option value="local" {{ $media_storage_settings['media_storage_type'] === 'local' ? 'selected' : '' }}><?= get_label('local_storage', 'Local storage') ?></option>
                                <option value="s3" {{ $media_storage_settings['media_storage_type'] === 's3' ? 'selected' : '' }}>Amazon AWS S3</option>
                            </select>
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                    <div class="aws-s3-fields {{ $media_storage_settings['media_storage_type'] === 's3' ? '' : 'd-none' }}">
                        @if (config('constants.ALLOW_MODIFICATION') === 1)
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="s3_key" class="form-label">AWS S3 Access Key <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" name="s3_key" placeholder="<?= get_label('please_enter_aws_s3_access_key', 'Please enter AWS S3 access key') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? str_repeat('*', strlen($media_storage_settings['s3_key'])) : $media_storage_settings['s3_key'] ?>">

                                @error('s3_key')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="s3_secret" class="form-label">AWS S3 Secret Key <span class="asterisk">*</span></label>
                                <input class="form-control" type="password" name="s3_secret" placeholder="<?= get_label('please_enter_aws_s3_secret_key', 'Please enter AWS S3 secret key') ?>" value="<?= $media_storage_settings['s3_secret']  ?>">

                                @error('s3_secret')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="s3_region" class="form-label">AWS S3 Region <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" name="s3_region" placeholder="<?= get_label('please_enter_aws_s3_region', 'Please enter AWS S3 region') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? str_repeat('*', strlen($media_storage_settings['s3_region'])) : $media_storage_settings['s3_region'] ?>">

                                @error('s3_region')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="s3_bucket" class="form-label">AWS S3 Bucket <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" name="s3_bucket" placeholder="<?= get_label('please_enter_aws_s3_bucket', 'Please enter AWS S3 bucket') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? str_repeat('*', strlen($media_storage_settings['s3_bucket'])) : $media_storage_settings['s3_bucket'] ?>">

                                @error('s3_bucket')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-md-12">
                            <div class="alert alert-danger" role="alert"><?= get_label('not_allowed_in_demo_mode', 'Not allowed in demo mode') ?>.</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn">{{ get_label('update', 'Update') }}</button>
                    <button type="reset" class="btn btn-outline-secondary">{{ get_label('cancel', 'Cancel') }}</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection