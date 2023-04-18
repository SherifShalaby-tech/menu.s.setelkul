<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>
</head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

<body>

    <div class="row">
        <div class="col-md-4">
            <label for="">@lang('lang.date_and_time'):
            </label>
            @if (!empty($order->created_at))
                {{ @format_datetime($order->created_at) }}
            @endif
        </div>
        <div class="col-md-4">
            <label for="">@lang('lang.customer_name'): </label> {{ $order->customer_name }}
        </div>
        <div class="col-md-4">
            <label for="">@lang('lang.phone_number'): </label> {{ $order->phone_number }}
        </div>
        <div class="col-md-4">
            <label for="">@lang('lang.notes'): </label> {{ $order->sales_note }}
        </div>
        <div class="col-md-4">
            <label for="">@lang('lang.delivery_type'): </label> {{ __('lang.' . $order->delivery_type) }}
        </div>
        <div class="col-md-4">
            <label for="">@lang('lang.payment_type'): </label> {{ __('lang.' . $order->payment_type) }}
        </div>
        <div class="col-md-4">
            <label for="">@lang('lang.total'): @if (!empty($order->final_total))
                    {{ @num_format($order->final_total) }}
                @endif
            </label>
        </div>
        <div class="col-md-4">
            <label for="">@lang('lang.status'): </label> {{ ucfirst($order->status) }}
        </div>
        <div class="col-md-4">
            <label for="">@lang('lang.delivery_status'): </label> {{ ucfirst($order->delivery_status) }}
        </div>
    </div>

    <table id="orders_details_table" class="table" style="width: 50%;">
        <thead>
            <tr>
                <th>@lang('lang.product')</th>
                <th>@lang('lang.quantity')</th>
                <th>@lang('lang.price')</th>
                <th>@lang('lang.sub_total')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->order_details as $item)
                <tr>
                    <td>{{ $item->product->name ?? '' }}</td>
                    <td>{{ @num_format($item->quantity) }}</td>
                    <td>{{ @num_format($item->price) }}</td>
                    <td>{{ @num_format($item->sub_total) }}</td>
                </tr>
            @endforeach

        </tbody>
        <tfoot>
        </tfoot>
    </table>

</body>

</html>
