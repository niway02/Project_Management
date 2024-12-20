@extends('layout')

@section('title')
    <?= get_label('update_payslip', 'Update payslip') ?>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-2 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/home') }}"><?= get_label('home', 'Home') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/payslips') }}"><?= get_label('payslips', 'Payslips') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('update', 'Update') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ url('/payslips/create') }}"><button type="button" class="btn btn-sm btn-primary"
                        data-bs-toggle="tooltip" data-bs-placement="left"
                        data-bs-original-title=" <?= get_label('create_payslip', 'Create payslip') ?>"><i
                            class="bx bx-plus"></i></button></a>
                <a href="{{ url('/payslips') }}"><button type="button" class="btn btn-sm btn-primary"
                        data-bs-toggle="tooltip" data-bs-placement="left"
                        data-bs-original-title=" <?= get_label('payslips', 'Payslips') ?>"><i
                            class="bx bx-list-ul"></i></button></a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url('/payslips/update') }}" class="form-submit-event" method="POST">
                    <input type="hidden" name="redirect_url" value="/payslips">
                    <input type="hidden" name="id" id="payslip_id" value="{{ $payslip->id }}">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="user_id"><?= get_label('select_user', 'Select user') ?> <span
                                    class="asterisk">*</span></label>
                            <div class="input-group">
                                <select id="user_id" class="form-select" name="user_id"
                                    data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                    <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $payslip->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="payslip_month"><?= get_label('payslip_month', 'Payslip month') ?>
                                <span class="asterisk">*</span></label>
                            <input class="form-control" type="month" id="payslip_month" name="month"
                                value="{{ old('month', $payslip->month) }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="basic_salary"><?= get_label('basic_salary', 'Basic salary') ?>
                                              <span class="asterisk">*</span></label>
                            <div class="input-group input-group-merge">                                <span class="input-group-text">{{ $general_settings['currency_symbol'] }}</span>                                <input class="form-control" type="number" id="basic_salary" name="basic_salary"                        placeholder="<?= get_label('please_enter_basic_salary', 'Please enter basic salary') ?>"                                value="{{ old('basic_salary', $payslip->basic_salary ?: 0) }}">          
                                              </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>



                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="working_days"><?= get_label('working_days', 'Working days') ?>
                                <span class="asterisk">*</span></label>
                            <input class="form-control" type="number" id="working_days" name="working_days" step="0.5"
                                placeholder="<?= get_label('please_enter_working_days', 'Please enter working days') ?>"
                                value="{{ old('working_days', $payslip->working_days) }}">
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="lop_days"><?= get_label('lop_days', 'Loss of pay days') ?> <span
                                    class="asterisk">*</span></label>
                            <input class="form-control" type="number" id="lop_days" name="lop_days" step="0.5"
                                placeholder="<?= get_label('please_enter_lop_days', 'Please enter loss of pay days') ?>"
                                value="{{ old('lop_days', $payslip->lop_days) ?? 0 }}">
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="paid_days"><?= get_label('paid_days', 'Paid days') ?> <span
                                    class="asterisk">*</span></label>
                            <input class="form-control" type="number" id="paid_days" name="paid_days"
                                value="{{ old('paid_days', $payslip->paid_days) }}" readonly>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="bonus"><?= get_label('bonus', 'Bonus') ?> <span
                                    class="asterisk">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">{{ $general_settings['currency_symbol'] }}</span>
                                <input class="form-control" type="number" id="bonus" name="bonus"
                                    placeholder="<?= get_label('please_enter_bonus', 'Please enter bonus') ?>"
                                    value="{{ old('bonus', $payslip->bonus) ?? 0 }}">
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="incentives"><?= get_label('incentives', 'Incentives') ?> <span
                                    class="asterisk">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">{{ $general_settings['currency_symbol'] }}</span>
                                <input class="form-control" type="number" id="incentives" name="incentives"
                                    placeholder="<?= get_label('please_enter_incentives', 'Please enter incentives') ?>"
                                    value="{{ old('incentives', $payslip->incentives) ?? 0 }}">
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label"
                                for="leave_deduction"><?= get_label('leave_deduction', 'Leave deduction') ?> <span
                                    class="asterisk">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">{{ $general_settings['currency_symbol'] }}</span>
                                <input class="form-control" type="number" id="leave_deduction" name="leave_deduction"
                                    value="{{ old('leave_deduction', $payslip->leave_deduction) }}" readonly>
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>



                        <div class="mb-3 col-md-4">
                            <label class="form-label"
                                for="over_time_hours"><?= get_label('over_time_hours', 'Over time hours') ?></label>
                            <input class="form-control" type="number" step="0.5" id="over_time_hours"
                                name="ot_hours"
                                placeholder="<?= get_label('please_enter_over_time_hours', 'Please enter over time hours') ?>"
                                value="{{ old('ot_hours', $payslip->ot_hours ?? 0) }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label"
                                for="over_time_rate"><?= get_label('over_time_rate', 'Over time rate') ?> <span
                                    class="asterisk">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">{{ $general_settings['currency_symbol'] }}</span>
                                <input class="form-control" type="number" id="over_time_rate" name="ot_rate"
                                    placeholder="<?= get_label('please_enter_over_time_rate', 'Please enter over time rate') ?>"
                                    value="{{ old('ot_rate', $payslip->ot_rate ?? 0) }}">
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label"
                                for="over_time_payment"><?= get_label('over_time_payment', 'Over time payment') ?> <span
                                    class="asterisk">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">{{ $general_settings['currency_symbol'] }}</span>
                                <input class="form-control" type="number" id="over_time_payment" name="ot_payment"
                                    value="{{ old('ot_payment', $payslip->ot_payment ?? 0) }}" readonly>
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                        </div>



                        <div class="mb-3 col-md-4">
                            <label class="form-label"
                                for="payment_method_id"><?= get_label('payment_method', 'Payment method') ?></label>
                            <div class="input-group">
                                <select name="payment_method_id" class="form-select">
                                    <option value=""><?= get_label('Please select', 'Please select') ?></option>
                                    @foreach ($payment_methods as $payment_method)
                                        <option value="{{ $payment_method->id }}"
                                            {{ old('payment_method_id', $payslip->payment_method_id ?? '') == $payment_method->id ? 'selected' : '' }}>
                                            {{ $payment_method->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-danger text-xs mt-1 error-message"></p>
                            <div class="mt-2">
                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                    data-bs-target="#create_pm_modal"><button type="button"
                                        class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                                        data-bs-original-title="<?= get_label('create_payment_method', 'Create payment method') ?>"><i
                                            class="bx bx-plus"></i></button></a>
                                <a href="/payment-methods" target="_blank"><button type="button"
                                        class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                                        data-bs-original-title="<?= get_label('manage_payment_methods', 'Manage payment methods') ?>"><i
                                            class="bx bx-list-ul"></i></button></a>
                            </div>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label"
                                for="payment_date"><?= get_label('payment_date', 'Payment date') ?></label>
                            <input type="text" id="payment_date" name="payment_date" class="form-control"
                                placeholder="<?= get_label('please_select', 'Please select') ?>"
                                value="{{ !is_null($payslip->payment_date) ? format_date(date('Y-m-d', strtotime($payslip->payment_date)), false, 'Y-m-d', get_php_date_time_format()) : '' }}"
                                autocomplete="off">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="status"><?= get_label('payment_status', 'Payment status') ?>
                                <span class="asterisk">*</span></label>
                            <div class="btn-group btn-group d-flex justify-content-center" role="group"
                                aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" id="ps_paid" name="status" value="1"
                                    {{ old('status', $payslip->status) == '1' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary"
                                    for="ps_paid"><?= get_label('paid', 'Paid') ?></label>

                                <input type="radio" class="btn-check" id="ps_unpaid" name="status" value="0"
                                    {{ old('status', $payslip->status) == '0' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary"
                                    for="ps_unpaid"><?= get_label('unpaid', 'Unpaid') ?></label>
                            </div>

                        </div>






                        <div class="mb-3 col-md-5">
                            <label class="form-label"
                                for="allowance_id"><?= get_label('allowance', 'Allowance') ?></label>
                            <div class="input-group">
                                <select id="allowance_id" name="allowance_id" class="form-select">
                                    <option value=""><?= get_label('Please select', 'Please select') ?></option>
                                    @foreach ($allowances as $allowance)
                                        <option value="{{ $allowance->id }}">{{ $allowance->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2">
                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                    data-bs-target="#create_allowance_modal"><button type="button"
                                        class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                                        data-bs-original-title=" <?= get_label('create_allowance', 'Create allowance') ?>"><i
                                            class="bx bx-plus"></i></button></a>
                                <a href="/allowances" target="_blank"><button type="button"
                                        class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                                        data-bs-original-title="<?= get_label('manage_allowances', 'Manage allowances') ?>"><i
                                            class="bx bx-list-ul"></i></button></a>
                            </div>
                        </div>

                        <div class="mb-3 col-md-7">
                            <label class="form-label"
                                for="deduction_id"><?= get_label('deduction', 'Deduction') ?></label>
                            <div class="input-group">
                                <select id="deduction_id" name="deduction_id" class="form-select">
                                    <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                    @foreach ($deductions as $deduction)
                                        <option value="{{ $deduction->id }}">{{ $deduction->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2">
                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                    data-bs-target="#create_deduction_modal"><button type="button"
                                        class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                                        data-bs-original-title=" <?= get_label('create_deduction', 'Create deduction') ?>"><i
                                            class="bx bx-plus"></i></button></a>
                                <a href="/deductions" target="_blank"><button type="button"
                                        class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                                        data-bs-original-title="<?= get_label('manage_deductions', 'Manage deductions') ?>"><i
                                            class="bx bx-list-ul"></i></button></a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5" id="payslip-allowances">

                                <div class="d-flex">
                                    <div class="mb-3 col-md-6 mx-1">
                                        <label class="form-label text-muted"
                                            for="allowance_0_title"><?= get_label('allowance', 'Allowance') ?></label>
                                        <input type="text" id="allowance_0_title" class="form-control"
                                            placeholder="<?= get_label('allowance', 'Allowance') ?>" readonly>
                                    </div>
                                    <div class="mb-3 col-md-4 mx-1">
                                        <label class="form-label text-muted"
                                            for="allowance_0_amount"><?= get_label('amount', 'Amount') ?>
                                            ({{ $general_settings['currency_symbol'] }})</label>
                                        <input type="text" id="allowance_0_amount" class="form-control"
                                            placeholder="<?= get_label('amount', 'Amount') ?>" readonly>
                                    </div>
                                    <div class="mb-3 col-md-1 mx-1">
                                        <label class="form-label text-muted"
                                            for="allowance_0_action"><?= get_label('action', 'Action') ?></label>
                                        <button type="button" class="btn btn-sm btn-success add-allowance my-1"><i
                                                class="bx bx-check"></i></button>
                                    </div>
                                </div>


                                @if (isset($payslip) && isset($payslip->allowances) && count($payslip->allowances) > 0)
                                    @php
                                        $total_allowance = 0;
                                    @endphp
                                    @foreach ($payslip->allowances as $index => $allowance)
                                        @php
                                            $total_allowance += $allowance->amount;
                                            $displayIndex = $index + 1; // Increment the index by 1 for display
                                        @endphp
                                        <div class="payslip-allowance">
                                            <div class="d-flex">
                                                <input type="hidden" id=allowance_{{ $displayIndex }}
                                                    value="{{ $allowance->id }}" name="allowances[]">
                                                <div class="mb-3 col-md-6 mx-1">
                                                    <input type="text" id="allowance_{{ $displayIndex }}_title"
                                                        class="form-control" value="{{ $allowance->title }}" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4 mx-1">
                                                    <input type="text" id="allowance_{{ $displayIndex }}_amount"
                                                        class="form-control" value="{{ $allowance->amount }}" readonly>
                                                </div>
                                                <div class="mb-3 col-md-1 mx-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger remove-allowance my-1"
                                                        data-count="{{ $displayIndex }}"><i
                                                            class="bx bx-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <div class="col-md-7" id="payslip-deductions">

                                <div class="d-flex">
                                    <div class="mb-3 col-md-5 mx-1">
                                        <label class="form-label text-muted"
                                            for="deduction_0_title"><?= get_label('deduction', 'Deduction') ?></label>
                                        <input type="text" id="deduction_0_title" class="form-control"
                                            placeholder="<?= get_label('deduction', 'Deduction') ?>" readonly>
                                    </div>
                                    <input type="hidden" id="deduction_0_type">
                                    <div class="mb-3 col-md-3 mx-1">
                                        <label class="form-label text-muted"
                                            for="deduction_0_amount"><?= get_label('amount', 'Amount') ?>
                                            ({{ $general_settings['currency_symbol'] }})</label>
                                        <input type="text" id="deduction_0_amount" class="form-control"
                                            placeholder="<?= get_label('amount', 'Amount') ?>" readonly>
                                    </div>
                                    <div class="mb-3 col-md-3 mx-1">
                                        <label class="form-label text-muted"
                                            for="deduction_0_percentage"><?= get_label('percentage', 'Percentage') ?></label>
                                        <input type="number" id="deduction_0_percentage" class="form-control"
                                            placeholder="<?= get_label('percentage', 'Percentage') ?>" readonly>
                                    </div>
                                    <div class="mb-3 col-md-1 mx-1">
                                        <label class="form-label text-muted"
                                            for="deduction_0_action"><?= get_label('action', 'Action') ?></label>
                                        <button type="button" class="btn btn-sm btn-success add-deduction my-1"><i
                                                class="bx bx-check"></i></button>
                                    </div>
                                </div>


                                @if (isset($payslip) && isset($payslip->deductions) && count($payslip->deductions) > 0)
                                    @foreach ($payslip->deductions as $index => $deduction)
                                        @php
                                            $displayIndex = $index + 1; // Increment the index by 1 for display
                                        @endphp
                                        <div class="payslip-deduction">
                                            <div class="d-flex">
                                                <input type="hidden" id=deduction_{{ $displayIndex }}
                                                    value="{{ $deduction->id }}" name="deductions[]">
                                                <div class="mb-3 col-md-5 mx-1">
                                                    <input type="text" id="deduction_{{ $displayIndex }}_title"
                                                        class="form-control" value="{{ $deduction->title }}" readonly>
                                                </div>
                                                <input type="hidden" id="deduction_{{ $displayIndex }}_type">
                                                <div class="mb-3 col-md-3 mx-1">
                                                    <input type="text" id="deduction_{{ $displayIndex }}_amount"
                                                        class="form-control" value="{{ $deduction->amount }}" readonly>
                                                </div>
                                                <div class="mb-3 col-md-3 mx-1">
                                                    <input type="number" id="deduction_{{ $displayIndex }}_percentage"
                                                        class="form-control" value="{{ $deduction->percentage }}"
                                                        readonly>
                                                </div>
                                                <div class="mb-3 col-md-1 mx-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger remove-deduction my-1"
                                                        data-count="{{ $displayIndex }}"><i
                                                            class="bx bx-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>



                        <div class="d-flex">
                            <div class="col-md-5 mt-4">
                                <label class="form-label"
                                    for=""><?= get_label('total_allowances', 'Total allowances') ?>
                                    ({{ $general_settings['currency_symbol'] }}) : <span
                                        id="total_allowance">{{ $payslip->total_allowance ?? 0.0 }}</span></label>
                            </div>
                            <div class="col-md-7 mt-4 mx-4">
                                <label class="form-label"
                                    for=""><?= get_label('total_deductions', 'Total deductions') ?>
                                    ({{ $general_settings['currency_symbol'] }}) : <span
                                        id="total_deduction">{{ $payslip->total_deductions ?? 0.0 }}</span></label>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="col-md-6"></div>
                            <div class="col-md-6 mt-4 text-end">
                                <h6 class="d-none"><?= get_label('total_earnings', 'Total earnings') ?>
                                    ({{ $general_settings['currency_symbol'] }}) : <span
                                        id="total_earning">{{ $payslip->total_earnings ?? 0 }}</span></h6>
                                <input type="hidden" id="total_earnings" name="total_earnings"
                                    value="{{ $payslip->total_earnings ?? 0 }}">


                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="col-md-6"></div>
                            <div class="col-md-6 mt-4 text-end">
                                <h6><?= get_label('net_payable', 'Net payable') ?>
                                    ({{ $general_settings['currency_symbol'] }}) : <span
                                        id="net_payable">{{ $payslip->net_pay ?? 0 }}</span>
                                    <input type="hidden" id="net_pay" name="net_pay"
                                        value="{{ $payslip->net_pay ?? 0 }}">
                                    <h6>
                            </div>
                        </div>

                        <!-- Total Allowance Section -->
                        <!-- <div class="d-flex">
                                   
                                </div> -->


                    </div>

                    <input type="hidden" name="total_allowance" id="hidden_total_allowance"
                        value="{{ $payslip->total_allowance ?? 0 }}">
                    <input type="hidden" name="total_deductions" id="hidden_total_deductions"
                        value="{{ $payslip->total_deductions ?? 0 }}">

                    <input type="hidden" name="allowance_ids" id="allowance_ids"
                        value="{{ implode(',', $payslip->allowances->pluck('id')->toArray()) }}">
                    <input type="hidden" name="deduction_ids" id="deduction_ids"
                        value="{{ implode(',', $payslip->deductions->pluck('id')->toArray()) }}">

                    <div class="mb-3 col-md-12 mt-4">
                        <label for="description" class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" name="note" rows="3"
                            placeholder="<?= get_label('please_enter_note_if_any', 'Please enter note if any') ?>">{{ old('note', $payslip->note ?? '') }}</textarea>

                        @error(' note')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary me-2"
                            id="submit_btn"><?= get_label('update', 'Update') ?></button>
                        <button type="reset"
                            class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script>
        var allowance_count =
            '<?= isset($payslip) && isset($payslip->allowances) && count($payslip->allowances) > 0 ? count($payslip->allowances) : 0 ?>';
        var deduction_count =
            '<?= isset($payslip) && isset($payslip->deductions) && count($payslip->deductions) > 0 ? count($payslip->deductions) : 0 ?>';
        var decimal_points = <?= intval($general_settings['decimal_points_in_currency'] ?? '2') ?>;
    </script>
    <script src="{{ asset('assets/js/pages/payslips.js') }}"></script>
@endsection
