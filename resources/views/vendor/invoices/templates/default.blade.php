<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $invoice->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css" media="screen">
        html {
            font-family: sans-serif;
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 36pt;
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        strong {
            font-weight: bolder;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        table {
            border-collapse: collapse;
        }

        th {
            text-align: inherit;
        }

        h4,
        .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4,
        .h4 {
            font-size: 1.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
        }

        .table.table-items td {
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
            padding-left: 0 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        * {
            font-family: "DejaVu Sans";
        }

        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        table,
        th,
        tr,
        td,
        p,
        div {
            line-height: 1.1;
        }

        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }

        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }

        .border-0 {
            border: none !important;
        }

        .cool-gray {
            color: #6B7280;
        }
    </style>
</head>

<body>
    @php

    use App\Models\Tax; // Adjust the namespace and path according to your application's structure
    $customData = $invoice->getCustomData();
    $estimate_invoice = $customData['estimate_invoice'];
    @endphp
    {{-- Header --}}
    @if($invoice->logo)
    <img src="{{ $invoice->logo }}" alt="logo" height="75" class="pl-0">
    @endif

    <table class="table mt-5">
        <tbody>
            <tr>
                <td class="border-0 pl-0" width="70%">
                    <h4 class="text-uppercase">
                        <strong>{{ $estimate_invoice->type == 'estimate' ? get_label('estimate', 'Estimate') : get_label('invoice', 'Invoice') }}</strong>
                    </h4>
                </td>
                <td class="border-0 pl-0">
                    @if($invoice->status)
                    <h4 class="text-uppercase cool-gray">
                        <strong>{{ $invoice->status }}</strong>
                    </h4>
                    @endif
                    <p><?= $estimate_invoice->type == 'estimate' ? get_label('estimate_no', 'Estimate No.') : get_label('invoice_no', 'Invoice No.') ?>:<strong> #{{ $estimate_invoice->type == 'estimate' ? get_label('estimate_id_prefix', 'ESTMT-') : get_label('invoice_id_prefix', 'INVC-') }}{{ $estimate_invoice->id }}</strong></p>
                    <p>{{ get_label('from_date', 'From date') }}: <strong>{{ format_date($estimate_invoice->from_date) }}</strong></p>
                    <p>{{ get_label('to_date', 'To date') }}: <strong>{{ format_date($estimate_invoice->to_date) }}</strong></p>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Seller - Buyer --}}
    <table class="table">
        <thead>
            <tr>
                <th class="border-0 pl-0 party-header" width="48.5%">
                    {{ get_label('billing_details', 'Billing details') }}
                </th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-0">
                    @if($invoice->seller->name)
                    <p class="seller-name">
                        <strong>{{ $invoice->seller->name }}</strong>
                    </p>
                    @endif

                    @if($invoice->seller->address)
                    <p class="seller-address">
                        {{ $invoice->seller->address }}
                    </p>
                    @endif
                    @if($invoice->seller->city_state_country_zip)
                    <p class="seller-address">
                        {{ $invoice->seller->city_state_country_zip }}
                    </p>
                    @endif

                    @if($invoice->seller->phone)
                    <p class="seller-phone">
                        {{ $invoice->seller->phone }}
                    </p>
                    @endif
                </td>

            </tr>
        </tbody>
    </table>

    {{-- Table --}}
    <table class="table table-items">
        <thead>
            <tr>
                <th class="border-0 pl-0 party-header" width="48.5%">
                    <?= $estimate_invoice->type == 'estimate' ? get_label('estimate_summary', 'Estimate summary') : get_label('invoice_summary', 'Invoice summary') ?>
                </th>

            </tr>
            @if(count($estimate_invoice->items) > 0)
            <tr>
                <th scope="col" class="border-0 pl-0" width="30%">{{ get_label('product_service', 'Product/Service') }}</th>
                <th scope="col" class="text-center border-0">{{ get_label('quantity', 'Quantity') }}</th>
                <th scope="col" class="text-center border-0">{{ get_label('unit', 'Unit') }}</th>
                <th scope="col" class="text-center border-0" width="30%">{{ get_label('rate', 'Rate') }}</th>
                <th scope="col" class="text-center border-0" width="30%">{{ get_label('tax', 'Tax') }}</th>
                <th scope="col" class="text-center border-0 pr-0" width="40%">{{ get_label('amount', 'Amount') }}</th>
            </tr>
            @else
            <tr>
                <th class="border-0 pl-0">{{get_label('no_items_found', 'No items found')}}</th>
            </tr>
            @endif
        </thead>
        <tbody>
            {{-- Items --}}
            @if(count($estimate_invoice->items) > 0)
            @php
            $count = 0;
            @endphp
            @foreach($estimate_invoice->items as $item)
            @php
            $count++;
            @endphp
            <tr>
                <td class="pl-0">
                    {{ $item->title }}


                    @if($item->description)
                    <p class="cool-gray">{{ $item->description }}</p>
                    @endif

                </td>
                <td class="text-center">{{ $item->pivot->qty }}</td>
                <td class="text-center">{{ $item->pivot->unit_id ? $item->unit->title : '-' }}</td>
                <td class="text-center">
                    {{ format_currency($item->pivot->rate) }}
                </td>
                <td class="text-center">
                    {{ $item->pivot->tax_id ? Tax::find($item->pivot->tax_id)->title .' - '. get_tax_data($item->pivot->tax_id, $item->pivot->rate * $item->pivot->qty,1)['dispTax'] : '-' }}
                </td>
                <td class="text-center pr-0">
                    {{ format_currency($item->pivot->amount) }}
                </td>
            </tr>
            @endforeach
            @endif
            {{-- Summary --}}

            <tr>
                <td colspan="4" class="border-1"></td>
                <td class="text-center pl-0">{{ get_label('sub_total', 'Sub total') }}</td>
                <td class="text-center pr-0">
                    {{ format_currency($estimate_invoice->total) }}
                </td>
            </tr>

            <tr>
                <td colspan="4" class="border-1"></td>
                <td class="text-center pl-0">{{ get_label('tax', 'Tax') }}</td>
                <td class="text-center pr-0">
                    {{ format_currency($estimate_invoice->tax_amount) }}
                </td>
            </tr>

            <tr>
                <td colspan="4" class="border-1"></td>
                <td class="text-center pl-0">{{ get_label('final_total', 'Final total') }}</td>
                <td class="text-center pr-0 total-amount">
                    {{ format_currency($estimate_invoice->final_total) }}
                </td>
            </tr>
        </tbody>
    </table>
    @if ($estimate_invoice->type == 'invoice')
    <!-- Table for Payments -->
    <table class="table table-items">
        <thead>
            <tr>
                <th class="border-0 pl-0 party-header" width="20%">
                    <?= get_label('payment_summary', 'Payment summary') ?>
                </th>
            </tr>
            @if(count($estimate_invoice->payments) > 0)
            <tr>
                <th scope="col" class="border-1" width="5%">#</th>
                <th scope="col" class="border-1" width="5%">{{ get_label('id', 'ID') }}</th>
                <th scope="col" class="border-1" width="30%">{{ get_label('amount', 'Amount') }}</th>
                <th scope="col" class="border-1" width="30%">{{ get_label('payment_method', 'Payment method') }}</th>
                <th scope="col" class="border-1" width="20%">{{ get_label('note', 'Note') }}</th>
                <th scope="col" class="border-1" width="30%">{{ get_label('payment_date', 'Payment date') }}</th>
                <th scope="col" class="border-1 pr-0" width="40%">{{ get_label('amount_left', 'Amount left') }}</th>
            </tr>
            @else
            <tr>
                <th class="border-0 pl-0">{{get_label('no_payments_found_invoice', 'No payments found for this invoice.')}}</th>
            </tr>
            @endif
        </thead>
        <tbody>
            {{-- Items --}}
            @if(count($estimate_invoice->payments) > 0)
            @php
            $count = 1;
            @endphp
            @foreach($estimate_invoice->payments as $payment)
            @php
            // Get the total paid amount for the invoice
            $paid_amount = $estimate_invoice->payments->where('id', '<=', $payment->id)->sum('amount');

                // Calculate the amount left
                $amount_left = $estimate_invoice->final_total - $paid_amount;
                @endphp
                <tr>
                    <td scope="col" class="border-0">{{ $count }}</td>
                    <td scope="col" class="border-0">{{ $payment->id }}</td>
                    <td scope="col" class="border-0">{{ format_currency($payment->amount) }}</td>
                    <td scope="col" class="border-0">{{ $payment->paymentMethod->title ?? '-' }}</td>
                    <td scope="col" class="border-0">{{ $payment->note ?? '-' }}</td>
                    <td scope="col" class="border-0">{{ format_date($payment->payment_date) }}</td>
                    <td scope="col" class="border-0">{{ format_currency($amount_left) }}</td>
                </tr>
                @php
                $count++;
                @endphp
                @endforeach
                @endif
        </tbody>


    </table>
    @endif

    @if($estimate_invoice->note)
    <p>
        {{ get_label('note', 'Note') }}: {!! $estimate_invoice->note !!}
    </p>
    @endif

    @if($estimate_invoice->personal_note)
    <p>
        {{ get_label('personal_note', 'Personal note') }}: {!! $estimate_invoice->personal_note !!}
    </p>
    @endif

    <script type="text/php">
        if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "{{ __('invoices::invoice.page') }} {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
</body>

</html>