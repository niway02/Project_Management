@php
$auth_user = getAuthenticatedUser();
@endphp
@if (Request::is('projects') || Request::is('projects/*') || Request::is('tasks') || Request::is('tasks/*') || Request::is('status/manage'))
<div class="modal fade" id="create_status_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/status/store')}}" method="POST">
            @if (Request::is('status/manage'))
            <input type="hidden" name="dnr">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_status', 'Create status') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="nameBasic" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="color" name="color">
                            <option class="badge bg-label-primary" value="primary" {{ old('color') == "primary" ? "selected" : "" }}>
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary" {{ old('color') == "secondary" ? "selected" : "" }}><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success" {{ old('color') == "success" ? "selected" : "" }}><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark" {{ old('color') == "dark" ? "selected" : "" }}><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('create', 'Create') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('status/manage'))
<div class="modal fade" id="edit_status_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('/status/update')}}" class="modal-content form-submit-event" method="POST">
            <input type="hidden" name="id" id="status_id">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_status', 'Update status') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="status_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="status_color" name="color" required>
                            <option class="badge bg-label-primary" value="primary">
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary"><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success"><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger"><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning"><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info"><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark"><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif


@if (Request::is('projects') || Request::is('projects/*') || Request::is('tasks') || Request::is('tasks/*') || Request::is('priority/manage'))
<div class="modal fade" id="create_priority_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/priority/store')}}" method="POST">
            @if (Request::is('priority/manage'))
            <input type="hidden" name="dnr">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_priority', 'Create Priority') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="nameBasic" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="color" name="color">
                            <option class="badge bg-label-primary" value="primary" {{ old('color') == "primary" ? "selected" : "" }}>
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary" {{ old('color') == "secondary" ? "selected" : "" }}><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success" {{ old('color') == "success" ? "selected" : "" }}><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark" {{ old('color') == "dark" ? "selected" : "" }}><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('create', 'Create') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('priority/manage'))
<div class="modal fade" id="edit_priority_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('priority/update')}}" class="modal-content form-submit-event" method="POST">
            <input type="hidden" name="id" id="priority_id">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_priority', 'Update Priority') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="priority_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="priority_color" name="color" required>
                            <option class="badge bg-label-primary" value="primary">
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary"><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success"><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger"><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning"><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info"><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark"><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif


@if (Request::is('projects') || Request::is('projects/*') || Request::is('tags/manage'))
<div class="modal fade" id="create_tag_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/tags/store')}}" method="POST">
            @if (Request::is('tags/manage'))
            <input type="hidden" name="dnr">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_tag', 'Create tag') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="nameBasic" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="color" name="color">
                            <option class="badge bg-label-primary" value="primary" {{ old('color') == "primary" ? "selected" : "" }}>
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary" {{ old('color') == "secondary" ? "selected" : "" }}><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success" {{ old('color') == "success" ? "selected" : "" }}><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark" {{ old('color') == "dark" ? "selected" : "" }}><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('create', 'Create') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('tags/manage'))
<div class="modal fade" id="edit_tag_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="/tags/update" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" name="id" id="tag_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_tag', 'Update tag') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="tag_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="tag_color" name="color">
                            <option class="badge bg-label-primary" value="primary">
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary"><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success"><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger"><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning"><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info"><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark"><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('home') || Request::is('todos'))
<div class="modal fade" id="create_todo_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/todos/store')}}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_todo', 'Create todo') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('priority', 'Priority') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" name="priority">
                            <option value="low" {{ old('priority') == "low" ? "selected" : "" }}><?= get_label('low', 'Low') ?></option>
                            <option value="medium" {{ old('priority') == "medium" ? "selected" : "" }}><?= get_label('medium', 'Medium') ?></option>
                            <option value="high" {{ old('priority') == "high" ? "selected" : "" }}><?= get_label('high', 'High') ?></option>
                        </select>
                    </div>
                </div>
                <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                <textarea class="form-control" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_todo_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('/todos/update')}}" class="modal-content form-submit-event" method="POST">
            <input type="hidden" name="id" id="todo_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_todo', 'Update todo') ?></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="todo_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('priority', 'Priority') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="todo_priority" name="priority">
                            <option value="low"><?= get_label('low', 'Low') ?></option>
                            <option value="medium"><?= get_label('medium', 'Medium') ?></option>
                            <option value="high"><?= get_label('high', 'High') ?></option>
                        </select>
                    </div>
                </div>
                <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                <textarea class="form-control" id="todo_description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></span></button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="modal fade" id="default_language_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('set_primary_lang_alert', 'Are you want to set as your primary language?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirm"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="leaveWorkspaceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= get_label('confirm_leave_workspace', 'Are you sure you want leave this workspace?') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-danger" id="confirm"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_language_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form class="modal-content form-submit-event" action="{{url('/settings/languages/store')}}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_language', 'Create language') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="For Example: English" />
                        @error('name')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('code', 'Code') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="code" placeholder="For Example: en" />
                        @error('code')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_language_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form class="modal-content form-submit-event" action="{{url('/settings/languages/update')}}" method="POST">
            <input type="hidden" name="id" id="language_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_language', 'Update language') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="name" id="language_title" placeholder="For Example: English" />
                        @error('name')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@if (Request::is('leave-requests') || Request::is('leave-requests/*'))
