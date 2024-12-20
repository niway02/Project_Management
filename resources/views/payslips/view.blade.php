@extends('layout') <!-- Assuming you have a layout file -->

@section('title')
<?= get_label('payslip', 'Payslip') ?>
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
                        <a href="/payslips"><?= get_label('payslips', 'Payslips') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('view', 'View') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Display Payslip Information -->
    <div class="card">
        <div class="card-body">
            <div id='section-to-print'>
                <div class="row mb-4">
                    <div class="col-md-2 text-start">
                        <p><?= get_label('payslip_id_prefix', 'PSL-') . $payslip->id ?></p>
                    </div>
                    <div class="col-md-5 text-end">
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

                <!-- Display Payslip Details -->
                <div class="row mt-4">
                    <h5 class="card-title text-center mb-4"><?= get_label('payslip_for', 'Payslip for') ?> {{ $payslip->user_name }} <span class="text-muted">({{ $payslip->user_email }})</span></h5>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong><?= get_label('payslip_month', 'Payslip month') ?>:</strong> {{ $payslip->month->format('F, Y') }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('basic_salary', 'Basic salary') ?>:</strong> {{ format_currency($payslip->basic_salary) }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('working_days', 'Working days') ?>:</strong> {{ $payslip->working_days }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('lop_days', 'Loss of pay days') ?>:</strong> {{ $payslip->lop_days }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('paid_days', 'Paid days') ?>:</strong> {{ $payslip->paid_days }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('leave_deduction', 'Leave deduction') ?>:</strong> {{ format_currency($payslip->leave_deduction) }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('bonus', 'Bonus') ?>:</strong> {{ format_currency($payslip->bonus) }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong><?= get_label('incentives', 'Incentives') ?>:</strong> {{ format_currency($payslip->incentives) }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('over_time_hours', 'Over time hours') ?>:</strong> {{ $payslip->ot_hours }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('over_time_rate', 'Over time rate') ?>:</strong>  {{ format_currency($payslip->ot_rate) }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('over_time_payment', 'Over time payment') ?>:</strong> {{ format_currency($payslip->ot_payment) }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('payment_method', 'Payment method') ?>:</strong> {{ $payslip->payment_method }}
                            </li>

                            <li class="list-group-item">
                                <strong><?= get_label('payment_date', 'Payment date') ?>:</strong> {{ $payslip->payment_date }}
                            </li>
                            <li class="list-group-item">
                                <strong><?= get_label('payment_status', 'Payment status') ?>:</strong> <?= $payslip->status ?>
                            </li>

                        </ul>
                    </div>

                </div>


                <!-- Display Allowances -->
                <div class="row mt-4 mx-1">
                    <div class="col-md-12">
                        <h5><?= get_label('allowances', 'Allowances') ?></h5>
                        @if(count($payslip->allowances) > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?= get_label('allowance', 'Allowance') ?></th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payslip->allowances as $allowance)
                                <tr>
                                    <td>{{ $allowance->title }}</td>
                                    <td> {{ format_currency($allowance->amount) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p><?= get_label('no_allowances_found_payslip', 'No allowances found for this payslip.') ?></p>
                        @endif


                        <!-- Display Deductions -->
                        <h5 class="mt-4 mx-1"><?= get_label('deductions', 'Deductions') ?></h5>
                        @if(count($payslip->deductions) > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?= get_label('deduction', 'Deduction') ?></th>
                                    <th><?= get_label('type', 'Type') ?></th>
                                    <th><?= get_label('amount', 'Amount') ?></th>
                                    <th><?= get_label('percentage', 'Percentage') ?> (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payslip->deductions as $deduction)
                                <tr>
                                    <td>{{ $deduction->title }}</td>
                                    <td>{{ $deduction->type == 'amount' ? get_label('amount', 'Amount') : get_label('percentage', 'Percentage') }}</td>
                                    <td> {{ format_currency($deduction->amount) }}</td>
                                    <td>{{ $deduction->type == 'percentage' ? $deduction->percentage : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p><?= get_label('no_deductions_found_payslip', 'No deductions found for this payslip.') ?></p>
                        @endif

                        <h5 class="mt-4 mx-1"><?= get_label('total_allowances_and_deductions', 'Total allowances and deductions') ?></h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row"><?= get_label('total_allowances', 'Total allowances') ?></th>
                                    <td>{{ format_currency($payslip->total_allowance) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= get_label('total_deductions', 'Total deductions') ?></th>
                                    <td>{{ format_currency($payslip->total_deductions) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Net Payable -->
                        <div class="text-end mt-4">
                            <h5 class="d-none"><?= get_label('total_earnings', 'Total earnings') ?> : {{ format_currency($payslip->total_earnings) }}</h5>
                            <h5><?= get_label('net_payable', 'Net payable') ?> : {{ format_currency($payslip->net_pay) }}</h5>
                        </div>
                        <!-- Note -->
                        @if ($payslip->note)
                        <h5><?= get_label('note', 'Note') ?></h5>
                        <p>{{ $payslip->note ?: '-' }}</p>
                        @endif
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-6 text-start">
                        <span class="text-muted">
                            <strong><?= get_label('created_at', 'Created at') ?>:</strong>
                            {{ format_date($payslip->created_at,true) }}
                        </span>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="text-muted">
                            <strong><?= get_label('last_updated_at', 'Last updated at') ?>:</strong>
                            {{ format_date($payslip->updated_at,true) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center mt-4" id="section-not-to-print">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('print_payslip', 'Print payslip') ?>" onclick="window.print()"><i class='bx bx-printer'></i></button>
    </div>
</div>
@endsection