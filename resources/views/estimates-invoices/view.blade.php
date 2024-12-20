@php
use App\Models\Tax; // Adjust the namespace and path according to your application's structure
@endphp
@extends('layout') <!-- Assuming you have a layout file -->

@section('title')
<?= $estimate_invoice->type == 'estimate' ? get_label('view_estimate', 'View estimate') : get_label('view_invoice', 'View invoice') ?>
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
                        <a href="/estimates-invoices"><?= get_label('etimates_invoices', 'Estimates/Invoices') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= $estimate_invoice->type == 'estimate' ? get_label('view_estimate', 'View estimate') : get_label('view_invoice', 'View invoice') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/estimates-invoices')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title=" <?= get_label('etimates_invoices', 'Estiamtes/Invoices') ?>"><i class="bx bx-list-ul"></i></button></a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div id='section-to-print'>
                <div class="row mb-4">
                    <div class="col-md-2">
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
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong><?= get_label('billing_details', 'Billing details') ?></strong>
                            </li>
                            <li class="list-group-item">
                                {{$estimate_invoice->name}}<br>
                                {{$estimate_invoice->address}}<br>
                                {{ $estimate_invoice->city }}, {{ $estimate_invoice->state }}, {{ $estimate_invoice->country }}, {{ $estimate_invoice->zip_code }}<br>
                                {{$estimate_invoice->phone}}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong><?= $estimate_invoice->type == 'estimate' ? get_label('estimate_details', 'Estimate details') : get_label('invoice_details', 'Invoice details') ?></strong>
                            </li>
                            <li class="list-group-item">
                                <strong><?= $estimate_invoice->type == 'estimate' ? get_label('estimate_no', 'Estimate No.') : get_label('invoice_no', 'Invoice No.') ?>:</strong> #{{ $estimate_invoice->type == 'estimate' ? get_label('estimate_id_prefix', 'ESTMT-') : get_label('invoice_id_prefix', 'INVC-') }} {{$estimate_invoice->id}}<br>
                                <strong><?= get_label('from_date', 'From date') ?>:</strong> {{$estimate_invoice->from_date}}<br>
                                <strong><?= get_label('to_date', 'To date') ?>:</strong> {{$estimate_invoice->to_date}}<br>
                                <strong><?= get_label('status', 'Status') ?>:</strong> <?= $estimate_invoice->status ?>
                            </li>
                        </ul>
                    </div>

                </div>


                <!-- Display Items -->
                <div class="row mt-4 mx-1">
                    <div class="col-md-12">
                        <h5><?= $estimate_invoice->type == 'estimate' ? get_label('estimate_summary', 'Estimate summary') : get_label('invoice_summary', 'Invoice summary') ?></h5>
                        @if(count($estimate_invoice->items) > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ get_label('product_service', 'Product/Service') }}</th>
                                    <th>{{ get_label('quantity', 'Quantity') }}</th>
                                    <th>{{ get_label('unit', 'Unit') }}</th>
                                    <th>{{ get_label('rate', 'Rate') }}</th>
                                    <th>{{ get_label('tax', 'Tax') }}</th>
                                    <th>{{ get_label('amount', 'Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $count = 0;
                                @endphp
                                @foreach($estimate_invoice->items as $item)
                                @php
                                $count++;
                                @endphp
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $item->title }}</td> <!-- Assuming 'title' is the attribute containing product/service name -->
                                    <td>{{ $item->pivot->qty }}</td>
                                    <td>
                                        {{ $item->pivot->unit_id ? $item->unit->title : '-' }}
                                    </td>
                                    <td>{{ format_currency($item->pivot->rate) }}</td>
                                    <td>

                                        {{ $item->pivot->tax_id ? Tax::find($item->pivot->tax_id)->title .' - '. get_tax_data($item->pivot->tax_id, $item->pivot->rate * $item->pivot->qty,1)['dispTax'] : '-' }}

                                    </td>
                                    <td>{{ format_currency($item->pivot->amount) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @else
                        <p><?= get_label('no_items_found', 'No items found') ?></p>
                        @endif
                    </div>
                </div>

                @if ($estimate_invoice->type == 'invoice')
                <div class="row mt-4 mx-1">
                    <div class="col-md-12">
                        <h5><?= get_label('payment_summary', 'Payment summary') ?></h5>
                        @if(count($estimate_invoice->payments) > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ get_label('id', 'ID') }}</th>
                                    <th>{{ get_label('amount', 'Amount') }}</th>
                                    <th>{{ get_label('payment_method', 'Payment method') }}</th>
                                    <th>{{ get_label('note', 'Note') }}</th>
                                    <th>{{ get_label('payment_date', 'Payment date') }}</th>
                                    <th>{{ get_label('amount_left', 'Amount left') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 1;
                                @endphp
                                @foreach($estimate_invoice->payments as $payment)
                                @php
                                // Get the total paid amount for the invoice
                                $paid_amount = $estimate_invoice->payments->where('id', '<=', $payment->id)->sum('amount');

                                    // Calculate the amount left
                                    $amount_left = $estimate_invoice->final_total - $paid_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $payment->id }}</td>
                                        <td>{{ format_currency($payment->amount) }}</td>
                                        <td>{{ $payment->paymentMethod->title ?? '-' }}</td>
                                        <td>{{ $payment->note ?? '-' }}</td>
                                        <td>{{ format_date($payment->payment_date) }}</td>
                                        <td>{{ format_currency($amount_left) }}</td>
                                    </tr>
                                    @php
                                    $i++;
                                    @endphp
                                    @endforeach
                            </tbody>


                        </table>

                        @else
                        <p><?= get_label('no_payments_found_invoice', 'No payments found for this invoice.') ?></p>
                        @endif
                    </div>
                </div>
                @endif
                <div class="row mt-4 mx-1">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">

                        <!-- Net Payable -->
                        <div class="text-end mt-4">
                            <div class="invoice-detail-item">
                                <div class="invoice-detail-name"><?= get_label('sub_total', 'Sub total') ?></div>
                                <div class="invoice-detail-value">{{ format_currency($estimate_invoice->total) }}</div>
                            </div>
                            <div class="invoice-detail-item">
                                <div class="invoice-detail-name"><?= get_label('tax', 'Tax') ?></div>
                                <div class="invoice-detail-value">{{ format_currency($estimate_invoice->tax_amount) }}</div>
                            </div>
                            <div class="invoice-detail-item">
                                <div class="invoice-detail-name"><?= get_label('final_total', 'Final total') ?></div>
                                <div class="invoice-detail-value">{{ format_currency($estimate_invoice->final_total) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Note -->
                <div class="row mt-4 mx-1">
                    <div class="col-md-6">
                        @if ($estimate_invoice->note)
                        <h5><?= get_label('note', 'Note') ?></h5>
                        <p>{{ $estimate_invoice->note ?: '-' }}</p>
                        @endif
                        @if ($estimate_invoice->personal_note)
                        <h5><?= get_label('personal_note', 'Personal note') ?></h5>
                        <p>{{ $estimate_invoice->personal_note ?: '-' }}</p>
                        @endif
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-6 text-start">
                        <span class="text-muted">
                            <strong><?= get_label('created_at', 'Created at') ?>:</strong>
                            {{ format_date($estimate_invoice->created_at,true) }}
                        </span>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="text-muted">
                            <strong><?= get_label('last_updated_at', 'Last updated at') ?>:</strong>
                            {{ format_date($estimate_invoice->updated_at,true) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection