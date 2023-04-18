@extends('layouts.admin')

@section('title', __('lang.dashboard'))

@section('content_header')
    <h1>@lang('lang.dashboard')</h1>
@stop

@section('plugins.Chartjs', true)
@section('content')
    <div class="row">
        <div class="col-md-4">
            <x-adminlte-card theme="lime" theme-mode="outline" title="{{ __('lang.order_count') }}: {{ $order_count }}"
                icon="fas fa-gem">

                {{-- <x-slot name="toolsSlot">
                    <select class="custom-select w-auto form-control-border bg-light">
                        <option>Skin 1</option>
                        <option>Skin 2</option>
                        <option>Skin 3</option>
                    </select>
                </x-slot> --}}

                <canvas id="order_count_chart" width="400" height="100"></canvas>
            </x-adminlte-card>
        </div>
        <div class="col-md-4">
            <x-adminlte-card theme="dark" theme-mode="outline"
                title="{{ __('lang.number_of_products_sold') }}: {{ $number_of_products }}" icon="fas fa-clipboard">
                <canvas id="number_of_products_chart" width="400" height="100"></canvas>
            </x-adminlte-card>
        </div>
        <div class="col-md-4">
            <x-adminlte-card theme="danger" theme-mode="outline"
                title="{{ __('lang.total_revenue') }}: {{ $total_revenue }}" icon="fas fa-money-bill-alt">
                <canvas id="total_revenue_chart" width="400" height="100"></canvas>
            </x-adminlte-card>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <x-adminlte-info-box title="{{ __('lang.count_carts_now') }}" theme="success" text="{{ $count_carts_now }}"
                icon="fas fa-lg fa-edit" />
        </div>
        <div class="col-md-4">
            <x-adminlte-info-box title="{{ __('lang.total_price_of_basket') }}" theme="danger"
                text="{{ $total_price_of_basket }}" icon="fas fa-lg fa-money-bill-alt" />
        </div>
        <div class="col-md-4">
            <x-adminlte-info-box title="{{ __('lang.count_categories') }}" theme="dark" text="{{ $count_categories }}"
                icon="fas fa-lg fa-puzzle-piece" />
        </div>
        <div class="col-md-4">
            <x-adminlte-info-box title="{{ __('lang.count_products') }}" theme="primary" text="{{ $count_products }}"
                icon="fas fa-lg fa-clipboard" />
        </div>
        <div class="col-md-4">
            <x-adminlte-info-box title="{{ __('lang.count_offers') }}" theme="info" text="{{ $count_offers }}"
                icon="fas fa-lg fa-copy" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card theme="default" theme-mode="outline" title="{{ __('lang.latest_request') }}"
                icon="fas fa-bell">
                <table class=" table table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('lang.order_no')</th>
                            <th>@lang('lang.phone_number')</th>
                            <th>@lang('lang.count')</th>
                            <th>@lang('lang.quantity')</th>
                            <th>@lang('lang.total')</th>
                            <th>@lang('lang.details')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($latest_orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->phone_number}}</td>
                            <td>{{$order->order_details->count()}}</td>
                            <td>{{$order->order_details->sum('quantity')}}</td>
                            <td>{{@num_format($order->final_total)}}</td>
                            <td>
                                <a href="{{action('Admin\OrderController@show', $order->id)}}"
                                    class="btn btn-primary btn-outline">
                                    @lang('lang.details')</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </x-adminlte-card>
        </div>
        <div class="col-md-4">
            <x-adminlte-card theme="default" theme-mode="outline" title="{{ __('lang.latest_messages') }}"
                icon="fas fa-envelope">
                <table class=" table table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('lang.subject')</th>
                            <th>@lang('lang.email')</th>
                            <th>@lang('lang.details')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($messages as $message)
                        <tr>
                            <td>{{$message->subject}}</td>
                            <td>{{$message->emails}}</td>
                            <td>
                                <a href="{{action('Admin\MessageController@show', $message->id)}}"
                                    class="btn btn-primary btn-outline">
                                    @lang('lang.details')</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script>
        const order_count_canvas = document.getElementById('order_count_chart');
        const number_of_products_canvas = document.getElementById('number_of_products_chart');
        const total_revenue_canvas = document.getElementById('total_revenue_chart');
        const config = {
            responsive: true,
            radius: 0,
            lineTension: 0.6,
            fill: true,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'cross'
                    },
                }
            },
            interaction: {
                mode: 'index',
                intersect: true
            },
            scales: {
                x: {
                    display: false,
                },
                y: {
                    display: false,
                },
            }
        };
        const order_count_chart = new Chart(order_count_canvas, {
            type: 'line',
            data: {
                labels: ['', '', '', ''],
                datasets: [{
                    label: "@lang('lang.order_count')",
                    data: [
                        @foreach ($order_count_array as $odc)
                            {{ $odc }},
                        @endforeach
                    ],

                    backgroundColor: [
                        'rgba(255, 99, 132, 0)',
                    ],
                    borderColor: [
                        'rgba(0, 255, 0, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: config
        });
        const number_of_products_chart = new Chart(number_of_products_canvas, {
            type: 'line',
            data: {
                labels: ['', '', '', ''],
                datasets: [{
                    label: "@lang('lang.number_of_products')",
                    data: [
                        @foreach ($number_of_products_array as $nop)
                            {{ $nop }},
                        @endforeach
                    ],

                    backgroundColor: [
                        'rgba(255, 99, 132, 0)',
                    ],
                    borderColor: [
                        'rgba(0, 0, 0, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: config
        });
        const total_revenue_chart = new Chart(total_revenue_canvas, {
            type: 'line',
            data: {
                labels: ['', '', '', ''],
                datasets: [{
                    label: "@lang('lang.total_revenue')",
                    data: [
                        @foreach ($total_revenue_array as $tr)
                            {{ $tr }},
                        @endforeach
                    ],

                    backgroundColor: [
                        'rgba(255, 99, 132, 0)',
                    ],
                    borderColor: [
                        'rgba(0, 0, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: config
        });
    </script>
@stop
