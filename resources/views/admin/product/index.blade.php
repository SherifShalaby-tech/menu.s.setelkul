@extends('layouts.admin')

@section('title', __('lang.product_list'))

@section('content_header')
<h1>@lang('lang.product_list')</h1>
@stop

@section('main_content')
@can('product.create')
<a class="btn btn-primary btn-flat mb-3" href="{{action('Admin\ProductController@create')}}"><i class="fas fa-plus"></i>
    @lang('lang.add_product')</a>
@endcan
<x-adminlte-card title="{{__('lang.filter')}}" theme="{{config('adminlte.right_sidebar_theme')}}" theme-mode="outline"
    icon="fas fa-filter">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('product_class_id', __('lang.category'), []) !!}
                {!! Form::select('product_class_id', $categories,
                false, ['class' => 'select2
                form-control', 'data-live-search'=>"true",
                'style' =>'width: 80%' , 'placeholder' => __('lang.please_select'), 'required',
                'required']) !!}
            </div>
        </div>

    </div>
</x-adminlte-card>
<x-adminlte-card title="{{__('lang.product_list')}}" theme="{{config('adminlte.right_sidebar_theme')}}"
    theme-mode="outline" icon="fas fa-file">

    <div class="table-responsive">
        <table id="product_table" class="table" style="width: 100%;">
            <thead>
                <tr>
                    <th>@lang('lang.image')</th>
                    <th>@lang('lang.name')</th>
                    <th>@lang('lang.category')</th>
                    <th>@lang('lang.description')</th>
                    <th>@lang('lang.cost')</th>
                    <th>@lang('lang.sell_price')</th>
                    <th>@lang('lang.discount')</th>
                    <th>@lang('lang.discount_start_date')</th>
                    <th>@lang('lang.discount_end_date')</th>
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
    $(document).ready( function(){
        product_table = $('#product_table').DataTable({
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
                "url": "/admin/product",
                "data": function ( d ) {
                    d.product_class_id = $('#product_class_id').val();
                }
            },
            columnDefs: [ {
                "targets": [0, 10],
                "orderable": false,
                "searchable": false
            } ],
            columns: [
                { data: 'image', name: 'image'  },
                { data: 'name', name: 'products.name'  },
                { data: 'category', name: 'product_classes.name'  },
                { data: 'product_details', name: 'product_details'  },
                { data: 'purchase_price', name: 'purchase_price'  },
                { data: 'sell_price', name: 'sell_price'  },
                { data: 'discount', name: 'discount'  },
                { data: 'discount_start_date', name: 'discount_start_date'  },
                { data: 'discount_end_date', name: 'discount_end_date'  },
                { data: 'active', name: 'active'  },

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
        product_table.ajax.reload();
    })
</script>
@endsection
