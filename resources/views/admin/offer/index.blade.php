@extends('layouts.admin')

@section('title', __('lang.list_offers'))

@section('content_header')
    <h1>@lang('lang.list_offers')</h1>
@stop

@section('main_content')
    @can('offer.create')
        <a class="btn btn-primary btn-flat mb-3" href="{{ action('Admin\OfferController@create') }}"><i
                class="fas fa-plus"></i>
            @lang('lang.add_offer')</a>
    @endcan
    <x-adminlte-card title="{{ __('lang.filter') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-filter">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('product_id', __('lang.products'), []) !!} <br>
                    {!! Form::select('product_id', $products, false, ['class' => 'select2 form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select')]) !!}
                </div>
            </div>

        </div>
    </x-adminlte-card>
    <x-adminlte-card title="{{ __('lang.list_offers') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="table-responsive">
            <table id="offer_table" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('lang.name')</th>
                        <th>@lang('lang.description')</th>
                        <th>@lang('lang.products')</th>
                        <th>@lang('lang.discount_type')</th>
                        <th>@lang('lang.discount')</th>
                        <th>@lang('lang.start_date')</th>
                        <th>@lang('lang.end_date')</th>
                        <th>@lang('lang.created_by')</th>
                        <th>@lang('lang.status')</th>

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
            offer_table = $('#offer_table').DataTable({
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
                    "url": "/admin/offers",
                    "data": function(d) {
                        d.offer_id = $('#offer_id').val();
                    }
                },
                columnDefs: [{
                    "targets": [0, 3],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'products',
                        name: 'products'
                    },
                    {
                        data: 'discount_type',
                        name: 'discount_type'
                    },
                    {
                        data: 'discount_value',
                        name: 'discount_value'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'created_by_name',
                        name: 'users.name'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

        $(document).on('change', '#offer_id', function() {
            offer_table.ajax.reload();
        })
    </script>
@endsection
