@extends('layout')

@section('title')
<?= get_label('contract', 'Contract') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between m-4" id="section-not-to-print">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="/contracts"><?= get_label('contracts', 'Contracts') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= $contract->title ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div id='section-to-print'>
                <div class="row">
                    <div class="col-md-7 text-end">
                        <img src="{{asset($general_settings['full_logo'])}}" alt="" width="200px" />
                    </div>
                    <div class="col-md-5 text-end">
                        <p>
                            <?php
                            $timezone = config('app.timezone');
                            $currentTime = now()->tz($timezone);
                            echo '<span class="text-muted">' . $currentTime->format($php_date_format . ' H:i:s') . '</span>';
                            ?>
                        </p>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-8 text-start">
                        <p>
                            <strong><?= get_label('title', 'Title') ?>:</strong>
                            {{$contract->title}}
                        </p>
                        <p>
                            <strong><?= get_label('project', 'Project') ?>:</strong>
                            <a href="/projects/information/{{$contract->project_id}}" target="_blank">{{$contract->project_title}}</a>
                        </p>
                        <p>
                            <strong><?= get_label('client', 'Client') ?>:</strong>
                            <a href="/clients/profile/{{$contract->client_id}}" target="_blank">{{$contract->client_name}}</a>
                        </p>
                        <p>
                            <strong><?= get_label('value', 'Value') ?>:</strong>
                            {{format_currency($contract->value)}}
                        </p>
                        <p>
                            <strong><?= get_label('type', 'Type') ?>:</strong>
                            {{$contract->contract_type}}
                        </p>
                    </div>
                    <?php
                    if (!is_null($contract->promisor_sign) && !is_null($contract->promisee_sign)) {
                        $statusBadge = '<span class="badge bg-success">' . get_label('signed', 'Signed') . '</span>';
                    } elseif (!is_null($contract->promisor_sign) || !is_null($contract->promisee_sign)) {
                        $statusBadge = '<span class="badge bg-warning">' . get_label('partially_signed', 'Partially signed') . '</span>';
                    } else {
                        $statusBadge = '<span class="badge bg-danger">' . get_label('not_signed', 'Not signed') . '</span>';
                    }
                    ?>
                    <div class="col-md-3 text-start">
                        <p>
                            <strong><?= get_label('id', 'ID') ?>:</strong>
                            {{get_label('contract_id_prefix', 'CTR-') . $contract->id}}
                        </p>
                        <p>
                            <strong><?= get_label('starts_at', 'Starts at') ?>:</strong>
                            {{format_date($contract->start_date)}}
                        </p>
                        <p>
                            <strong><?= get_label('ends_at', 'Ends at') ?>:</strong>
                            {{format_date($contract->end_date)}}
                        </p>
                        <p>
                            <strong><?= get_label('created_by', 'Created by') ?>:</strong>
                            {{$contract->creator}}
                        </p>
                        <p>
                            <strong><?= get_label('status', 'Status') ?>:</strong>
                            <?= $statusBadge ?>
                        </p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-11 mt-4">
                        <p><strong><?= get_label('description', 'Description') ?>:</strong><?= $contract->description ?></p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3 text-right mt-4 mb-4">
                        <h5>
                            <?= get_label('promiser_sign', 'Promisor sign') ?>
                        </h5>
                        @if (!is_null($contract->promisor_sign))
                        <p><img src="{{asset('storage/signatures/'.$contract->promisor_sign)}}" width="150px" alt="" /></p>
                        @if ((getAuthenticatedUser()->id == $contract->created_by || isAdminOrHasAllDataAccess()) && !getAuthenticatedUser()->hasRole('client'))
                        <div id="section-not-to-print">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_contract_sign_modal"><button type="button" data-id="{{$contract->id}}" class="btn btn-sm btn-danger mx-3 delete_contract_sign" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('delete_signature', 'Delete signature') ?>"><i class="bx bx-trash"></i></button></a>
                        </div>
                        @endif
                        @else
                        @if ((getAuthenticatedUser()->id == $contract->created_by || isAdminOrHasAllDataAccess()) && !getAuthenticatedUser()->hasRole('client'))
                        <p><?= get_label('not_signed', 'Not signed') ?></p>
                        <div id="section-not-to-print">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_contract_sign_modal"><button type="button" class="btn btn-sm btn-primary mx-4" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_signature', 'Create signature') ?>"><i class="bx bx-plus"></i></button></a>
                        </div>
                        @else
                        <p><?= get_label('not_signed', 'Not signed') ?></p>
                        @endif
                        @endif
                    </div>
                    <div class="col-md-5"></div>

                    <div class="col-md-3 text-right mt-4 mb-4">
                        <h5>
                            <?= get_label('promisee_sign', 'Promisee sign') ?>
                        </h5>
                        @if (!is_null($contract->promisee_sign))
                        <p><img src="{{ asset('storage/signatures/' . $contract->promisee_sign) }}" width="150px" alt="" /></p>
                        @if (getAuthenticatedUser()->id == $contract->client_id && getAuthenticatedUser()->hasRole('client'))
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_contract_sign_modal"><button type="button" data-id="{{ $contract->id }}" class="btn btn-sm btn-danger mx-3 delete_contract_sign" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('delete_signature', 'Delete signature') ?>"><i class="bx bx-trash"></i></button></a>
                        @endif
                        @else
                        @if (getAuthenticatedUser()->id == $contract->client_id)
                        <p><?= get_label('not_signed', 'Not signed') ?></p>
                        <div id="section-not-to-print">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_contract_sign_modal"><button type="button" class="btn btn-sm btn-primary mx-4" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('create_signature', 'Create signature') ?>"><i class="bx bx-plus"></i></button></a>
                        </div>
                        @else
                        <p><?= get_label('not_signed', 'Not signed') ?></p>
                        @endif
                        @endif
                    </div>

                    <div class="col-md-6 text-start mt-4">
                        <span class="text-muted"><?= get_label('created_at', 'Created at') ?> : <?= format_date($contract->created_at, true) ?></span>
                    </div>
                    <div class="col-md-6 text-end mt-4">
                        <span class="text-muted"><?= get_label('last_updated_at', 'Last updated at') ?> : <?= format_date($contract->updated_at, true) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center mt-4" id="section-not-to-print">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('print_contract', 'Print contract') ?>" onclick="window.print()"><i class='bx bx-printer'></i></button>
    </div>
    <div class="modal fade" id="create_contract_sign_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form class="modal-content" id="contract_sign_form" action="{{url('/contracts/create-sign')}}" method="POST">
                <input name="id" type="hidden" id="contract_id" value="{{$contract->id}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('create_signature', 'Create signature') ?></h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mb-3 text-center">
                            <canvas id="promisor_sign" height="181" style="touch-action: none; user-select: none; border:1px solid #6c757d !important;" width="500"></canvas>
                            <button type="button" id="reset_promisor_sign" class="btn btn-danger mt-2">
                                <?= get_label('reset', 'Reset') ?>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <?= get_label('close', 'Close') ?>
                        </button>
                        <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="delete_contract_sign_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> '</button>
                </div>
                <div class="modal-body">
                    <p><?= get_label('delete_alert', 'Are you sure you want to delete signature?') ?></p>
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
</div>
<script>
    var label_please_wait = '<?= get_label('please_wait', 'Please wait...') ?>';
</script>
<script src="{{asset('assets/js/signature-pad.umd.min.js')}}"></script>
<script src="{{asset('assets/js/pages/contracts.js')}}"></script>
@endsection