<div class="modal fade" id="create_leave_request_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/leave-requests/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="lr_table">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_leave_requet', 'Create leave request') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    @if (is_admin_or_leave_editor())
                    <label class="form-label" for="user_id"><?= get_label('select_user', 'Select user') ?> <span class="asterisk">*</span></label>
                    <div class="col-12 mb-3">
                        <select class="form-select" name="user_id">
                            <option value=""><?= get_label('select_user', 'Select user') ?></option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}" <?= $user->id == getAuthenticatedUser()->id ? 'selected' : '' ?>>{{$user->first_name.' '.$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-5 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('leave_from_date', 'Leave from date') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="start_date" name="from_date" class="form-control" placeholder="" autocomplete="off">
                    </div>
                    <div class="col-5 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('leave_to_date', 'Leave to date') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="end_date" name="to_date" class="form-control" placeholder="" autocomplete="off">
                    </div>
                    <div class="col-2 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('days', 'Days') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="total_days" class="form-control" value="1" placeholder="" disabled>
                    </div>
                </div>
                <label for="description" class="form-label"><?= get_label('leave_reason', 'Leave reason') ?> <span class="asterisk">*</span></label>
                <textarea class="form-control" name="reason" placeholder="<?= get_label('please_enter_leave_reason', 'Please enter leave reason') ?>"></textarea>
                @if (is_admin_or_leave_editor())
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">

                            <input type="radio" class="btn-check" name="status" id="create_lr_pending" value="pending" checked>
                            <label class="btn btn-outline-primary" for="create_lr_pending"><?= get_label('pending', 'Pending') ?></label>

                            <input type="radio" class="btn-check" name="status" id="create_lr_approved" value="approved">
                            <label class="btn btn-outline-primary" for="create_lr_approved"><?= get_label('approved', 'Approved') ?></label>

                            <input type="radio" class="btn-check" name="status" id="create_lr_rejected" value="rejected">
                            <label class="btn btn-outline-primary" for="create_lr_rejected"><?= get_label('rejected', 'Rejected') ?></label>
                        </div>
                    </div>
                </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="edit_leave_request_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/leave-requests/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="lr_table">
            <input type="hidden" name="id" id="lr_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_leave_request', 'Update leave request') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">

                            <input type="radio" class="btn-check" name="status" id="update_lr_pending" value="pending" checked>
                            <label class="btn btn-outline-primary" for="update_lr_pending"><?= get_label('pending', 'Pending') ?></label>

                            <input type="radio" class="btn-check" name="status" id="update_lr_approved" value="approved">
                            <label class="btn btn-outline-primary" for="update_lr_approved"><?= get_label('approved', 'Approved') ?></label>

                            <input type="radio" class="btn-check" name="status" id="update_lr_rejected" value="rejected">
                            <label class="btn btn-outline-primary" for="update_lr_rejected"><?= get_label('rejected', 'Rejected') ?></label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('contracts'))

