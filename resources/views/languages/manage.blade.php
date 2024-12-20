@extends('layout')

@section('title')
<?= get_label('manage_languages', 'Manage languages') ?>
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
                    <li class="breadcrumb-item">
                        <a href="{{url('/settings/languages')}}"><?= get_label('languages', 'Languages') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('manage', 'Manage') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <span data-bs-toggle="modal" data-bs-target="#create_language_modal"><a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_language', 'Create language') ?>"><i class='bx bx-plus'></i></a></span>
        </div>
    </div>
    @if (is_countable($languages) && count($languages) > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="settings/languages">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/settings/languages/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                            <th data-sortable="true" data-field="name"><?= get_label('title', 'Title') ?></th>
                            <th data-sortable="true" data-field="code"><?= get_label('code', 'Code') ?></th>
                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                            <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @else
    <?php
    $type = 'Languages'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script src="{{asset('assets/js/pages/languages.js')}}"></script>
@endsection