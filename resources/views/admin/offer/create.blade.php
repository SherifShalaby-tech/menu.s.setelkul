@extends('layouts.admin')

@section('title', __('lang.create_offer'))

@section('content_header')
    <h1>@lang('lang.create_offer')</h1>
@stop

@section('main_content')
    {!! Form::open(['url' => action('Admin\OfferController@store'), 'method' => 'post', 'files' => true, 'id' => 'offer_form']) !!}
    <x-adminlte-card title="{{ __('lang.create_offer') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('name', __('lang.name') . ':*') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('product_class_id', __('lang.category')) !!}
                    {!! Form::select('product_class_id', $categories, false, ['class' => 'select2 form-control', 'id' => 'product_class_id', 'placeholder' => __('lang.please_select')]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('product_ids', __('lang.products') . ':*') !!}
                    {!! Form::select('product_ids[]', $products, false, ['class' => 'select2 form-control', 'multiple', 'required', 'id' => 'product_ids']) !!}
                </div>
            </div>

            <div class="col-md-4 hide">
                <div class="form-group">
                    {!! Form::label('type', __('lang.type') . ':*') !!}
                    {!! Form::select('type', ['item_discount' => __('lang.item_discount'), 'package_promotion' => __('lang.package_promotion')], 'item_discount', ['class' => ' form-control', 'data-live-search' => 'true', 'placeholder' => __('lang.please_select'), 'required']) !!}
                </div>
            </div>

            <div class="col-md-3 ">
                <div class="form-group">
                    {!! Form::label('discount_type', __('lang.discount_type') . ':*') !!}
                    {!! Form::select('discount_type', ['fixed' => 'Fixed', 'percentage' => 'Percentage'], 'fixed', ['class' => 'form-control selecpicker', 'required', 'placeholder' => __('lang.please_select')]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('discount_value', __('lang.discount') . ':*') !!}
                    {!! Form::text('discount_value', 0, ['class' => 'form-control', 'required']) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('start_date', __('lang.start_date') . ':') !!}
                    {!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'required']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('end_date', __('lang.end_date') . ':') !!}
                    {!! Form::text('end_date', null, ['class' => 'form-control datepicker', 'required']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('status', __('lang.status') . ':', []) !!} <br>
                {!! Form::checkbox('status', 1, true, ['class' => 'bootstrapswitch']) !!}
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('description', __('lang.description') . ':', []) !!}
                    @php
                        $config = config('adminlte.editor');
                    @endphp
                    <x-adminlte-text-editor name="description" :config="$config" />

                </div>
            </div>
        </div>



    </x-adminlte-card>

    <div class="col-md-12">
        <button type="submit" class="btn btn-primary pull-right" id="submit_btn">@lang('lang.save')</button>
    </div>
    {!! Form::close() !!}
@stop
@section('javascript')
    <script>
        $(document).on('change', '#product_class_id', function() {
            var product_class_id = $(this).val();
            $.ajax({
                url: "/admin/offers/get-product-dropdown-by-category/" + product_class_id,
                type: "GET",
                success: function(data) {
                    $('#product_ids').html(data);
                    $('.select2').select2();
                }
            });
        });
        $(".bootstrapswitch").bootstrapSwitch();


        $(function() {
            $(".datepicker").daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: "Clear",
                },
                singleDatePicker: true,
            });

            $(".datepicker").on("apply.daterangepicker", function(ev, picker) {
                $(this).val(picker.startDate.format("MM/DD/YYYY"));
            });

            $(".datepicker").on("cancel.daterangepicker", function(ev, picker) {
                $(this).val("");
            });
        });
        $('#product_class_id').select2()
    </script>
@endsection
