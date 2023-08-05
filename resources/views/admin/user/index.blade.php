@extends('layouts.admin')

@section('title', __('lang.users'))

@section('content_header')
<h1>@lang('lang.users')</h1>
@stop

@section('main_content')
@can('product.create')
<a class="btn btn-primary btn-flat mb-3" href="{{action('Admin\UserController@create')}}"><i class="fas fa-plus"></i>
    @lang('lang.add_user')</a>
@endcan
<x-adminlte-card title="{{__('lang.users')}}" theme="{{config('adminlte.right_sidebar_theme')}}"
    theme-mode="outline" icon="fas fa-file">

    <div class="table-responsive">
        <table id="user_table" class="table" style="width: 100%;">
            <thead>
                <tr>
                    <th>@lang('lang.image')</th>
                    <th>@lang('lang.name')</th>
                    <th>@lang('lang.email')</th>
                    <th>@lang('lang.phone')</th>
                    <th>@lang('lang.date_of_join')</th>
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
    $(document).ready( function(){
        user_table = $('#user_table').DataTable({
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
            aaSorting: [[2, 'asc']],
             "ajax": {
                "url": "/admin/user",
                "data": function ( d ) {
                }
            },
            columnDefs: [ {
                "targets": [0, 3],
                "orderable": true,
                "searchable": false
            } ],
            columns: [
                { data: 'image', name: 'image'  },
                { data: 'name', name: 'name'  },
                { data: 'email', name: 'email'  },
                { data: 'phone', name: 'phone'  },
                { data: 'date_of_join', name: 'date_of_join'  },
                { data: 'action', name: 'action'},

            ],
            createdRow: function( row, data, dataIndex ) {

            },
            fnDrawCallback: function(oSettings) {
                var intVal = function (i) {
                    return typeof i === "string"
                        ? i.replace(/[\$,]/g, "") * 1
                        : typeof i === "number"
                        ? i
                        : 0;
                };

                this.api()
                    .columns(".sum", { page: "current" })
                    .every(function () {
                        var column = this;
                        if (column.data().count()) {
                            var sum = column.data().reduce(function (a, b) {
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

    $(document).on('change', '#product_class_id', function(){
        user_table.ajax.reload();
    })
</script>
@endsection
