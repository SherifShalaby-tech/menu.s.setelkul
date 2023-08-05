@extends('layouts.admin')

@section('title', __('lang.orders'))

@section('content_header')
    <h1>@lang('lang.orders')</h1>
@stop

@section('main_content')
    <x-adminlte-card title="{{ __('lang.orders') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="table-responsive">
            <table id="orders_table" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('lang.date_and_time')</th>
                        <th>@lang('lang.order_no')</th>
                        <th>@lang('lang.customer_name')</th>
                        <th>@lang('lang.phone_number')</th>
                        <th>@lang('lang.notes')</th>
                        <th>@lang('lang.count')</th>
                        <th>@lang('lang.order_type')</th>
                        <th>@lang('lang.delivery_type')</th>
                        <th>@lang('lang.payment_type')</th>
                        <th>@lang('lang.table_no')</th>
                        <th class="sum">@lang('lang.total')</th>
                        <th>@lang('lang.status')</th>
                        <th>@lang('lang.delivery_status')</th>
                        <th>@lang('lang.address')</th>

                        <th class="notexport">@lang('lang.action')</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="11" class="text-right">@lang('lang.total')</th>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-adminlte-card>

@stop
@section('javascript')
    <script>
        $(document).ready(function() {
            orders_table = $('#orders_table').DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                order: [],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                buttons: buttons,
                processing: true,
                serverSide: true,
                aaSorting: [
                    [2, 'asc']
                ],
                "ajax": {
                    "url": "/admin/orders",
                    "data": function(d) {}
                },
                columnDefs: [{
                    "targets": [0, 3],
                    "orderable": true,
                    "searchable": false
                }],
                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'sales_note',
                        name: 'sales_note'
                    },
                    {
                        data: 'count',
                        name: 'count'
                    },
                    {
                        data: 'order_type',
                        name: 'order_type'
                    },
                    {
                        data: 'delivery_type',
                        name: 'delivery_type'
                    },
                    {
                        data: 'payment_type',
                        name: 'payment_type'
                    },
                    {
                        data: 'table_no',
                        name: 'table_no'
                    },
                    {
                        data: 'final_total',
                        name: 'final_total'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'delivery_status',
                        name: 'delivery_status'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },

                    {
                        data: 'action',
                        name: 'action'
                    },

                ],
                createdRow: function(row, data, dataIndex) {

                },
                fnDrawCallback: function(oSettings) {
                    var intVal = function(i) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ?
                            i :
                            0;
                    };

                    this.api()
                        .columns(".sum", {
                            page: "current"
                        })
                        .every(function() {
                            var column = this;
                            if (column.data().count()) {
                                var sum = column.data().reduce(function(a, b) {
                                    a = intVal(a);
                                    if (isNaN(a)) {
                                        a = 0;
                                    }

                                    b = intVal(b);
                                    if (isNaN(b)) {
                                        b = 0;
                                    }

                                    return a + b;
                                });
                                $(column.footer()).html(
                                    __currency_trans_from_en(sum, false)
                                );
                            }
                        });
                },
            });

        });
    </script>
@endsection
