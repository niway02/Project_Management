@extends('layout')
<title>Reset password - {{$general_settings['company_title']}}</title>
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
                                <img src="{{asset($general_settings['full_logo'])}}" width="300px" alt="" />
                            </span>
                            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Taskify</span> -->
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Reset Password 🔒</h4>
                    <p class="mb-4">Enter details and hit submit to reset your password</p>
                    <form id="formAuthentication" class="mb-3 form-submit-event" action="/reset-password" method="POST">
                        <input type="hidden" name="token" value="{{$token}}" />
                        <input type="hidden" name="redirect_url" value="/">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="asterisk">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" autofocus />
                            @error('email')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">New password <span class="asterisk">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Enter new password" />
                            @error('password')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Confirm new password <span class="asterisk">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password" />
                            @error('password_confirmation')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-primary d-grid w-100" id="submit_btn">Submit</button>
                    </form>
                    <div class="text-center">
                        <a href="{{url('/forgot-password')}}" class="d-flex align-items-center justify-content-center">
                            <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                            Back to forgot password
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