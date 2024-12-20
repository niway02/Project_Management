@extends('layout')

@section('title')
<?= get_label('Labors', 'Labors') ?>
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
                        <?= get_label('labor_spossition', 'Labor Possition') ?>
                    </li>

                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_leave_request_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_leave_request', 'Create leave request') ?>"><i class="bx bx-plus"></i></button></a>
        </div>
    </div>
 
    <div class="row">
        <div class="d-flex justify-content-center">
            <form action="{{url('/leave-requests/update-editors')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="redirect_url" value="/leave-requests">
                <input type="hidden" name="dnr">
            
            </form>
     
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                @if (is_admin_or_leave_editor())
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="lr_user_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_member', 'Select member') ?></option>
                        @foreach ($labors as $user)
                        <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-4">
                    <select class="form-select" id="lr_status_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_status', 'Select status') ?></option>
                        <option value="active"><?= get_label('active', 'active') ?></option>
                        <option value="rejected"><?= get_label('rejected', 'Rejected') ?></option>
                    </select>
                </div>
            </div>

         
            <div class="table-responsive text-nowrap">
                @if (is_admin_or_leave_editor())
                <input type="hidden" id="data_type" value="leave-requests">
                <input type="hidden" id="data_table" value="lr_table">
                @endif
                <table id="lr_table" data-toggle="table" data-loading-template="loadingTemplate"  data-url="/labor-possition/list"  data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsLr">
                    <thead>
                        <tr>
                        <th data-checkbox="true"></th>
                            <th data-sortable="true" data-field="id">{{ get_label('id', 'ID') }}</th>
                            <th data-sortable="true" data-field="labor_type_name">{{ get_label('labor_type_name', 'Type Name') }}</th>
                            <th data-sortable="true" data-field="status">{{ get_label('status', 'status') }}</th>
                            @if (is_admin_or_leave_editor())
                            <th data-formatter="actionsFormatter">{{ get_label('actions', 'Actions') }}</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
  
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{asset('assets/js/pages/leave-requests.js')}}"></script>
@endsection