@extends('layout')

@section('title')
<?= get_label('general_settings', 'General settings') ?>
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
                        <?= get_label('general', 'General') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{url('/settings/store_general')}}" class="form-submit-event" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/settings/general">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="company_title" class="form-label"><?= get_label('company_title', 'Company title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="company_title" name="company_title" placeholder="<?= get_label('please_enter_company_title', 'Please enter company title') ?>" value="{{ $general_settings['company_title'] }}">

                        @error('company_title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror


                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="full_logo" class="form-label"><?= get_label('full_logo', 'Full logo') ?> <a data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('view_current_full_logo', 'View current full logo') ?>" href="{{asset($general_settings['full_logo'])}}" data-lightbox="full logo" data-title="<?= get_label('current_full_logo', 'Current full logo') ?>"> <i class='bx bx-show-alt'></i></a></label>
                        <input type="file" class="form-control" id="inputGroupFile02" name="full_logo">

                        @error('full_logo')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <div class="mb-3 col-md-6 d-none">
                        <label for="half_logo" class="form-label"><?= get_label('half_logo', 'Half logo') ?> <a data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('view_current_half_logo', 'View current half logo') ?>" href="{{asset($general_settings['half_logo'])}}" data-lightbox="half_logo" data-title="<?= get_label('current_half_logo', 'Current half logo') ?>"> <i class='bx bx-show-alt'></i></a></label>
                        <input type="file" class="form-control" id="inputGroupFile02" name="half_logo">

                        @error('half_logo')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="favicon" class="form-label"><?= get_label('favicon', 'Favicon') ?> <a data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('view_current_favicon', 'View current favicon') ?>" href="{{asset($general_settings['favicon'])}}" data-lightbox="favicon" data-title="<?= get_label('current_favicon', 'Current favicon') ?>"> <i class='bx bx-show-alt'></i></a></label>
                        <input type="file" class="form-control" id="inputGroupFile02" name="favicon">

                        @error('favicon')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- <div class="mb-3 col-md-6">
                        <label for="fonts" class="form-label">System Fonts <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="fonts" name="fonts">

                        @error('fonts')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror


                    </div> -->
                    <div class="mb-3 col-md-4">
                        <label for="currency_full_form" class="form-label"><?= get_label('currency_full_form', 'Currency full form') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="currency_full_form" name="currency_full_form" placeholder="<?= get_label('please_enter_currency_full_form', 'Please enter currency full form') ?>" value="{{$general_settings['currency_full_form']}}">

                        @error('currency_full_form')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror


                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="currency_symbol" class="form-label"><?= get_label('currency_symbol', 'Currency symbol') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="currency_symbol" name="currency_symbol" placeholder="<?= get_label('please_enter_currency_symbol', 'Please enter currency symbol') ?>" value="{{$general_settings['currency_symbol']}}">

                        @error('currency_symbol')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror


                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="currency_code" class="form-label"><?= get_label('currency_code', 'Currency code') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="currency_code" name="currency_code" placeholder="<?= get_label('please_enter_currency_code', 'Please enter currency code') ?>" value="{{$general_settings['currency_code']}}">

                        @error('currency_code')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror


                    </div>




                    <div class="mb-3 col-md-4">
                        <label for="" class="form-label"><?= get_label('currency_symbol_position', 'Currency symbol position') ?></label>
                        <div class="input-group">
                            <select class="form-select" name="currency_symbol_position">
                                <option value="before" {{ old('currency_symbol_position', $general_settings['currency_symbol_position']) == 'before' ? 'selected' : '' }}><?= get_label('before', 'Before') ?> - $100</option>
                                <option value="after" {{ old('currency_symbol_position', $general_settings['currency_symbol_position']) == 'after' ? 'selected' : '' }}><?= get_label('after', 'After') ?> - 100$</option>
                            </select>
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="" class="form-label"><?= get_label('currency_formate', 'Currency formate') ?></label>
                        <div class="input-group">
                            <select class="form-select" name="currency_formate">
                                <option value="comma_separated" {{ old('currency_formate', $general_settings['currency_formate']) == 'comma_separated' ? 'selected' : '' }}><?= get_label('comma_separated', 'Comma separated') ?> - 100,000</option>
                                <option value="dot_separated" {{ old('currency_formate', $general_settings['currency_formate']) == 'dot_separated' ? 'selected' : '' }}><?= get_label('dot_separated', 'Dot separated') ?> - 100.000</option>
                            </select>
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="" class="form-label"><?= get_label('decimal_points_in_currency', 'Decimal points in currency') ?></label>
                        <input class="form-control" type="number" name="decimal_points_in_currency" step="1" placeholder="Any number value - Example: if 2 - 100.00" value="{{$general_settings['decimal_points_in_currency']}}" oninput="this.value = Math.floor(this.value)" min="0">

                        @error('decimal_points_in_currency')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>




                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="user_id"><?= get_label('system_time_zone', 'System time zone') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" type="text" id="timezone" name="timezone" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                <option value=""><?= get_label('select_time_zone', 'Select time zone') ?></option>
                                @foreach ($timezones as $timezone)
                                <option value="{{ $timezone['2'] }}" data-gmt="<?= $timezone[1] ?>" {{ $general_settings['timezone']==$timezone[2]?'selected':'' }}>
                                    <span class="lh-lg">
                                        {{ $timezone['2'] }} &nbsp; - &nbsp; GMT &nbsp; {{ $timezone['1'] }} &nbsp; - &nbsp; {{ $timezone['0'] }}
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('timezone')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for=""><?= get_label('date_format', 'Date format') ?> <small class="text-muted">(<?= get_label('this_date_format_will_be_used_system_wide', 'This date format will be used system-wide') ?>)</small> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" type="text" id="date_format" name="date_format" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                <option value=""><?= get_label('select_date_format', 'Select date format') ?></option>
                                <option value="DD-MM-YYYY|d-m-Y" <?= $general_settings['date_format'] == 'DD-MM-YYYY|d-m-Y' ? 'selected' : '' ?>>Day-Month-Year with leading zero (04-08-2023)</option>
                                <option value="D-M-YY|j-n-y" <?= $general_settings['date_format'] == 'D-M-YY|j-n-y' ? 'selected' : '' ?>>Day-Month-Year with no leading zero (4-8-23)</option>
                                <option value="MM-DD-YYYY|m-d-Y" <?= $general_settings['date_format'] == 'MM-DD-YYYY|m-d-Y' ? 'selected' : '' ?>>Month-Day-Year with leading zero (08-04-2023)</option>
                                <option value="M-D-YY|n-j-y" <?= $general_settings['date_format'] == 'M-D-YY|n-j-y' ? 'selected' : '' ?>>Month-Day-Year with no leading zero (8-4-23)</option>
                                <option value="YYYY-MM-DD|Y-m-d" <?= $general_settings['date_format'] == 'YYYY-MM-DD|Y-m-d' ? 'selected' : '' ?>>Year-Month-Day with leading zero (2023-08-04)</option>
                                <option value="YY-M-D|Y-n-j" <?= $general_settings['date_format'] == 'YY-M-D|Y-n-j' ? 'selected' : '' ?>>Year-Month-Day with no leading zero (23-8-4)</option>
                                <option value="MMMM DD, YYYY|F d, Y" <?= $general_settings['date_format'] == 'MMMM DD, YYYY|F d, Y' ? 'selected' : '' ?>>Month name-Day-Year with leading zero
                                    (August 04, 2023)</option>
                                <!-- <option value="MMMM D, YY|F n, y" <?= $general_settings['date_format'] == 'MMMM D, YY|F n, y' ? 'selected' : '' ?>>Month name-Day-Year with no leading zero
                                    (August 4, 23)</option> -->
                                <option value="MMM DD, YYYY|M d, Y" <?= $general_settings['date_format'] == 'MMM DD, YYYY|M d, Y' ? 'selected' : '' ?>>Month abbreviation-Day-Year with leading zero
                                    (Aug 04, 2023)</option>
                                <!-- <option value="MMM D, YY|M n, y" <?= $general_settings['date_format'] == 'MMM D, YY|M n, y' ? 'selected' : '' ?>>Month abbreviation-Day-Year with no leading zero
                                    (Aug 4, 23)</option> -->
                                <option value="DD-MMM-YYYY|d-M-Y" <?= $general_settings['date_format'] == 'DD-MMM-YYYY|d-M-Y' ? 'selected' : '' ?>>Day with leading zero, Month abbreviation, Year (04-Aug-2023)</option>
                                <option value="DD MMM, YYYY|d M, Y" <?= $general_settings['date_format'] == 'DD MMM, YYYY|d M, Y' ? 'selected' : '' ?>>Day with leading zero, Month abbreviation, Year (04 Aug, 2023)</option>
                                <option value="YYYY-MMM-DD|Y-M-d" <?= $general_settings['date_format'] == 'YYYY-MMM-DD|Y-M-d' ? 'selected' : '' ?>>Year, Month abbreviation, Day with leading zero (2023-Aug-04)</option>
                                <option value="YYYY, MMM DD|Y, M d" <?= $general_settings['date_format'] == 'YYYY, MMM DD|Y, M d' ? 'selected' : '' ?>>Year, Month abbreviation, Day with leading zero (2023, Aug 04)</option>
                            </select>
                        </div>
                        @error('date_format')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="time_format" class="form-label"><?= get_label('time_format', 'Time format') ?> <small class="text-muted">(<?= get_label('this_time_format_will_be_used_system_wide', 'This time format will be used system-wide') ?>)</small></label>
                        <div class="input-group">
                            <select class="form-select" name="time_format" id="time_format">
                                <option value="H:i:s" {{ old('time_format', $general_settings['time_format'] ?? '') == 'H:i:s' ? 'selected' : '' }}>24-hour format - 15:45:30</option>
                                <option value="h:i:s A" {{ old('time_format', $general_settings['time_format'] ?? '') == 'h:i:s A' ? 'selected' : '' }}>12-hour format AM/PM uppercase - 03:45:30 PM</option>
                                <option value="h:i:s a" {{ old('time_format', $general_settings['time_format'] ?? '') == 'h:i:s a' ? 'selected' : '' }}>12-hour format AM/PM lowercase - 03:45:30 pm</option>
                            </select>
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label for="currency_symbol" class="form-label"><?= get_label('footer_text', 'Footer text') ?></label>
                        <textarea id="footer_text" name="footer_text" class="form-control"><?= $general_settings['footer_text'] ?></textarea>
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