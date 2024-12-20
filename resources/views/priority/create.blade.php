@extends('layout')

@section('title')
<?= get_label('create_status', 'Create status') ?>
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
                        <?= get_label('status', 'Status') ?>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('create', 'Create') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <form action="{{url('/status/store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="color"><?= get_label('color', 'Color') ?></label>
                        <div class="input-group">
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
                        @error('color')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2" id="showToastPlacement"><?= get_label('create', 'Create') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection