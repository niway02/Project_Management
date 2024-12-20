@extends('layout')
<title>Forgot password - {{$general_settings['company_title']}}</title>
@section('content')
<!-- Content -->

<div class="container-fluid">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Forgot Password -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="/home" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <img src="{{asset($general_settings['full_logo'])}}" width="300px" alt=""/>
                            </span>
                            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Taskify</span> -->
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Forgot Password? 🔒</h4>
                    <p class="mb-4">Enter your email and we'll send you password reset link</p>
                    <form id="formAuthentication" class="mb-3 form-submit-event" action="/forgot-password-mail" method="POST">
                        <input type="hidden" name="dnr">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="asterisk">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="{{ old('email') }}" autofocus />
                        </div>
                        @error('email')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <button type="submit" id="submit_btn" class="btn btn-primary d-grid w-100">Submit</button>
                    </form>
                    <div class="text-center">
                        <a href="{{url('/')}}" class="d-flex align-items-center justify-content-center">
                            <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                            Back to login
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
</div>

<!-- / Content -->

@endsection