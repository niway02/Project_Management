@extends('layout')

@section('title')
<?= get_label('templates', 'Templates') ?>
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
                        <?= get_label('templates', 'Templates') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="demo-inline-spacing mt-3">
                        <!-- Tab Switcher for Email and SMS Templates -->
                        <div class="list-group list-group-horizontal-md text-md-center">
                            <a class="list-group-item list-group-item-action active" id="email-tab" data-bs-toggle="list" href="#email-templates"><?= get_label('email_templates', 'Email Templates') ?></a>
                            <a class="list-group-item list-group-item-action" id="sms-tab" data-bs-toggle="list" href="#sms-templates"><?= get_label('sms_templates', 'SMS Templates') ?></a>
                        </div>

                        <!-- Main Tab Content -->
                        <div class="tab-content px-0 mt-3">
                            <!-- Email Templates Tab Content -->
                            <div class="tab-pane fade show active" id="email-templates">
                                <div class="list-group list-group-horizontal-md text-md-center">
                                    <a class="list-group-item list-group-item-action active" id="email-account-creation-list-item" data-bs-toggle="list" href="#email-account-creation">{{get_label('account_creation','Account creation')}}</a>
                                    <a class="list-group-item list-group-item-action" id="email-verify-email-list-item" data-bs-toggle="list" href="#email-verify-email">{{get_label('email_verification','Email verification')}}</a>
                                    <a class="list-group-item list-group-item-action" id="email-forgot-password-list-item" data-bs-toggle="list" href="#email-forgot-password">{{get_label('forgot_password','Forgot password')}}</a>

                                    <a class="list-group-item list-group-item-action" id="email-project-assignment-list-item" data-bs-toggle="list" href="#email-project-assignment">{{get_label('project_assignment','Project assignment')}}</a>
                                    <a class="list-group-item list-group-item-action" id="email-task-assignment-list-item" data-bs-toggle="list" href="#email-task-assignment">{{get_label('task_assignment','Task assignment')}}</a>
                                    <a class="list-group-item list-group-item-action" id="email-workspace-assignment-list-item" data-bs-toggle="list" href="#email-workspace-assignment">{{get_label('workspace_assignment','Workspace assignment')}}</a>
                                    <a class="list-group-item list-group-item-action" id="email-meeting-assignment-list-item" data-bs-toggle="list" href="#email-meeting-assignment">{{get_label('meeting_assignment','Meeting assignment')}}</a>
                                </div>
                                <div class="tab-content px-0 mt-0">
                                    <div class="tab-pane fade show active" id="email-account-creation">
                                        @php
                                        $account_creation_template = App\Models\Template::where('type', 'email')
                                        ->where('name', 'account_creation')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('account_creation_email_info','This template will be used for the email sent to notify users/clients about the successful creation of their account.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="email">
                                            <input type="hidden" name="name" value="account_creation">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('subject', 'Subject') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {FIRST_NAME},{LAST_NAME},{COMPANY_TITLE})</small></label>
                                            <input type="text" class="form-control mb-3" name="subject" value="{{ $account_creation_template->subject ?? '' }}" placeholder="{{get_label('please_enter_email_subject','Please enter email subject')}}">


                                            <label class="form-label"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="email_account_creation" name="content" class="form-control">{{ $account_creation_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('account_creation_email_will_not_sent','If Deactive, account creation email won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="email_account_creation_status_active" name="status" value="1" {{ !($account_creation_template) || $account_creation_template && $account_creation_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_account_creation_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="email_account_creation_status_deactive" name="status" value="0" {{ $account_creation_template && $account_creation_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_account_creation_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(0)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(1)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{USER_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(2)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{PASSWORD}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(3)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(4)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_LOGO}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(5)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(6)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{CURRENT_YEAR}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(7)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="email-verify-email">
                                        @php
                                        $verify_email_template = App\Models\Template::where('type', 'email')
                                        ->where('name', 'verify_email')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('verify_user_client_email_info','This template will be used for the email sent for verifying new user/client creation.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="email">
                                            <input type="hidden" name="name" value="verify_email">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('subject', 'Subject') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {FIRST_NAME},{LAST_NAME},{COMPANY_TITLE})</small></label>
                                            <input type="text" class="form-control mb-3" name="subject" value="{{ $verify_email_template->subject ?? '' }}" placeholder="{{get_label('please_enter_email_subject','Please enter email subject')}}">


                                            <label class="form-label"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="email_verify_email" name="content" class="form-control">{{ $verify_email_template->content ?? '' }}</textarea>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(8)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(9)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{VERIFY_EMAIL_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(10)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(11)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_LOGO}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(12)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(13)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{CURRENT_YEAR}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(14)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="email-forgot-password">
                                        @php
                                        $forgot_password_template = App\Models\Template::where('type', 'email')
                                        ->where('name', 'forgot_password')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('forgot_password_email_info','This template will be used for the email sent to users/clients to reset their password if they have forgotten it.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="email">
                                            <input type="hidden" name="name" value="forgot_password">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('subject', 'Subject') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {FIRST_NAME},{LAST_NAME},{COMPANY_TITLE})</small></label>
                                            <input type="text" class="form-control mb-3" name="subject" value="{{ $forgot_password_template->subject ?? '' }}" placeholder="{{get_label('please_enter_email_subject','Please enter email subject')}}">


                                            <label class="form-label"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="email_forgot_password" name="content" class="form-control">{{ $forgot_password_template->content ?? '' }}</textarea>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(15)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(16)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{RESET_PASSWORD_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(17)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(18)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_LOGO}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(19)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(20)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{CURRENT_YEAR}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(21)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="tab-pane fade" id="email-project-assignment">
                                        @php
                                        $project_assignment_template = App\Models\Template::where('type', 'email')
                                        ->where('name', 'project_assignment')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('project_assignment_email_info','This template will be used for the email sent to users/clients when they are assigned a project.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="email">
                                            <input type="hidden" name="name" value="project_assignment">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('subject', 'Subject') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {PROJECT_ID},{PROJECT_TITLE},{FIRST_NAME},{LAST_NAME},{COMPANY_TITLE})</small></label>
                                            <input type="text" class="form-control mb-3" name="subject" value="{{ $project_assignment_template->subject ?? '' }}" placeholder="{{get_label('please_enter_email_subject','Please enter email subject')}}">


                                            <label class="form-label"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="email_project_assignment" name="content" class="form-control">{{ $project_assignment_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('project_assignment_email_will_not_sent','If Deactive, project assignment email won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="email_project_assignment_status_active" name="status" value="1" {{ !($project_assignment_template) || $project_assignment_template && $project_assignment_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_project_assignment_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="email_project_assignment_status_deactive" name="status" value="0" {{ $project_assignment_template && $project_assignment_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_project_assignment_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{PROJECT_ID}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(22)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{PROJECT_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(23)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(24)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(25)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{PROJECT_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(26)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(27)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_LOGO}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(28)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(29)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{CURRENT_YEAR}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(30)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="email-task-assignment">
                                        @php
                                        $task_assignment_template = App\Models\Template::where('type', 'email')
                                        ->where('name', 'task_assignment')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('task_assignment_email_info','This template will be used for the email sent to users/clients when they are assigned a task.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="email">
                                            <input type="hidden" name="name" value="task_assignment">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('subject', 'Subject') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {TASK_ID},{TASK_TITLE},{FIRST_NAME},{LAST_NAME},{COMPANY_TITLE})</small></label>
                                            <input type="text" class="form-control mb-3" name="subject" value="{{ $task_assignment_template->subject ?? '' }}" placeholder="{{get_label('please_enter_email_subject','Please enter email subject')}}">


                                            <label class="form-label"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="email_task_assignment" name="content" class="form-control">{{ $task_assignment_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('task_assignment_email_will_not_sent','If Deactive, task assignment email won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="email_task_assignment_status_active" name="status" value="1" {{ !($task_assignment_template) || $task_assignment_template && $task_assignment_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_task_assignment_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="email_task_assignment_status_deactive" name="status" value="0" {{ $task_assignment_template && $task_assignment_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_task_assignment_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{TASK_ID}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(31)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{TASK_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(32)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(33)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(34)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{TASK_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(35)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(36)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_LOGO}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(37)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(38)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{CURRENT_YEAR}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(39)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="email-workspace-assignment">
                                        @php
                                        $workspace_assignment_template = App\Models\Template::where('type', 'email')
                                        ->where('name', 'workspace_assignment')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('workspace_assignment_email_info','This template will be used for the email sent to users/clients when they are added to a workspace.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="email">
                                            <input type="hidden" name="name" value="workspace_assignment">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('subject', 'Subject') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {WORKSPACE_ID},{WORKSPACE_TITLE},{FIRST_NAME},{LAST_NAME},{COMPANY_TITLE})</small></label>
                                            <input type="text" class="form-control mb-3" name="subject" value="{{ $workspace_assignment_template->subject ?? '' }}" placeholder="{{get_label('please_enter_email_subject','Please enter email subject')}}">


                                            <label class="form-label"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="email_workspace_assignment" name="content" class="form-control">{{ $workspace_assignment_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('workspace_assignment_email_will_not_sent','If Deactive, workspace assignment email won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="email_workspace_assignment_status_active" name="status" value="1" {{ !($workspace_assignment_template) || $workspace_assignment_template && $workspace_assignment_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_workspace_assignment_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="email_workspace_assignment_status_deactive" name="status" value="0" {{ $workspace_assignment_template && $workspace_assignment_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_workspace_assignment_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{WORKSPACE_ID}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(40)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{WORKSPACE_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(41)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{WORKSPACE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(42)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(43)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(44)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(45)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_LOGO}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(46)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(47)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{CURRENT_YEAR}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(48)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="email-meeting-assignment">
                                        @php
                                        $meeting_assignment_template = App\Models\Template::where('type', 'email')
                                        ->where('name', 'meeting_assignment')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('meeting_assignment_email_info','This template will be used for the email sent to users/clients when they are added to a meeting.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="email">
                                            <input type="hidden" name="name" value="meeting_assignment">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('subject', 'Subject') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {MEETING_ID},{MEETING_TITLE},{FIRST_NAME},{LAST_NAME},{COMPANY_TITLE})</small></label>
                                            <input type="text" class="form-control mb-3" name="subject" value="{{ $meeting_assignment_template->subject ?? '' }}" placeholder="{{get_label('please_enter_email_subject','Please enter email subject')}}">


                                            <label class="form-label"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="email_meeting_assignment" name="content" class="form-control">{{ $meeting_assignment_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('meeting_assignment_email_will_not_sent','If Deactive, meeting assignment email won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="email_meeting_assignment_status_active" name="status" value="1" {{ !($meeting_assignment_template) || $meeting_assignment_template && $meeting_assignment_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_meeting_assignment_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="email_meeting_assignment_status_deactive" name="status" value="0" {{ $meeting_assignment_template && $meeting_assignment_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="email_meeting_assignment_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{MEETING_ID}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(49)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{MEETING_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(50)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{MEETING_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(51)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(52)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(53)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(54)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_LOGO}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(55)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(56)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{CURRENT_YEAR}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(57)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- SMS Templates Tab Content -->
                            <div class="tab-pane fade" id="sms-templates">
                                <div class="list-group list-group-horizontal-md text-md-center">
                                    <a class="list-group-item list-group-item-action active" id="sms-project-assignment-list-item" data-bs-toggle="list" href="#sms-project-assignment">{{get_label('project_assignment','Project assignment')}}</a>
                                    <a class="list-group-item list-group-item-action" id="sms-task-assignment-list-item" data-bs-toggle="list" href="#sms-task-assignment">{{get_label('task_assignment','Task assignment')}}</a>
                                    <a class="list-group-item list-group-item-action" id="sms-workspace-assignment-list-item" data-bs-toggle="list" href="#sms-workspace-assignment">{{get_label('workspace_assignment','Workspace assignment')}}</a>
                                    <a class="list-group-item list-group-item-action" id="sms-meeting-assignment-list-item" data-bs-toggle="list" href="#sms-meeting-assignment">{{get_label('meeting_assignment','Meeting assignment')}}</a>
                                </div>
                                <div class="tab-content px-0 mt-3">
                                    <div class="tab-pane fade show active" id="sms-project-assignment">
                                        @php
                                        $sms_project_assignment_template = App\Models\Template::where('type', 'sms')
                                        ->where('name', 'project_assignment')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('project_assignment_sms_info','This template will be used for the SMS sent to users/clients when they are assigned a project.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="sms">
                                            <input type="hidden" name="name" value="project_assignment">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="sms_project_assignment" name="content" class="form-control" rows="5">{{ $sms_project_assignment_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('project_assignment_sms_will_not_sent','If Deactive, project assignment SMS won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="sms_project_assignment_status_active" name="status" value="1" {{ !($sms_project_assignment_template) || $sms_project_assignment_template && $sms_project_assignment_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="sms_project_assignment_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="sms_project_assignment_status_deactive" name="status" value="0" {{ $sms_project_assignment_template && $sms_project_assignment_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="sms_project_assignment_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning sms-restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{PROJECT_ID}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(58)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{PROJECT_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(59)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(60)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(61)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{PROJECT_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(62)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(63)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(64)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="tab-pane fade" id="sms-task-assignment">
                                        @php
                                        $sms_task_assignment_template = App\Models\Template::where('type', 'sms')
                                        ->where('name', 'task_assignment')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('task_assignment_sms_info','This template will be used for the SMS sent to users/clients when they are assigned a task.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="sms">
                                            <input type="hidden" name="name" value="task_assignment">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="sms_task_assignment" name="content" class="form-control" rows="5">{{ $sms_task_assignment_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('task_assignment_sms_will_not_sent','If Deactive, task assignment SMS won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="sms_task_assignment_status_active" name="status" value="1" {{ !($sms_task_assignment_template) || $sms_task_assignment_template && $sms_task_assignment_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="sms_task_assignment_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="sms_task_assignment_status_deactive" name="status" value="0" {{ $sms_task_assignment_template && $sms_task_assignment_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="sms_task_assignment_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning sms-restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{TASK_ID}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(65)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{TASK_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(66)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(67)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(68)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{TASK_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(69)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(70)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(71)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="tab-pane fade" id="sms-workspace-assignment">
                                        @php
                                        $sms_workspace_assignment_template = App\Models\Template::where('type', 'sms')
                                        ->where('name', 'workspace_assignment')
                                        ->first();
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('workspace_assignment_sms_info','This template will be used for the email sent to users/clients when they are added to a workspace.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="sms">
                                            <input type="hidden" name="name" value="workspace_assignment">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="sms_workspace_assignment" name="content" class="form-control" rows="5">{{ $sms_workspace_assignment_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('workspace_assignment_sms_will_not_sent','If Deactive, workspace assignment SMS won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="sms_workspace_assignment_status_active" name="status" value="1" {{ !($sms_workspace_assignment_template) || $sms_workspace_assignment_template && $sms_workspace_assignment_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="sms_workspace_assignment_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="sms_workspace_assignment_status_deactive" name="status" value="0" {{ $sms_workspace_assignment_template && $sms_workspace_assignment_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="sms_workspace_assignment_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning sms-restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{WORKSPACE_ID}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(72)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{WORKSPACE_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(73)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(74)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(75)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{WORKSPACE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(76)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(77)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(78)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="tab-pane fade" id="sms-meeting-assignment">
                                        @php
                                        $sms_meeting_assignment_template = App\Models\Template::where('type', 'sms')
                                        ->where('name', 'meeting_assignment')
                                        ->first();                                        
                                        @endphp
                                        <small class="text-light fw-semibold mb-1"><?=get_label('meeting_assignment_sms_info','This template will be used for the email sent to users/clients when they are added to a meeting.')?></small>
                                        <form action="{{url('/settings/store_template')}}" class="form-submit-event" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="sms">
                                            <input type="hidden" name="name" value="meeting_assignment">
                                            <input type="hidden" name="dnr">
                                            <label class="form-label mt-3"><?= get_label('message', 'Message') ?> <span class="asterisk">*</span> <small class="text-muted">({{get_label('possible_placeholders', 'Possible placeholders')}} : {{get_label('all_available_placeholders', 'All available placeholders')}})</small></label>
                                            <textarea id="sms_meeting_assignment" name="content" class="form-control" rows="5">{{ $sms_meeting_assignment_template->content ?? '' }}</textarea>
                                            <div class="col-md-6 mt-4 mb-5">
                                                <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?=get_label('meeting_assignment_sms_will_not_sent','If Deactive, project assignment SMS won\'t be sent')?></small>)</label>
                                                <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" id="sms_meeting_assignment_status_active" name="status" value="1" {{ !($sms_meeting_assignment_template) || $sms_meeting_assignment_template && $sms_meeting_assignment_template->status == 1 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="sms_meeting_assignment_status_active">{{ get_label('active', 'Active') }}</label>

                                                    <input type="radio" class="btn-check" id="sms_meeting_assignment_status_deactive" name="status" value="0" {{ $sms_meeting_assignment_template && $sms_meeting_assignment_template->status == 0 ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="sms_meeting_assignment_status_deactive">{{ get_label('deactive', 'Deactive') }}</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                                                <button type="button" class="btn btn-warning sms-restore-default"><?= get_label('reset_to_default', 'Reset to default') ?></button>
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
                                                            <td class="copyText">{MEETING_ID}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(79)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{MEETING_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(80)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{FIRST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(81)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{LAST_NAME}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(82)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{MEETING_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(83)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{COMPANY_TITLE}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(84)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="copyText">{SITE_URL}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="copyToClipboard(85)" title="{{get_label('copy_to_clipboard','Copy to clipboard')}}">
                                                                    <i class="bx bx-copy text-warning mx-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="{{asset('assets/js/pages/templates.js')}}"></script>
@endsection