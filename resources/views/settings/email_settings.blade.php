@extends('layout')

@section('title')
<?= get_label('email_settings', 'E-mail settings') ?>
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
                        <?= get_label('email', 'E-mail') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="alert alert-primary" role="alert"><?= get_label('important_settings_for_email_feature_to_be_work', 'Important settings for email feature to be work') ?>, <a href="https://www.gmass.co/smtp-test" target="_blank"><?= get_label('click_here_to_test_your_email_settings', 'Click here to test your email settings') ?></a>.</div>
    <div class="card">
        <div class="card-body">
            <form action="{{url('/settings/store_email')}}" class="form-submit-event" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/settings/email">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label"><?= get_label('email', 'E-mail') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? str_repeat('*', strlen($email_settings['email'])) : $email_settings['email'] ?>">

                        @error('email')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror


                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label"><?= get_label('password', 'Password') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="password" name="password" placeholder="<?= get_label('please_enter_password', 'Please enter password') ?>" value="<?= $email_settings['password'] ?>">

                        @error('password')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label"><?= get_label('smtp_host', 'SMTP host') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="" name="smtp_host" placeholder="<?= get_label('please_enter_smtp_host', 'Enter SMTP host') ?>" value="<?= $email_settings['smtp_host'] ?>">

                        @error('smtp_host')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label"><?= get_label('smtp_port', 'SMTP port') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="" name="smtp_port" placeholder="<?= get_label('please_enter_smtp_port', 'Enter SMTP port') ?>" value="<?= $email_settings['smtp_port'] ?>">

                        @error('smtp_port')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for=""><?= get_label('email_content_type', 'Email content type') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-select" type="text" id="" name="email_content_type">
                                <option value="text" <?= $email_settings['email_content_type'] == 'text' ? 'selected' : '' ?>>Text</option>
                                <option value="html" <?= $email_settings['email_content_type'] == 'html' ? 'selected' : '' ?>>HTML</option>
                            </select>
                        </div>
                        @error('email_content_type')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label" for=""><?= get_label('smtp_encryption', 'SMTP Encryption') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-select" type="text" id="" name="smtp_encryption">
                                <option value="off" <?= $email_settings['smtp_encryption'] == 'off' ? 'selected' : '' ?>>Off</option>
                                <option value="ssl" <?= $email_settings['smtp_encryption'] == 'ssl' ? 'selected' : '' ?>>SSL</option>
                                <option value="tls" <?= $email_settings['smtp_encryption'] == 'tls' ? 'selected' : '' ?>>TLS</option>
                            </select>
                        </div>
                        @error('smtp_encryption')
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