<div class="modal fade" id="create_contract_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content form-submit-event" action="{{url('/contracts/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="contracts_table">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_contract', 'Create contract') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-6 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>">
                        </div>

                        <div class="col-6 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('value', 'Value') ?> <span class="asterisk">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                                <input type="text" name="value" class="form-control" placeholder="<?= get_label('please_enter_value', 'Please enter value') ?>">
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="start_date" name="start_date" class="form-control" placeholder="" autocomplete="off">
                        </div>

                        <div class="col-6 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="end_date" name="end_date" class="form-control" placeholder="" autocomplete="off">
                        </div>
                        @if(!isClient())
                        <label class="form-label" for=""><?= get_label('select_client', 'Select client') ?> <span class="asterisk">*</span></label>
                        <div class="col-12 mb-3">
                            <select class="form-select" name="client_id">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <label class="form-label" for=""><?= get_label('select_project', 'Select project') ?> <span class="asterisk">*</span></label>
                        <div class="col-12 mb-3">
                            <select class="form-select" name="project_id">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @foreach ($projects as $project)
                                <option value="{{$project->id}}">{{$project->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="form-label" for=""><?= get_label('select_contract_type', 'Select contract type') ?> <span class="asterisk">*</span></label>
                        <div class="col-12 mb-3">
                            <select class="form-select" name="contract_type_id">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @foreach ($contract_types as $type)
                                <option value="{{$type->id}}">{{$type->type}}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_contract_type_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_contract_type', 'Create contract type') ?>"><i class="bx bx-plus"></i></button></a>
                                <a href="/contracts/contract-types"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_contract_types', 'Manage contract types') ?>"><i class="bx bx-list-ul"></i></button></a>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_contract_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content form-submit-event" action="{{url('/contracts/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="contracts_table">
            <input type="hidden" id="contract_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_contract', 'Update contract') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-6 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>">
                        </div>

                        <div class="col-6 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('value', 'Value') ?> <span class="asterisk">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                                <input type="text" id="value" name="value" class="form-control" placeholder="<?= get_label('please_enter_value', 'Please enter value') ?>">
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="update_start_date" name="start_date" class="form-control" placeholder="" autocomplete="off">
                        </div>

                        <div class="col-6 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="update_end_date" name="end_date" class="form-control" placeholder="" autocomplete="off">
                        </div>

                        <label class="form-label" for=""><?= get_label('select_client', 'Select client') ?> <span class="asterisk">*</span></label>
                        <div class="col-12 mb-3">
                            <select class="form-select" id="client_id" name="client_id">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="form-label" for=""><?= get_label('select_project', 'Select project') ?> <span class="asterisk">*</span></label>
                        <div class="col-12 mb-3">
                            <select class="form-select" id="project_id" name="project_id">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @foreach ($projects as $project)
                                <option value="{{$project->id}}">{{$project->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="form-label" for=""><?= get_label('select_contract_type', 'Select contract type') ?> <span class="asterisk">*</span></label>
                        <div class="col-12 mb-3">
                            <select class="form-select" id="contract_type_id" name="contract_type_id">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @foreach ($contract_types as $type)
                                <option value="{{$type->id}}">{{$type->type}}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_contract_type_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_contract_type', 'Create contract type') ?>"><i class="bx bx-plus"></i></button></a>
                                <a href="/contracts/contract-types"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_contract_types', 'Manage contract types') ?>"><i class="bx bx-list-ul"></i></button></a>
                            </div>
                        </div>
                    </div>
                    <label for="description" class="form-label"><?= get_label('description', 'Description') ?> <span class="asterisk">*</span></label>
                    <textarea class="form-control" name="description" id="update_contract_description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="modal fade" id="create_contract_type_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/contracts/store-contract-type')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_contract_type', 'Create contract type') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="type" placeholder="<?= get_label('please_enter_contract_type', 'Please enter contract type') ?>" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_contract_type_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/contracts/update-contract-type')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="update_contract_type_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_contract_type', 'Update contract type') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="type" id="contract_type" placeholder="<?= get_label('please_enter_contract_type', 'Please enter contract type') ?>" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>

@if (Request::is('payslips/create') || Request::is('payment-methods'))
<div class="modal fade" id="create_pm_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form class="modal-content form-submit-event" action="{{url('/payment-methods/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_payment_method', 'Create payment method') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_pm_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form class="modal-content form-submit-event" action="{{url('/payment-methods/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="pm_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_payment_method', 'Update payment method') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" id="pm_title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif

@if (Request::is('payslips/create') || Request::is('allowances'))
<div class="modal fade" id="create_allowance_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/allowances/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_allowance', 'Create allowance') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_allowance_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/allowances/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" name="id" id="allowance_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_allowance', 'Update allowance') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="allowance_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="allowance_amount" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif



@if (Request::is('payslips/create') || Request::is('deductions'))
<div class="modal fade" id="create_deduction_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/deductions/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_deduction', 'Create deduction') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <select id="deduction_type" name="type" class="form-select">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            <option value="amount"><?= get_label('amount', 'Amount') ?></option>
                            <option value="percentage"><?= get_label('percentage', 'Percentage') ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3 d-none" id="amount_div">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                    <div class="col-md-12 mb-3 d-none" id="percentage_div">
                        <label class="form-label" for=""><?= get_label('percentage', 'Percentage') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" name="percentage" placeholder="<?= get_label('please_enter_percentage', 'Please enter percentage') ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_deduction_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/deductions/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="deduction_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_deduction', 'Update deduction') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="deduction_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <select id="update_deduction_type" name="type" class="form-select">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            <option value="amount"><?= get_label('amount', 'Amount') ?></option>
                            <option value="percentage"><?= get_label('percentage', 'Percentage') ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3" id="update_amount_div">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="deduction_amount" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                    <div class="col-md-12 mb-3" id="update_percentage_div">
                        <label class="form-label" for=""><?= get_label('percentage', 'Percentage') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="deduction_percentage" name="percentage" placeholder="<?= get_label('please_enter_percentage', 'Please enter percentage') ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif



@if (Request::is('estimates-invoices/create') || Request::is('taxes') || Request::is('units') || Request::is('items'))
<div class="modal fade" id="create_tax_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/taxes/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_tax', 'Create tax') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <select id="deduction_type" name="type" class="form-select">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            <option value="amount"><?= get_label('amount', 'Amount') ?></option>
                            <option value="percentage"><?= get_label('percentage', 'Percentage') ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3 d-none" id="amount_div">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                    <div class="col-md-12 mb-3 d-none" id="percentage_div">
                        <label class="form-label" for=""><?= get_label('percentage', 'Percentage') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" name="percentage" placeholder="<?= get_label('please_enter_percentage', 'Please enter percentage') ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_tax_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/taxes/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="tax_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_tax', 'Update tax') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="tax_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <select id="update_tax_type" name="type" class="form-select" disabled>
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            <option value="amount"><?= get_label('amount', 'Amount') ?></option>
                            <option value="percentage"><?= get_label('percentage', 'Percentage') ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3" id="update_amount_div">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="tax_amount" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>" disabled>
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                    <div class="col-md-12 mb-3" id="update_percentage_div">
                        <label class="form-label" for=""><?= get_label('percentage', 'Percentage') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="tax_percentage" name="percentage" placeholder="<?= get_label('please_enter_percentage', 'Please enter percentage') ?>" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>



<div class="modal fade" id="create_unit_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/units/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_unit', 'Create unit') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>


                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_unit_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/units/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="unit_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_unit', 'Update unit') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="unit_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" id="unit_description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>



<div class="modal fade" id="create_item_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/items/store')}}" method="POST">
            @if (Request::is('items'))
            <input type="hidden" name="dnr">
            @else
            <input type="hidden" name="reload">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_item', 'Create item') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="price" placeholder="<?= get_label('please_enter_price', 'Please enter price') ?>" />
                    </div>
                    @if(isset($units) && is_iterable($units))
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('unit', 'Unit') ?></label>
                        <select class="form-select" name="unit_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($units as $unit)
                            <option value="{{$unit->id}}">{{$unit->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif


                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="edit_item_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form class="modal-content form-submit-event" action="{{url('/items/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="item_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_item', 'Update item') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="item_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="item_price" name="price" placeholder="<?= get_label('please_enter_price', 'Please enter price') ?>" />
                    </div>
                    @if(isset($units) && is_iterable($units))
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('unit', 'Unit') ?></label>
                        <select class="form-select" id="item_unit_id" name="unit_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($units as $unit)
                            <option value="{{$unit->id}}">{{$unit->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif


                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" id="item_description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>


@endif


@if (Request::is('notes'))
<div class="modal fade" id="create_note_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/notes/store')}}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_note', 'Create note') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="nameBasic" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" name="color">
                            <option class="badge bg-label-success" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('green', 'Green') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('yellow', 'Yellow') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('red', 'Red') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('create', 'Create') ?></label></button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="edit_note_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/notes/update')}}" method="POST">
            <input type="hidden" name="id" id="note_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_note', 'Update note') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="note_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" id="note_description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="note_color" name="color">
                            <option class="badge bg-label-info" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('green', 'Green') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('yellow', 'Yellow') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('red', 'Red') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></label></button>
            </div>
        </form>
    </div>
</div>



@endif


<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('delete_account_alert', 'Are you sure you want to delete your account?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <form id="formAccountDeactivation" action="/account/destroy/{{getAuthenticatedUser()->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><?= get_label('yes', 'Yes') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> '</button>
            </div>
            <div class="modal-body">
                <p><?= get_label('delete_alert', 'Are you sure you want to delete?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-danger" id="confirmDelete"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteSelectedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> '</button>
            </div>
            <div class="modal-body">
                <p><?= get_label('delete_selected_alert', 'Are you sure you want to delete selected record(s)?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-danger" id="confirmDeleteSelections"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="duplicateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('duplicate_warning', 'Are you sure you want to duplicate?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>

                <button type="submit" class="btn btn-primary" id="confirmDuplicate"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="timerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('time_tracker', 'Time tracker') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="stopwatch">
                        <div class="stopwatch_time">
                            <input type="text" name="hour" id="hour" value="00" class="form-control stopwatch_time_input" readonly>
                            <div class="stopwatch_time_lable"><?= get_label('hours', 'Hours') ?></div>
                        </div>
                        <div class="stopwatch_time">
                            <input type="text" name="minute" id="minute" value="00" class="form-control stopwatch_time_input" readonly>
                            <div class="stopwatch_time_lable"><?= get_label('minutes', 'Minutes') ?></div>
                        </div>
                        <div class="stopwatch_time">
                            <input type="text" name="second" id="second" value="00" class="form-control stopwatch_time_input" readonly>
                            <div class="stopwatch_time_lable"><?= get_label('second', 'Second') ?></div>
                        </div>
                    </div>
                    <div class="selectgroup selectgroup-pills d-flex justify-content-around mt-3">
                        <label class="selectgroup-item">
                            <span class="selectgroup-button selectgroup-button-icon" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('start', 'Start') ?>" id="start" onclick="startTimer()"><i class="bx bx-play"></i></span>
                        </label>
                        <label class="selectgroup-item">
                            <span class="selectgroup-button selectgroup-button-icon" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('stop', 'Stop') ?>" id="end" onclick="stopTimer()"><i class="bx bx-stop"></i></span>
                        </label>
                        <label class="selectgroup-item">
                            <span class="selectgroup-button selectgroup-button-icon" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('pause', 'Pause') ?>" id="pause" onclick="pauseTimer()"><i class="bx bx-pause"></i></span>
                        </label>
                    </div>
                    <div class="form-group mb-0 mt-3">
                        <label class="label"><?= get_label('message', 'Message') ?>:</label>
                        <textarea class="form-control" id="time_tracker_message" placeholder="<?= get_label('please_enter_your_message', 'Please enter your message') ?>" name="message"></textarea>
                    </div>
                </div>
                @if (getAuthenticatedUser()->can('manage_timesheet'))
                <div class="modal-footer justify-content-center">
                    <a href="/time-tracker" class="btn btn-primary"><i class="bx bxs-time"></i> <?= get_label('view_timesheet', 'View timesheet') ?></a>

                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="stopTimerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> '</button>
            </div>
            <div class="modal-body">
                <p><?= get_label('stop_timer_alert', 'Are you sure you want to stop the timer?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-danger" id="confirmStop"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
@if (Request::is('estimates-invoices/create') || preg_match('/^estimates-invoices\/edit\/\d+$/', Request::path()))
<div class="modal fade" id="edit-billing-address" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_billing_details', 'Update billing details') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> '</button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('name', 'Name') ?> <span class="asterisk">*</span></label>
                        <input name="update_name" id="update_name" class="form-control" placeholder="<?= get_label('please_enter_name', 'Please enter name') ?>" value="{{$estimate_invoice->name??''}}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('contact', 'Contact') ?> <span class="asterisk">*</span></label>
                        <input name="update_contact" id="update_contact" class="form-control" placeholder="<?= get_label('please_enter_contact', 'Please enter contact') ?>" value="{{$estimate_invoice->phone??''}}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('address', 'Address') ?> <span class="asterisk">*</span></label>
                        <textarea class="form-control" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" name="update_address" id="update_address">{{$estimate_invoice->address??''}}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('city', 'City') ?> <span class="asterisk">*</span></label>
                        <input name="update_city" id="update_city" class="form-control" placeholder="<?= get_label('please_enter_city', 'Please enter city') ?>" value="{{$estimate_invoice->city??''}}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('state', 'State') ?> <span class="asterisk">*</span></label>
                        <input name="update_contact" id="update_state" class="form-control" placeholder="<?= get_label('please_enter_state', 'Please enter state') ?>" value="{{$estimate_invoice->city??''}}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('country', 'Country') ?> <span class="asterisk">*</span></label>
                        <input name="update_country" id="update_country" class="form-control" placeholder="<?= get_label('please_enter_country', 'Please enter country') ?>" value="{{$estimate_invoice->country??''}}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('zip_code', 'Zip code') ?> <span class="asterisk">*</span></label>
                        <input name="update_zip_code" id="update_zip_code" class="form-control" placeholder="<?= get_label('please_enter_zip_code', 'Please enter zip code') ?>" value="{{$estimate_invoice->zip_code??''}}">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="button" class="btn btn-primary" id="apply_billing_details"><?= get_label('apply', 'Apply') ?></button>
            </div>
        </div>
    </div>
</div>
@endif

@if (Request::is('expenses') || Request::is('expenses/*'))
<div class="modal fade" id="create_expense_type_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/expenses/store-expense-type')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_expense_type', 'Create expense type') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_expense_type_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/expenses/update-expense-type')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="update_expense_type_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_expense_type', 'Update expense type') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" id="expense_type_title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" name="description" id="expense_type_description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@if (Request::is('expenses'))
<div class="modal fade" id="create_expense_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content form-submit-event" action="{{url('/expenses/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_expense', 'Create expense') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('expense_type', 'Expense type') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" name="expense_type_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($expense_types as $type)
                            <option value="{{$type->id}}">{{$type->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('user', 'User') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" name="user_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('expense_date', 'Expense date') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="expense_date" name="expense_date" class="form-control" placeholder="" autocomplete="off">
                    </div>
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" name="note" placeholder="<?= get_label('please_enter_note_if_any', 'Please enter note if any') ?>"></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit_expense_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content form-submit-event" action="{{url('/expenses/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="update_expense_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_expense', 'Update expense') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="expense_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('expense_type', 'Expense type') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="expense_type_id" name="expense_type_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($expense_types as $type)
                            <option value="{{$type->id}}">{{$type->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('user', 'User') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="expense_user_id" name="user_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="expense_amount" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('expense_date', 'Expense date') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_expense_date" name="expense_date" class="form-control" placeholder="" autocomplete="off">
                    </div>
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" id="expense_note" name="note" placeholder="<?= get_label('please_enter_note_if_any', 'Please enter note if any') ?>"></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif
@endif

@if (Request::is('payments'))
<div class="modal fade" id="create_payment_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content form-submit-event" action="{{url('/payments/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_payment', 'Create payment') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('user', 'User') ?></label>
                        <select class="form-select" name="user_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('invoice', 'Invoice') ?></label>
                        <select class="form-select" name="invoice_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($invoices as $invoice)
                            <option value="{{$invoice->id}}">{{get_label('invoice_id_prefix', 'INVC-') . $invoice->id}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">

                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('payment_method', 'Payment method') ?></label>
                        <select class="form-select" name="payment_method_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($payment_methods as $pm)
                            <option value="{{$pm->id}}">{{$pm->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" name="amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('payment_date', 'Payment date') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="payment_date" name="payment_date" class="form-control" placeholder="" autocomplete="off">
                    </div>
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" name="note" placeholder="<?= get_label('please_enter_note_if_any', 'Please enter note if any') ?>"></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_payment_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content form-submit-event" action="{{url('/payments/update')}}" method="POST">
            <input type="hidden" name="dnr">
            <input type="hidden" id="update_payment_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_payment', 'Update payment') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('user', 'User') ?></label>
                        <select class="form-select" name="user_id" id="payment_user_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('invoice', 'Invoice') ?></label>
                        <select class="form-select" name="invoice_id" id="payment_invoice_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($invoices as $invoice)
                            <option value="{{$invoice->id}}">{{get_label('invoice_id_prefix', 'INVC-') . $invoice->id}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">

                    <div class="col mb-3">
                        <label class="form-label"><?= get_label('payment_method', 'Payment method') ?></label>
                        <select class="form-select" name="payment_method_id" id="payment_pm_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($payment_methods as $pm)
                            <option value="{{$pm->id}}">{{$pm->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label" for=""><?= get_label('amount', 'Amount') ?> <span class="asterisk">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" name="amount" id="payment_amount" placeholder="<?= get_label('please_enter_amount', 'Please enter amount') ?>">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('payment_date', 'Payment date') ?> <span class="asterisk">*</span></label>
                        <input type="text" name="payment_date" class="form-control" id="update_payment_date" placeholder="" autocomplete="off">
                    </div>
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" name="note" id="payment_note" placeholder="<?= get_label('please_enter_note_if_any', 'Please enter note if any') ?>"></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="modal fade" id="mark_all_notifications_as_read_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('mark_all_notifications_as_read_alert', 'Are you sure you want to mark all notifications as read?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmMarkAllAsRead"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_notification_status_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('update_notifications_status_alert', 'Are you sure you want to update notification status?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmNotificationStatus"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="restore_default_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('confirm_restore_default_template', 'Are you sure you want to restore default template?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmRestoreDefault"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sms_instuction_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('sms_gateway_configuration', 'Sms Gateway Configuration') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <ul>
                        <li>Read and follow instructions carefully while configuration sms gateway setting </li>

                        <li class="my-4">Firstly open your sms gateway account . You can find api keys in your account -> API keys & credentials -> create api key </li>
                        <li class="my-4">After create key you can see here Account sid and auth token </li>
                        <div class="simplelightbox-gallery">
                            <a href="{{asset('storage/images/base_url_and_params.png')}}" target="_blank">
                                <img src="{{asset('storage/images/base_url_and_params.png')}}" class="w-100">
                            </a>
                        </div>

                        <li class="my-4">For Base url Messaging -> Send an SMS</li>
                        <div class="simplelightbox-gallery">
                            <a href="{{asset('storage/images/api_key_and_token.png')}}" target="_blank">
                                <img src="{{asset('storage/images/api_key_and_token.png')}}" class="w-100">
                            </a>
                        </div>

                        <li class="my-4">check this for admin panel settings</li>
                        <div class="simplelightbox-gallery">
                            <a href="{{asset('storage/images/sms_gateway_1.png')}}" target="_blank">
                                <img src="{{asset('storage/images/sms_gateway_1.png')}}" class="w-100">
                            </a>
                        </div>
                        <div class="simplelightbox-gallery">
                            <a href="{{asset('storage/images/sms_gateway_2.png')}}" target="_blank">
                                <img src="{{asset('storage/images/sms_gateway_2.png')}}" class="w-100">
                            </a>
                        </div>
                        <li class="my-4"><b>Make sure you entered valid data as per instructions before proceed</b></li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="permission_instuction_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('permission_settings_instructions', 'Permission Settings Instructions') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <ul>
                        <li class="mb-2"><b>{{get_label('all_data_access', 'All Data Access')}}:</b> If this option is selected, users or clients assigned to this role will have unrestricted access to all data, without any specific restrictions or limitations.</li>
                        <li class="mb-2"><b>{{get_label('allocated_data_access', 'Allocated Data Access')}}:</b> If this option is selected, users or clients assigned to this role will have restricted access to data based on specific assignments and restrictions.</li>
                        <li class="mb-2"><b>{{get_label('create_permission', 'Create Permission')}}:</b> This determines whether users or clients assigned to this role can create new records. For example, if the create permission is enabled for projects, users or clients in this role will be able to create new projects; otherwise, they won’t have this ability.</li>
                        <li class="mb-2"><b>{{get_label('manage_permission', 'Manage Permission')}}:</b> This determines whether users or clients assigned to this role can access and interact with specific modules. For instance, if the manage permission is enabled for projects, users or clients in this role will be able to view projects however create, edit, or delete depending on the specific permissions granted. If the manage permission is disabled for projects, users or clients in this role won’t be able to view or interact with projects in any way.</li>
                        <li class="mb-2"><b>{{get_label('edit_permission', 'Edit Permission')}}:</b> This determines whether users or clients assigned to this role can edit current records. For example, if the edit permission is enabled for projects, users or clients in this role will be able to edit current projects; otherwise, they won’t have this ability.</li>
                        <li><b>{{get_label('delete_permission', 'Delete Permission')}}:</b> This determines whether users or clients assigned to this role can delete current records. For example, if the delete permission is enabled for projects, users or clients in this role will be able to delete current projects; otherwise, they won’t have this ability.</li>

                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

@if (Request::is('tasks') || Request::is('tasks/draggable') || Request::is('master-schedule') ||  Request::is('projects/information/*') || Request::is('projects/tasks/draggable/*') || Request::is('projects/tasks/list/*'))
<div class="modal fade" id="create_task_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/tasks/store" class="form-submit-event modal-content" method="POST">
            @if (!Request::is('projects/tasks/draggable/*') && !Request::is('tasks/draggable'))
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="task_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_task', 'Create Task') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">

                            <select class="form-select" name="status_id">
                                @foreach($statuses as $status)
                                <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_status_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_statuses', 'Manage statuses') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('status_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <div class="input-group">

                            <select class="form-select" name="priority_id">
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_priority_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priorities', 'Manage Priorities') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('priority_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="task_start_date" name="start_date" class="form-control" value="">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="task_end_date" name="due_date" class="form-control" value="">
                        @error('due_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <?php $project_id = 0;
                    if (!isset($project->id)) {
                    ?>
                        <div class="mb-3">
                            <label class="form-label" for="user_id"><?= get_label('select_project', 'Select project') ?> <span class="asterisk">*</span></label>
                            <div class="input-group">
                                <select id="" class="form-control js-example-basic-multiple" name="project" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                    <option value=""></option>
                                    @foreach($projects as $project)
                                    <option value="{{$project->id}}" {{ old('project')==$project->id ? 'selected':'' }}>{{$project->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('project')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    <?php } else {
                        $project_id = $project->id ?>
                        <input type="hidden" name="project" value="{{$project_id}}">
                        <div class="mb-3">
                            <label for="project_title" class="form-label"><?= get_label('project', 'Project') ?> <span class="asterisk">*</span></label>
                            <input class="form-control" type="text" value="{{ $project->title }}" readonly>
                            @error('title')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    <?php } ?>
                </div>
                <div class="row" id="selectTaskUsers">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?> <span id="users_associated_with_project"></span><?php if (!empty($project_id)) { ?> (<?= get_label('users_associated_with_project', 'Users associated with project') ?> <b>{{$project->title}}</b>)

                            <?php } ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                <?php if (!empty($project_id)) { ?>
                                    @foreach($toSelectTaskUsers as $user)
                                    <?php
                                    $selected = '';
                                    // Check if task_accessibility is 'project_users' or if the user is the authenticated user
                                    if ($project->task_accessibility == 'project_users' || $user->id == getAuthenticatedUser()->id) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?> <span class="asterisk">*</span></label>
                        <textarea class="form-control" rows="5" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>
@endif
<!-- Modal for Creating Activity -->
@if (Request::is('activities') || Request::is('tasks/information/*') || Request::is('master-schedule') | Request::is('tasks/activity/*'))
<div class="modal fade" id="create_activity_modal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createActivityModalLabel">{{ get_label('create_activity', 'Create Activity') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createActivityForm" method="POST" action="{{ route('activities.store') }}">
                    @csrf

                    <input type="hidden" id="task_id" name="task_id" value="">

                    <div class="mb-3">
                        <label for="activityName" class="form-label">{{ get_label('activity_name', 'Activity Name') }}</label>
                        <input type="text" class="form-control" id="activityName" name="name" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="activityPriority" class="form-label">{{ get_label('activity_priority', 'Activity Priority') }}</label>
                            <select class="form-select" id="activityPriority" name="priority" required>
                                <option value="" disabled selected>{{ get_label('select_priority', 'Select Priority') }}</option>
                                @foreach($priorities as $pri)
                                    <option value="{{ $pri->id }}">{{ $pri->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">{{ get_label('status', 'Status') }} <span class="asterisk">*</span></label>
                            <select class="form-select" name="status_id" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="unit" class="form-label">{{ get_label('unit', 'Unit') }} <span class="asterisk">*</span></label>
                            <select class="form-select" id="unit" name="unit_id" required>
                                <option value="" disabled selected>{{ get_label('select_unit', 'Select Unit') }}</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">{{ get_label('quantity', 'Quantity') }} <span class="asterisk">*</span></label>
                            <input type="number" id="quantity" name="quantity" class="form-control" required min="1">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                            <input type="text" id="start_date" name="start_date" class="form-control" required>
                            @error('start_date')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                            <input type="text" id="end_date" name="end_date" class="form-control" value="">
                            @error('due_date')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ get_label('submit', 'Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@if (Request::is('tasks') || Request::is('tasks/draggable') || Request::is('projects/tasks/draggable/*') || Request::is('projects/tasks/list/*') || Request::is('tasks/information/*'))
<div class="modal fade" id="edit_task_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/tasks/update" class="form-submit-event modal-content" method="POST">
            <input type="hidden" name="id" id="id">
            @if (!Request::is('projects/tasks/draggable/*') && !Request::is('tasks/draggable') && !Request::is('tasks/information/*'))
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="task_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_task', 'Update Task') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">

                            <select class="form-select" id="status_id" name="status_id">
                                @foreach($statuses as $status)
                                <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_status_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_statuses', 'Manage statuses') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('status_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <div class="input-group">

                            <select class="form-select" name="priority_id" id="priority_id">
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_priority_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priorities', 'Manage Priorities') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('priority_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_start_date" name="start_date" class="form-control" value="">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_end_date" name="due_date" class="form-control" value="">
                        @error('due_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                </div>

                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="project_title" class="form-label"><?= get_label('project', 'Project') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="project_title" readonly>
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?> <span id="task_update_users_associated_with_project"></span></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="row">

<div class="mb-3">
    <label for="description" class="form-label"><?= get_label('issue', 'Issue') ?> </label>
    <textarea class="form-control" id="task_issue" rows="5" name="issue" placeholder="<?= get_label('please_enter_issue', 'Please enter issue') ?>">{{ old('issue') }}</textarea>
    @error('issue')
    <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

</div>
                <div class="mb-3">
    <label for="progress" class="form-label"><?= get_label('Progress', 'Progress') ?> <span class="asterisk">*</span></label>
    <div class="d-flex align-items-center">
        <input type="range" class="form-range me-2" id="task_Progress" name="progress" min="1" max="100" value="{{ old('progress') }}">
        <output for="task_Progress" id="progressValue">{{ old('progress') }}%</output>
    </div>
    <div class="progress mt-2">
        <div class="progress-bar" id="progressBar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    @error('progress')
    <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

</div>
                <div class="row">

                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?> <span class="asterisk">*</span></label>
                        <textarea class="form-control" id="task_description" rows="5" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="modal fade" id="confirmUpdateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-5"><?= get_label('confirm_update_status', 'Do You Want to Update the Status?') ?></p>
                <textarea class="form-control" id="statusNote" placeholder="Optional note"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="declineUpdateStatus" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmUpdateStatus"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmUpdatePriorityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('confirm_update_priority', 'Do You Want to Update the Priority?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="declineUpdatePriority" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmUpdatePriority"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>


@if (Request::is('projects') || Request::is('master-schedule') || Request::is('projects/list'))
<div class="modal fade" id="create_project_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/projects/store" class="form-submit-event modal-content" method="POST">
            @if (!Request::is('projects'))
                <input type="hidden" name="dnr">
                <input type="hidden" name="table" value="projects_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_project', 'Create Project') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" name="status_id">
                            @foreach($statuses as $status)
                                <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                            @endforeach
                        </select>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_status_modal">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= get_label('create_status', 'Create status') ?>">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </a>
                            <a href="/status/manage" target="_blank">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= get_label('manage_statuses', 'Manage statuses') ?>">
                                    <i class="bx bx-list-ul"></i>
                                </button>
                            </a>
                        </div>
                        @error('status_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="priority"><?= get_label('priority', 'Priority') ?></label>
                        <select class="form-select" name="priority_id">
                            @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$priority->color}})</option>
                            @endforeach
                        </select>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_priority_modal">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= get_label('create_priority', 'Create Priority') ?>">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </a>
                            <a href="/priority/manage" target="_blank">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= get_label('manage_priorities', 'Manage Priorities') ?>">
                                    <i class="bx bx-list-ul"></i>
                                </button>
                            </a>
                        </div>
                        @error('priority_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="budget" class="form-label"><?= get_label('plannedbudget', 'Planned Budget') ?></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="budget" name="budget" placeholder="<?= get_label('please_enter_budget', 'Please enter budget') ?>" value="{{ old('budget') }}">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="start_date" name="start_date" class="form-control" value="">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="end_date" name="end_date" class="form-control" value="">
                        @error('due_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="task_accessibility"><?= get_label('task_accessibility', 'Task Accessibility') ?>
                            <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-original-title="<b>{{get_label('assigned_users','Assigned Users')}}:</b> {{get_label('assigned_users_info','You Will Need to Manually Select Task Users When Creating Tasks Under This Project.')}} <br><b>{{get_label('project_users','Project Users')}}:</b> {{get_label('project_users_info','When Creating Tasks Under This Project, the Task Users Selection Will Be Automatically Filled With Project Users.')}}"></i>
                        </label>
                        <div class="input-group">
                            <select class="form-select" name="task_accessibility">
                                <option value="assigned_users"><?= get_label('assigned_users', 'Assigned Users') ?></option>
                                <option value="project_users"><?= get_label('project_users', 'Project Users') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
        <label class="form-label" for="site_id"><?= get_label('site', 'Site') ?>
            <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-original-title="<b>{{get_label('site_info','Site Info')}}:</b> {{get_label('site_info_description','Select the site for this project.')}}"></i>
        </label>
        <div class="input-group">
            <select class="form-select" name="site_id" id="site_id">
                @foreach($sites as $site)
                    <option value="{{$site->id}}" {{ old('site_id') == $site->id ? "selected" : "" }}>{{$site->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_members', 'Select members') ?></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach($toSelectProjectUsers as $user)
                                    <?php $selected = $user->id == getAuthenticatedUser()->id ? "selected" : "" ?>
                                    <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="client_id"><?= get_label('select_clients', 'Select clients') ?></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="client_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach ($toSelectProjectClients as $client)
                                    <?php $selected = $client->id == getAuthenticatedUser()->id && $auth_user->hasRole('client') ? "selected" : "" ?>
                                    <option value="{{$client->id}}" {{ (collect(old('client_id'))->contains($client->id)) ? 'selected':'' }} <?= $selected ?>>{{$client->first_name}} {{$client->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for=""><?= get_label('select_tags', 'Select tags') ?></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="tag_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}" {{ (collect(old('tag_ids'))->contains($tag->id)) ? 'selected':'' }}>{{$tag->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_tag_modal">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= get_label('create_tag', 'Create tag') ?>">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </a>
                            <a href="/tags/manage">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= get_label('manage_tags', 'Manage tags') ?>">
                                    <i class="bx bx-list-ul"></i>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?> <span class="asterisk">*</span></label>
                        <textarea class="form-control" rows="5" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="alert alert-primary" role="alert">
                    <?= get_label('you_will_be_project_participant_automatically', 'You will be project participant automatically.') ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('projects') || Request::is('projects/list') || Request::is('projects/information/*'))
<div class="modal fade" id="edit_project_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/projects/update" class="form-submit-event modal-content" method="POST">
            <input type="hidden" name="id" id="project_id">
            @if (!Request::is('projects') && !Request::is('projects/information/*'))
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="projects_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_project', 'Update Project') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" id="project_title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">

                            <select class="form-select" name="status_id" id="project_status_id">
                                @foreach($statuses as $status)
                                <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_status_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_statuses', 'Manage statuses') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('status_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <div class="input-group">

                            <select class="form-select" name="priority_id" id="project_priority_id">
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_priority_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priorities', 'Manage Priorities') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('priority_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="budget" class="form-label"><?= get_label('budget', 'Budget') ?></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="project_budget" name="budget" placeholder="<?= get_label('please_enter_budget', 'Please enter budget') ?>" value="{{ old('budget') }}">
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>

                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_start_date" name="start_date" class="form-control" value="">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_end_date" name="end_date" class="form-control" value="">
                        @error('due_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="">
                            <?= get_label('task_accessibility', 'Task Accessibility') ?>
                            <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<b>{{get_label('assigned_users', 'Assigned Users')}}:</b> {{get_label('assigned_users_info','You Will Need to Manually Select Task Users When Creating Tasks Under This Project.')}}<br><b>{{get_label('project_users', 'Project Users')}}:</b> {{get_label('project_users_info','When Creating Tasks Under This Project, the Task Users Selection Will Be Automatically Filled With Project Users.')}}" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                        </label>
                        <div class="input-group">
                            <select class="form-select" name="task_accessibility" id="task_accessibility">
                                <option value="assigned_users"><?= get_label('assigned_users', 'Assigned Users') ?></option>
                                <option value="project_users"><?= get_label('project_users', 'Project Users') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach($toSelectProjectUsers as $user)
                                <?php $selected = $user->id == getAuthenticatedUser()->id ? "selected" : "" ?>
                                <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="client_id"><?= get_label('select_clients', 'Select clients') ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="client_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach ($toSelectProjectClients as $client)
                                <?php $selected = $client->id == getAuthenticatedUser()->id && $auth_user->hasRole('client') ? "selected" : "" ?>
                                <option value="{{$client->id}}" {{ (collect(old('client_id'))->contains($client->id)) ? 'selected':'' }} <?= $selected ?>>{{$client->first_name}} {{$client->last_name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for=""><?= get_label('select_tags', 'Select tags') ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="tag_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach($tags as $tag)
                                <option value="{{$tag->id}}" {{ (collect(old('tag_ids'))->contains($tag->id)) ? 'selected':'' }}>{{$tag->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_tag_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_tag', 'Create tag') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/tags/manage"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_tags', 'Manage tags') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?> <span class="asterisk">*</span></label>
                        <textarea class="form-control" rows="5" name="description" id="project_description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>

@endif


<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><span id="typePlaceholder"></span> <?= get_label('quick_view', 'Quick View') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 id="quickViewTitlePlaceholder" class="text-muted"></h5>
                <div class="nav-align-top my-4">
                    <ul class="nav nav-tabs" role="tablist">
                        @if ($auth_user->can('manage_users'))
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-quick-view-users" aria-controls="navs-top-quick-view-users">
                                <i class="menu-icon tf-icons bx bx-group text-primary"></i><?= get_label('users', 'Users') ?>
                            </button>
                        </li>
                        @endif
                        @if ($auth_user->can('manage_clients'))
                        <li class="nav-item">
                            <button type="button" class="nav-link {{!$auth_user->can('manage_users')?'active':''}}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-quick-view-clients" aria-controls="navs-top-quick-view-clients">
                                <i class="menu-icon tf-icons bx bx-group text-warning"></i><?= get_label('clients', 'Clients') ?>
                            </button>
                        </li>
                        @endif
                        <li class="nav-item">
                            <button type="button" class="nav-link {{!$auth_user->can('manage_users') && !$auth_user->can('manage_clients')?'active':''}}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-quick-view-description" aria-controls="navs-top-quick-view-description">
                                <i class="menu-icon tf-icons bx bx-notepad text-success"></i><?= get_label('checkedBy', 'Checked By') ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link {{!$auth_user->can('manage_users') && !$auth_user->can('manage_clients')?'active':''}}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-quick-view-description" aria-controls="navs-top-quick-view-description">
                                <i class="menu-icon tf-icons bx bx-notepad text-success"></i><?= get_label('description', 'Description') ?>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane fade active show" id="navs-top-quick-view-users" role="tabpanel">
                            <div class="table-responsive text-nowrap">
                                <!-- <input type="hidden" id="data_type" value="users">
                                <input type="hidden" id="data_table" value="usersTable"> -->
                                <table id="usersTable" data-toggle="table" data-loading-template="loadingTemplate" data-url="/users/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsUsersClients">
                                    <thead>
                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                                            <th data-formatter="userFormatter" data-sortable="true" data-field="first_name"><?= get_label('users', 'Users') ?></th>
                                            <th data-field="role"><?= get_label('role', 'Role') ?></th>
                                            <th data-field="phone" data-sortable="true" data-visible="false"><?= get_label('phone_number', 'Phone number') ?></th>
                                            <th data-formatter="assignedFormatter"><?= get_label('assigned', 'Assigned') ?></th>
                                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                                            {{-- <th data-formatter="actionFormatterUsers"><?= get_label('actions', 'Actions') ?></th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade {{!$auth_user->can('manage_users')?'active show':''}}" id="navs-top-quick-view-clients" role="tabpanel">
                            <div class="table-responsive text-nowrap">
                                <!-- <input type="hidden" id="data_type" value="clients">
                            <input type="hidden" id="data_table" value="clientsTable"> -->
                                <table id="clientsTable" data-toggle="table" data-loading-template="loadingTemplate" data-url="/clients/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsUsersClients">
                                    <thead>

                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                                            <th data-formatter="clientFormatter" data-sortable="true"><?= get_label('client', 'Client') ?></th>
                                            <th data-field="company" data-sortable="true" data-visible="false"><?= get_label('company', 'Company') ?></th>
                                            <th data-field="phone" data-sortable="true" data-visible="false"><?= get_label('phone_number', 'Phone number') ?></th>
                                            <th data-formatter="assignedFormatter"><?= get_label('assigned', 'Assigned') ?></th>
                                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                                            {{--<th data-formatter="actionFormatterClients"><?= get_label('actions', 'Actions') ?></th> --}}
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade {{!$auth_user->can('manage_users') && !$auth_user->can('manage_clients')?'active show':''}}" id="navs-top-quick-view-description" role="tabpanel">
                            <p class="pt-3" id="quickViewDescPlaceholder"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<style>
input[type="range"] {
    -webkit-appearance: none;
    width: 100%;
    height: 10px;
    background-color: #ddd;
    outline: none;
    border-radius: 5px;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    background-color: gray; /* Default color */
}

input[type="range"]::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    background-color: gray; /* Default color */
}

output {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

input[type="range"]::-webkit-slider-runnable-track {
    height: 10px;
    border-radius: 5px;
    background: linear-gradient(to right, gray 0%, gray 100%); /* Default color */
}

input[type="range"]::-moz-range-track {
    height: 10px;
    border-radius: 5px;
    background: linear-gradient(to right, gray 0%, gray 100%); /* Default color */
}
</style>
<script>
    var progressSlider = document.getElementById('task_Progress');
    var progressValue = document.getElementById('progressValue');
    var progressBar = document.getElementById('progressBar');

    progressSlider.addEventListener('input', function() {
        progressValue.textContent = progressSlider.value + '%';
        updateProgressBar(progressSlider.value);
    });

    function updateProgressBar(value) {
        progressBar.style.width = value + '%';
        progressBar.style.backgroundColor = getColor(value);
    }

    function getColor(value) {
        if (value <= 25) {
            return 'red';
        } else if (value <= 50) {
            return 'orange';
        } else if (value <= 75) {
            return 'yellow';
        } else {
            return 'green';
        }
    }
</script>