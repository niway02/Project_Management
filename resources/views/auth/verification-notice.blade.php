@extends('layout')
<title>Pending email verification - {{$general_settings['company_title']}}</title>
@section('content')
<!-- Content -->



<div class="container h-100">
    <div class="row align-items-center h-100">
        <div class="col-6 mx-auto">
            <div class="alert alert-danger" role="alert"><?= get_label('pending_email_verification', 'Pending email verification. Please check verification mail sent to you!') ?></div>
            <div class="text-center">
                <a href="/email/verification-notification"><button type="button" class="btn btn-primary"><i class='bx bx-revision'></i> <?= get_label('resend_verification_link', 'Resend verification link') ?></button></a>
            </div>
        </div>
    </div>
</div>

<!-- / Content -->

@endsection