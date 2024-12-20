@extends('layout')

@section('title')
    {{ get_label('departments', 'Departments') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/departments') }}">{{ get_label('departments', 'Departments') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ get_label('list', 'List') }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_department_modal">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('create_department', 'Create Department') }}">
                        <i class='bx bx-plus'></i> {{ get_label('create_department', 'Create Department') }}
                    </button>
                </a>
            </div>
            <input type="hidden" id="type">
            <input type="hidden" id="typeId">
        </div>

        <div class="modal fade" id="create_department_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="/department/store" class="form-submit-event modal-content" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_department', 'Create Department') ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label for="name" class="form-label"><?= get_label('name', 'Name') ?> <span class="asterisk">*</span></label>
                                <input class="form-control" type="text" name="name" placeholder="<?= get_label('please_enter_name', 'Please enter name') ?>" value="{{ old('name') }}">
                                @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                                <select class="form-select" name="status_id">
                                    @foreach($statuses as $status)
                                        <option value="{{$status->id}}" {{ old('status_id') == $status->id ? "selected" : "" }}>{{$status->title}}</option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
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
                        <button type="submit" id="submit_btn" class="btn btn-primary">
                            <?= get_label('submit', 'Submit') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <x-departments-card :departments="$departments" />
    </div>
@endsection
