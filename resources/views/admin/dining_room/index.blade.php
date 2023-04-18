@extends('layouts.admin')

@section('title', __('lang.dining_rooms'))

@section('content_header')
    <h1>@lang('lang.dining_rooms')</h1>
@stop

@section('main_content')
    @can('settings.dining_room.create')
        <a class="btn btn-primary btn-modal btn-flat mb-3" data-container=".view_modal"
            data-href="{{ action('Admin\DiningRoomController@create') }}"><i class="fas fa-plus"></i>
            @lang('lang.add_dining_room')</a>
    @endcan
    <x-adminlte-card title="{{ __('lang.dining_rooms') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="table-responsive">
            <table id="dining_room_table" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('lang.name')</th>

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
            dining_room_table = $('#dining_room_table').DataTable({
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
                    "url": "/admin/dining-room",
                    "data": function(d) {}
                },
                columnDefs: [{
                    "targets": [1],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'name',
                        name: 'name'
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
