@extends('layout')

@section('title')
<?= get_label('update_profile', 'Update profile') ?>
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
                    <li class="breadcrumb-item active">
                        <?= get_label('profile', 'Profile') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header"><?= get_label('profile_details', 'Profile details') ?></h5>
                <!-- Account -->
                <div class="card-body">
                    <form action="{{url('/profile/update_photo/' . getAuthenticatedUser()->id)}}" class="form-submit-event" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="redirect_url" value="/account/{{getAuthenticatedUser()->id}}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{getAuthenticatedUser()->photo ? asset('storage/' . getAuthenticatedUser()->photo) : asset('storage/photos/no-image.jpg')}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile02" name="upload">
                                    <button class="btn btn-outline-primary" type="submit" id="submit_btn"><?= get_label('update_profile_photo', 'Update profile photo') ?></button>
                                </div>

                                @error('upload')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                            </div>

                        </div>
                    </form>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <form id="formAccountSettings" method="POST" class="form-submit-event" action="/profile/update/{{getAuthenticatedUser()->id}}">
                        <input type="hidden" name="redirect_url" value="/account/{{getAuthenticatedUser()->id}}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label"><?= get_label('first_name', 'First name') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" id="first_name" name="first_name" placeholder="<?= get_label('please_enter_first_name', 'Please enter first name') ?>" value="{{ getAuthenticatedUser()->first_name }}" autofocus />

                                @error('first_name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>



                            <div class="mb-3 col-md-6">
                                <label for="lastName" class="form-label"><?= get_label('last_name', 'Last name') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" name="last_name" placeholder="<?= get_label('please_enter_last_name', 'Please enter last name') ?>" id="last_name" value="{{getAuthenticatedUser()->last_name}}" />


                                @error('last_name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>


                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="phone"><?= get_label('phone_number', 'Phone number') ?> <span class="asterisk">*</span></label>
                                <input type="text" id="phone" name="phone" placeholder="<?= get_label('please_enter_phone_number', 'Please enter phone number') ?>" class="form-control" placeholder="" value="{{getAuthenticatedUser()->phone}}" />


                                @error('phone')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>


                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="email"><?= get_label('email', 'E-mail') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="{{getAuthenticatedUser()->email}}" @if(!isAdminOrHasAllDataAccess()) readonly @endif>

                                @error('email')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label"><?= get_label('password', 'Password') ?> <small class="text-muted"> (Leave it blank if no change)</small></label>
                                <input class="form-control" type="password" id="password" name="password" placeholder="<?= get_label('please_enter_password', 'Please enter password') ?>">

                                @error('password')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password_confirmation" class="form-label"><?= get_label('confirm_password', 'Confirm password') ?></label>
                                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="<?= get_label('please_re_enter_password', 'Please re enter password') ?>">

                                @error('password_confirmation')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            @if(getAuthenticatedUser()->getRoleNames()->first() == 'admin')

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="role"><?= get_label('role', 'Role') ?> <span class="asterisk">*</span></label>
                                <div class="input-group">
                                    <select class="form-select text-capitalize" id="role" name="role">
                                        @foreach ($roles as $role)

                                        <option value="{{$role->id}}" <?php if ($user->getRoleNames()->first() == $role->name) {
                                                                            echo 'selected';
                                                                        }  ?>>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('role')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            @else

                            <div class="mb-3 col-md-6">
                                <input type="hidden" name="role" value="<?= $user->roles->pluck('id')[0] ?>">
                                <label class="form-label" for="role"><?= get_label('role', 'Role') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" id="exampleFormControlReadOnlyInput1" value="{{getAuthenticatedUser()->getRoleNames()->first()}}" readonly="">

                                @error('role')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            @endif



                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="address"><?= get_label('address', 'Address') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" id="address" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" name="address" value="{{getAuthenticatedUser()->address}}">

                                @error('address')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>



                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="city"><?= get_label('city', 'City') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" id="city" placeholder="<?= get_label('please_enter_city', 'Please enter city') ?>" name="city" value="{{getAuthenticatedUser()->city}}">

                                @error('city')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="state"><?= get_label('state', 'State') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" id="state" placeholder="<?= get_label('please_enter_state', 'Please enter state') ?>" name="state" value="{{getAuthenticatedUser()->state}}">

                                @error('state')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="country"><?= get_label('country', 'Country') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" id="country" placeholder="<?= get_label('please_enter_country', 'Please enter country') ?>" name="country" value="{{getAuthenticatedUser()->country}}">

                                @error('country')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="zip"><?= get_label('zip_code', 'ZIP code') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" id="zip" placeholder="<?= get_label('please_enter_zip_code', 'Please enter ZIP code') ?>" name="zip" value="{{getAuthenticatedUser()->zip}}">

                                @error('zip')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <div class="mt-2">
                                <button type="submit" id="submit_btn" class="btn btn-primary me-2"><?= get_label('update', 'Update') ?></button>
                                <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                            </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
            <div class="card">
                <h5 class="card-header"><?= get_label('delete_account', 'Delete account') ?></h5>
                <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1"><?= get_label('delete_account_alert', 'Are you sure you want to delete your account?') ?></h6>
                            <p class="mb-0"><?= get_label('delete_account_alert_sub_text', 'Once you delete your account, there is no going back. Please be certain.') ?></p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal"><?= get_label('delete_account', 'Delete account') ?></button>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection