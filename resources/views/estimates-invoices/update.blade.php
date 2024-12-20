@extends('layout')

@section('title')
<?= $estimate_invoice->type == 'estimate' ? get_label('update_estimate', 'Update estimate') : get_label('update_invoice', 'Update invoice') ?>
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
                        <a href="{{url('/estimates-invoices')}}"><?= get_label('etimates_invoices', 'Estimates/Invoices') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?= get_label('update', 'Update') ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/estimates-invoices')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title=" <?= get_label('etimates_invoices', 'Estiamtes/Invoices') ?>"><i class="bx bx-list-ul"></i></button></a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{url('/estimates-invoices/update')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="redirect_url" value="/estimates-invoices">
                <input type="hidden" name="id" value="{{$estimate_invoice->id}}">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for=""><?= get_label('etimate_invoice', 'Estimate/Invoice') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-select" name="type" id="type">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                <option value="estimate" {{ old('type', $estimate_invoice->type) == 'estimate' ? 'selected' : '' }}><?= get_label('estimate', 'Estimate') ?></option>
                                <option value="invoice" {{ old('type', $estimate_invoice->type) == 'invoice' ? 'selected' : '' }}><?= get_label('invoice', 'Invoice') ?></option>
                            </select>
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for=""><?= get_label('select_client', 'Select client') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" id="client_id" name="client_id" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @foreach($clients as $client)
                                <option value="{{$client->id}}" {{ (old('client_id', $estimate_invoice->client_id) == $client->id) ? 'selected' : '' }}>{{$client->first_name}} {{$client->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for=""><?= get_label('status', 'Status') ?></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="status" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>" id="status">
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @if($estimate_invoice->type == 'estimate')
                                <option value="sent" {{ (old('status', $estimate_invoice->status) == 'sent') ? 'selected' : '' }}><?= get_label('sent', 'Sent') ?></option>
                                <option value="accepted" {{ (old('status', $estimate_invoice->status) == 'accepted') ? 'selected' : '' }}><?= get_label('accepted', 'Accepted') ?></option>
                                <option value="draft" {{ (old('status', $estimate_invoice->status) == 'draft') ? 'selected' : '' }}><?= get_label('draft', 'Draft') ?></option>
                                <option value="declined" {{ (old('status', $estimate_invoice->status) == 'declined') ? 'selected' : '' }}><?= get_label('declined', 'Declined') ?></option>
                                <option value="expired" {{ (old('status', $estimate_invoice->status) == 'expired') ? 'selected' : '' }}><?= get_label('expired', 'Expired') ?></option>
                                @elseif($estimate_invoice->type == 'invoice')
                                <option value="fully_paid" {{ (old('status', $estimate_invoice->status) == 'fully_paid') ? 'selected' : '' }}><?= get_label('fully_paid', 'Fully paid') ?></option>
                                <option value="partially_paid" {{ (old('status', $estimate_invoice->status) == 'partially_paid') ? 'selected' : '' }}><?= get_label('partially_paid', 'Partially paid') ?></option>
                                <option value="draft" {{ (old('status', $estimate_invoice->status) == 'draft') ? 'selected' : '' }}><?= get_label('draft', 'Draft') ?></option>
                                <option value="cancelled" {{ (old('status', $estimate_invoice->status) == 'cancelled') ? 'selected' : '' }}><?= get_label('cancelled', 'Cancelled') ?></option>
                                <option value="due" {{ (old('status', $estimate_invoice->status) == 'due') ? 'selected' : '' }}><?= get_label('due', 'Due') ?></option>
                                @endif
                            </select>
                        </div>
                        <p class="text-danger text-xs mt-1 error-message"></p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for=""><?= get_label('billing_details', 'Billing details') ?> <span class="asterisk">*</span></label>
                            <a href="javascript:void(0);" class="edit-billing-details"><i class="bx bx-edit mx-1"></i></a>
                            <address>
                                <span class="billing_name">{{$estimate_invoice->name}}</span><br>
                                <span class="billing_address">{{$estimate_invoice->address}}</span><br>
                                <span class="billing_city">{{$estimate_invoice->city}}</span>,
                                <span class="billing_state">{{$estimate_invoice->state}}</span>
                                <br>
                                <span class="billing_country">{{$estimate_invoice->country}}</span>,
                                <span class="billing_zip">{{$estimate_invoice->zip_code}}</span><br>
                                <span class="billing_contact">{{$estimate_invoice->phone}}</span>
                            </address>
                        </div>
                        <input type="hidden" name="name" id="name" value="{{$estimate_invoice->name}}">
                        <input type="hidden" name="address" id="address" value="{{$estimate_invoice->address}}">
                        <input type="hidden" name="city" id="city" value="{{$estimate_invoice->city}}">
                        <input type="hidden" name="state" id="state" value="{{$estimate_invoice->state}}">
                        <input type="hidden" name="country" id="country" value="{{$estimate_invoice->country}}">
                        <input type="hidden" name="zip_code" id="zip_code" value="{{$estimate_invoice->zip_code}}">
                        <input type="hidden" name="phone" id="contact" value="{{$estimate_invoice->phone}}">

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for=""><?= get_label('note', 'Note') ?></label>
                            <textarea class="form-control" placeholder="<?= get_label('please_enter_note_if_any', 'Please enter note if any') ?>" name="note">{{$estimate_invoice->note??''}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('from_date', 'From date') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="start_date" name="from_date" class="form-control" value="{{ $estimate_invoice->from_date?format_date($estimate_invoice->from_date):''}}" placeholder="" autocomplete="off">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('to_date', 'To date') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="end_date" name="to_date" class="form-control" value="{{ $estimate_invoice->to_date?format_date($estimate_invoice->to_date):''}}" placeholder="" autocomplete="off">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for=""><?= get_label('personal_note', 'Personal note') ?></label>
                            <textarea class="form-control" placeholder="<?= get_label('please_enter_personal_note_if_any', 'Please enter personal note if any') ?>" name="personal_note">{{$estimate_invoice->personal_note??''}}</textarea>
                        </div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label" for=""><?= get_label('item', 'Item') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select id="item_id" name="item_id" class="form-control js-example-basic-multiple">
                                <option value=""><?= get_label('Please select', 'Please select') ?></option>
                                @foreach ($items as $item)
                                <option value="{{$item->id}}">{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_item_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_item', 'Create item') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/items" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_items', 'Manage items') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="estimate-invoice-items">
                            <div class="d-flex">
                                <div class="mb-3 col-md-2 mx-1">
                                    <label class="form-label text-muted" for=""><?= get_label('product_service', 'Product/Service') ?> <span class="asterisk">*</span></label>
                                    <input type="text" id="item_0_title" class="form-control" readonly>
                                </div>
                                <div class="mb-3 col-md-2 mx-1">
                                    <label class="form-label text-muted" for=""><?= get_label('description', 'Description') ?></label>
                                    <textarea class="form-control" id="item_0_description" readonly></textarea>
                                </div>
                                <div class="mb-3 col-md-1 mx-1">
                                    <label class="form-label text-muted" for=""><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                                    <input type="number" step="0.25" id="item_0_quantity" placeholder="1" class="form-control" min="0.25" value="1" onchange="update_amount(0,0)">
                                </div>
                                <div class="mb-3 col-md-1 mx-1">
                                    <label class="form-label text-muted" for=""><?= get_label('unit', 'Unit') ?></label>
                                    <select class="form-select" id="item_0_unit">
                                        <option value=""><?= get_label('select', 'Select') ?></option>
                                        @foreach ($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-2 mx-1">
                                    <label class="form-label text-muted" for=""><?= get_label('rate', 'Rate') ?> ({{$general_settings['currency_symbol']}}) <span class="asterisk">*</span></label>
                                    <input type="text" id="item_0_rate" placeholder="{{format_currency(0,0)}}" class="form-control" min="0" onchange="update_amount(0,0)">
                                </div>
                                <div class="mb-3 col-md-1 mx-1">
                                    <label class="form-label text-muted" for=""><?= get_label('tax', 'Tax') ?></label>
                                    <select class="form-select" id="item_0_tax" onchange="update_amount(0,0)">
                                        <option value=""><?= get_label('select', 'Select') ?></option>
                                        @foreach ($taxes as $tax)
                                        <option value="{{$tax->id}}">{{$tax->title}}</option>
                                        @endforeach
                                    </select>
                                    <div class="item_0_tax_title"></div>
                                </div>
                                <div class="mb-3 col-md-2 mx-1">
                                    <label class="form-label text-muted" for=""><?= get_label('amount', 'Amount') ?> ({{$general_settings['currency_symbol']}}) <span class="asterisk">*</span></label>
                                    <input type="text" id="item_0_amount" placeholder="{{format_currency(0,0)}}" class="form-control" min="0" onchange="updateTotals()">
                                </div>
                                <div class="mb-3 col-md-1 mx-1">
                                    <label class="form-label text-muted" for=""><?= get_label('action', 'Action') ?></label>
                                    <button type="button" class="btn btn-sm btn-success my-1" id="add-item"><i class="bx bx-check"></i></button>
                                </div>
                            </div>
                            @if (isset($estimate_invoice) && isset($estimate_invoice->items) && count($estimate_invoice->items) > 0)
                            @foreach ($estimate_invoice->items as $index => $item)
                            @php
                            $displayIndex = $index + 1; // Increment the index by 1 for display
                            $taxAmount=0;
                            $disp_tax='';
                            @endphp
                            <div class="estimate-invoice-item">
                                <div class="d-flex">
                                    <input type="hidden" id="item_{{$displayIndex}}" value="{{$item->id}}" name="item[]">
                                    <div class="mb-3 col-md-2 mx-1">
                                        <input type="text" id="item_{{$displayIndex}}_title" value="{{$item['title']}}" class="form-control" readonly>
                                    </div>
                                    <div class="mb-3 col-md-2 mx-1">
                                        <textarea class="form-control" id="item_{{$displayIndex}}_description" readonly>{{$item['description']}}</textarea>
                                    </div>
                                    <div class="mb-3 col-md-1 mx-1">
                                        <input type="number" step="0.25" name="quantity[]" id="item_{{$displayIndex}}_quantity" placeholder="1" class="form-control" min="0.25" value="{{$item->pivot->qty}}" onchange="update_amount(<?= $displayIndex ?>)">
                                    </div>
                                    <div class="mb-3 col-md-1 mx-1">
                                        <select class="form-select" name="unit[]" id="item_{{$displayIndex}}_unit">
                                            <option value=""><?= get_label('select', 'Select') ?></option>
                                            @foreach ($units as $unit)
                                            <option value="{{$unit->id}}" {{$item->pivot->unit_id==$unit['id']?'selected':''}}>{{$unit->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-2 mx-1">
                                        <input type="text" name="rate[]" id="item_{{$displayIndex}}_rate" placeholder="{{format_currency(0,0)}}" class="form-control" min="0" value="{{format_currency($item->pivot->rate,0)}}" onchange="update_amount(<?= $displayIndex ?>)">
                                    </div>
                                    <div class="mb-3 col-md-1 mx-1">
                                        <select class="form-select" name="tax[]" id="item_{{$displayIndex}}_tax" onchange="update_amount(<?= $displayIndex ?>)">
                                            <option value="">{{ get_label('select', 'Select') }}</option>
                                            @foreach ($taxes as $tax)
                                            <option value="{{$tax->id}}" {{$item->pivot->tax_id == $tax->id ? 'selected' : ''}}>{{$tax->title}}</option>
                                            @endforeach
                                        </select>
                                        <div class="item_{{$displayIndex}}_tax_title">{{get_tax_data($item->pivot->tax_id,$item->pivot->rate*$item->pivot->qty)['dispTax']}}</div>
                                        <input class="item_{{$displayIndex}}_tax_title" type="hidden" name="tax_amount[]" value="{{get_tax_data($item->pivot->tax_id,$item->pivot->rate*$item->pivot->qty)['taxAmount']}}"></input>
                                    </div>

                                    <div class="mb-3 col-md-2 mx-1">
                                        <input type="text" name="amount[]" id="item_{{$displayIndex}}_amount" placeholder="{{format_currency(0,0)}}" class="form-control" min="0" value="{{format_currency($item->pivot->amount,0)}}" onchange="updateTotals()">
                                    </div>
                                    <div class="mb-3 col-md-1 mx-1">
                                        <button type="button" class="btn btn-sm btn-danger my-1 remove-estimate-invoice-item" data-count={{$displayIndex}}><i class="bx bx-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif



                        </div>
                    </div>
                    <input type="hidden" name="item_ids" id="item_ids" value="{{ implode(',', $estimate_invoice->items->pluck('id')->toArray()) }}">
                    <div class="d-flex">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 mt-4 text-end">
                            <h6><?= get_label('sub_total', 'Sub total') ?> ({{$general_settings['currency_symbol']}})</h6>
                            <input type="text" class="form-control" name="total" id="sub_total" placeholder="{{format_currency(0,0)}}" value="{{ format_currency($estimate_invoice->total,0)}}" onchange="updateFinalTotal()">

                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 mt-4 text-end">
                            <h6><?= get_label('tax', 'Tax') ?> ({{$general_settings['currency_symbol']}})</h6>
                            <input type="text" class="form-control" name="tax_amount" id="total_tax" placeholder="{{format_currency(0,0)}}" value="{{ format_currency($estimate_invoice->tax_amount,0)}}" onchange="updateFinalTotal()">

                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 mt-4 text-end">
                            <h6><?= get_label('final_total', 'Final total') ?> ({{$general_settings['currency_symbol']}})</h6>
                            <input type="text" class="form-control" name="final_total" id="final_total" placeholder="{{format_currency(0,0)}}" value="{{ format_currency($estimate_invoice->final_total,0)}}">

                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('update', 'Update') ?></button>
                        <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        var label_billing_details_updated_successfully = '<?= get_label('billing_details_updated_successfully', 'Billing details updated successfully.') ?>';
        var label_apply = '<?= get_label('apply', 'Apply') ?>';
        var label_please_wait = '<?= get_label('please_wait', 'Please wait...') ?>';
        var label_sent = '<?= get_label('sent', 'Sent') ?>';
        var label_accepted = '<?= get_label('accepted', 'Accepted') ?>';
        var label_partially_paid = '<?= get_label('partially_paid', 'Partially paid') ?>';
        var label_fully_paid = '<?= get_label('fully_paid', 'Fully paid') ?>';
        var label_draft = '<?= get_label('draft', 'Draft') ?>';
        var label_declined = '<?= get_label('declined', 'Declined') ?>';
        var label_expired = '<?= get_label('expired', 'Expired') ?>';
        var label_cancelled = '<?= get_label('cancelled', 'Cancelled') ?>';
        var label_due = '<?= get_label('due', 'Due') ?>';
        var taxes = '<?php
                        echo "<option value=>" . get_label('select', 'Select') . "</option>";
                        foreach ($taxes as $tax) {

                            echo "<option value=" . $tax['id'] . ">" . $tax['title'] . "</option>";
                        } ?>';
        var units = '<?php
                        echo "<option value=>" . get_label('select', 'Select') . "</option>";
                        foreach ($units as  $unit) {
                            echo "<option value=" . $unit['id'] . ">" . $unit['title'] . "</option>";
                        }
                        ?>';
        var items_count = <?= count($estimate_invoice->items) ?>;
        var decimal_points = <?= intval($general_settings['decimal_points_in_currency'] ?? '2') ?>;
    </script>
    <script src="{{asset('assets/js/pages/estimates-invoices.js')}}"></script>
    @endsection