@extends('layout')

@section('title')
<?= get_label('sms_gateway_settings', 'SMS gateway settings') ?>
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
                        <?= get_label('sms_gateway', 'SMS gateway') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="alert alert-primary" role="alert"><?= get_label('important_settings_for_SMS_feature_to_be_work', 'Important settings for SMS feature to be work') ?>, <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#sms_instuction_modal"><?= get_label('click_for_sms_gateway_settings_help', 'Click Here for Help with SMS Gateway Settings.') ?></a></div>
    @php
    $sms_gateway_settings = get_settings('sms_gateway_settings');
    @endphp
    <div class="card">
        <div class="card-body">
            <p></p>
            <form action="{{url('/settings/store_sms_gateway')}}" class="form-submit-event" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/settings/sms-gateway">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="base_url" class="form-label">{{get_label('base_url','Base URL')}} <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="base_url" value="{{$sms_gateway_settings['base_url'] ?? ''}}">
                    </div>

                    <div class="col-md-6">
                        <label for="sms_gateway_method" class="form-label">{{get_label('method','Method')}} <span class="asterisk">*</span></label>
                        <select class="form-select" name="sms_gateway_method">
                        <option value="POST" {{ ($sms_gateway_settings && isset($sms_gateway_settings['sms_gateway_method']) && $sms_gateway_settings['sms_gateway_method'] == 'POST') ? 'selected' : '' }}>POST</option>
                        <option value="GET" {{ ($sms_gateway_settings && isset($sms_gateway_settings['sms_gateway_method']) && $sms_gateway_settings['sms_gateway_method'] == 'GET') ? 'selected' : '' }}>GET</option>
                        </select>
                    </div>
                </div>
                <h4 class="mb-3 mt-4">{{get_label('create_authorization_token','Create authorization token')}}</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="base_url" class="form-label">{{get_label('account_sid','Account SID')}}</label>
                        <input type="text" class="form-control" id="converterInputAccountSID">
                    </div>

                    <div class="col-md-6">
                        <label for="base_url" class="form-label">{{get_label('auth_token','Auth token')}}</label>
                        <input type="text" class="form-control" id="converterInputAuthToken">
                    </div>
                </div>
                <button type="button" class="btn btn-primary me-2 mb-3" id="createBasicToken"><?= get_label('create', 'Create') ?></button>
                <h4 class="mb-4" id="basicToken"></h4>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="nav-align-top mb-4">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-header" aria-controls="navs-top-header" aria-selected="true">
                                    {{get_label('header','Header')}}
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-body" aria-controls="navs-top-body" aria-selected="false">
                                    {{get_label('body','Body')}}
                                    </button>
                                </li>
                                <li class="nav-item">

                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-params" aria-controls="navs-top-params" aria-selected="false">
                                    {{get_label('params','Params')}}
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="navs-top-header" role="tabpanel">
                                    <h6 class="text-muted">{{get_label('add_header_data','Add header data')}}</h6>
                                    <div class="row">
                                        <div class="col-md-12" id="header-rows">
                                            <div class="d-flex">
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <label class="form-label text-muted" for="">{{get_label('key','Key')}}</label>
                                                    <input type="text" id="header_key" class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <label class="form-label text-muted" for="">{{get_label('value','Value')}}</label>
                                                    <input type="text" id="header_value" class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-1 mx-3">
                                                    <label class="form-label text-muted" for=""><?= get_label('action', 'Action') ?></label>
                                                    <button type="button" class="btn btn-sm btn-success" id="add-header"><i class="bx bx-check"></i></button>
                                                </div>
                                            </div>

                                            @foreach ($sms_gateway_settings['header_data'] ?? [] as $key => $value)
                                            <div class="d-flex header-row">
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <input type="text" class="form-control" name="header_key[]" value="{{ $key }}">
                                                </div>
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <input type="text" class="form-control" name="header_value[]" value="{{ $value }}">
                                                </div>
                                                <div class="mb-3 col-md-1 mx-3">
                                                    <button type="button" class="btn btn-sm btn-danger remove-header"><i class="bx bx-trash"></i></button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-body" role="tabpanel">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#text-json-tab" aria-controls="text-json-tab" aria-selected="true">
                                                text/JSON
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#formdata-tab" aria-controls="formdata-tab" aria-selected="false">
                                                FormData
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="text-json-tab" role="tabpanel">
                                            <div class="col-md-12">
                                                <textarea name="text_format_data" class="text_format_data">{{$sms_gateway_settings['text_format_data'] ?? ''}}</textarea>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="formdata-tab" role="tabpanel">
                                            <h6 class="text-muted">{{get_label('add_body_data_parameters_and_values','Add body data parameter and values')}}</h6>
                                            <div class="col-md-12" id="body-formdata-rows">
                                                <div class="d-flex">
                                                    <div class="mb-3 col-md-5 mx-1">
                                                        <label class="form-label text-muted" for="">{{get_label('key','Key')}}</label>
                                                        <input type="text" id="body_formdata_key" class="form-control">
                                                    </div>
                                                    <div class="mb-3 col-md-5 mx-1">
                                                        <label class="form-label text-muted" for="">{{get_label('value','Value')}}</label>
                                                        <input type="text" id="body_formdata_value" class="form-control">
                                                    </div>
                                                    <div class="mb-3 col-md-1 mx-3">
                                                        <label class="form-label text-muted" for=""><?= get_label('action', 'Action') ?></label>
                                                        <button type="button" class="btn btn-sm btn-success" id="add-body-formdata"><i class="bx bx-check"></i></button>
                                                    </div>
                                                </div>

                                                @foreach ($sms_gateway_settings['body_formdata'] ?? [] as $key => $value)
                                                <div class="d-flex body-formdata-row">
                                                    <div class="mb-3 col-md-5 mx-1">
                                                        <input type="text" class="form-control" name="body_key[]" value="{{ $key }}">
                                                    </div>
                                                    <div class="mb-3 col-md-5 mx-1">
                                                        <input type="text" class="form-control" name="body_value[]" value="{{ $value }}">
                                                    </div>
                                                    <div class="mb-3 col-md-1 mx-3">
                                                        <button type="button" class="btn btn-sm btn-danger remove-body-formdata"><i class="bx bx-trash"></i></button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-params" role="tabpanel">
                                    <h6 class="text-muted">{{get_label('add_params','Add params')}}</h6>
                                    <div class="row">
                                        <div class="col-md-12" id="params-rows">
                                            <div class="d-flex">
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <label class="form-label text-muted" for="">{{get_label('key','Key')}}</label>
                                                    <input type="text" id="params_key" class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <label class="form-label text-muted" for="">{{get_label('value','Value')}}</label>
                                                    <input type="text" id="params_value" class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-1 mx-3">
                                                    <label class="form-label text-muted" for=""><?= get_label('action', 'Action') ?></label>
                                                    <button type="button" class="btn btn-sm btn-success" id="add-params"><i class="bx bx-check"></i></button>
                                                </div>
                                            </div>

                                            @foreach ($sms_gateway_settings['params_data'] ?? [] as $key => $value)
                                            <div class="d-flex params-row">
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <input type="text" class="form-control" name="params_key[]" value="{{ $key }}">
                                                </div>
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <input type="text" class="form-control" name="params_value[]" value="{{ $value }}">
                                                </div>
                                                <div class="mb-3 col-md-1 mx-3">
                                                    <button type="button" class="btn btn-sm btn-danger remove-params"><i class="bx bx-trash"></i></button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive text-nowrap">
                                                <h5 class="mt-5">{{get_label('available_placeholders', 'Available placeholders')}}</h5>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>{{get_label('placeholder','Placeholder')}}</th>
                                                            <th>{{get_label('action','Action')}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="copyText">{only_mobile_number}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(0)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <!-- <tr>
                                                            <td class="copyText">{mobile_number_with_country_code}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(1)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr> -->
                                                        <tr>
                                                            <td class="copyText">{country_code}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(1)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{message}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(2)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('update', 'Update') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                </div>
        </div>
        </form>
    </div>
</div>
<script src="{{asset('assets/js/pages/sms-gateway-settings.js')}}"></script>
@endsection