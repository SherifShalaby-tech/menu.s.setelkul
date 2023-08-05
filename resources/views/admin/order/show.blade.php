@extends('layouts.admin')

@section('title', __('lang.order'))

@section('content_header')
    <h1>
        @lang('lang.order_no'): {{ $order->id }}</h1>
@stop

@section('main_content')
    <x-adminlte-card title="{{ __('lang.order_no') }} {{ $order->id }}"
        theme="{{ config('adminlte.right_sidebar_theme') }}" theme-mode="outline" icon="fas fa-file">
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
    </x-adminlte-card>
    <x-adminlte-card title="{{ __('lang.orders_details') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">
        <div class="table-responsive">
            <table id="orders_details_table" class="table" style="width: 100%;">
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
        </div>
    </x-adminlte-card>

@stop
@section('javascript')
    <script>

    </script>
@endsection
