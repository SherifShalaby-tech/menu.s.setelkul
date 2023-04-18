@extends('layouts.admin')

@section('title', __('lang.sms'))

@section('content_header')
    <h1>@lang('lang.sms')</h1>
@stop

@section('main_content')
    @can('settings.size.create')
        <a class="btn btn-primary btn-flat mb-3" href="{{ action('Admin\SmsController@create') }}"><i
                class="fas fa-paper-plane"></i>
            @lang('lang.send_sms')</a>
    @endcan
    <x-adminlte-card title="{{ __('lang.sms') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="table-responsive">
            <table id="sms_table" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('lang.date_and_time')</th>
                        <th>@lang('lang.created_by')</th>
                        <th>@lang('lang.content')</th>
                        <th>@lang('lang.receiver')</th>
                        <th>@lang('lang.notes')</th>
                        <th class="notexport">@lang('lang.action')</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </x-adminlte-card>

@stop
@section('javascript')
    <script>
        $(document).ready(function() {
            sms_table = $('#sms_table').DataTable({
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
                    "url": "/admin/sms",
                    "data": function(d) {}
                },
                columnDefs: [{
                    "targets": [5],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'sent_by',
                        name: 'sent_by'
                    },
                    {
                        data: 'message',
                        name: 'message'
                    },
                    {
                        data: 'mobile_numbers',
                        name: 'mobile_numbers'
                    },
                    {
                        data: 'notes',
                        name: 'notes'
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
