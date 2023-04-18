@extends('layouts.admin')
@section('title', __('lang.stores'))

@section('content_header')
    <h1>@lang('lang.stores')</h1>
@stop

@section('main_content')
    @can('settings.store.create')
        <a class="btn btn-primary btn-modal btn-flat mb-3" data-container=".view_modal"
            data-href="{{ action('Admin\StoreController@create') }}"><i class="fas fa-plus"></i>
            @lang('lang.add_store')</a>
    @endcan
    <x-adminlte-card title="{{ __('lang.stores') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="table-responsive">
            <table id="store_table" class="table dataTable">
                <thead>
                    <tr>
                        <th>@lang('lang.name')</th>
                        <th>@lang('lang.location')</th>
                        <th>@lang('lang.phone_number')</th>
                        <th>@lang('lang.email')</th>
                        <th>@lang('lang.manager_name')</th>
                        <th>@lang('lang.manager_mobile_number')</th>
                        <th class="notexport">@lang('lang.action')</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </x-adminlte-card>

@stop


@section('javascript')
    <script>
        $(document).ready(function() {
            store_table = $('#store_table').DataTable({
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
                    "url": "/admin/store",
                    "data": function(d) {}
                },
                columnDefs: [{
                    "targets": [6],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'manager_name',
                        name: 'manager_name'
                    },
                    {
                        data: 'manager_mobile_number',
                        name: 'manager_mobile_number'
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
