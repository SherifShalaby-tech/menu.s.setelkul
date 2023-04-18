<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $product->name }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <label style="font-weight: bold;" for="">@lang('lang.sku'): </label>
                            {{ $product->sku }} <br>
                            <label style="font-weight: bold;" for="">@lang('lang.category'): </label>
                            @if (!empty($product->category))
                                {{ $product->category->name }}
                            @endif <br>
                            <label style="font-weight: bold;" for="">@lang('lang.selling_price'): </label>
                            {{ @num_format($product->sell_price) }}<br>
                            <label style="font-weight: bold;" for="">@lang('lang.purchase_price'): </label>
                            {{ @num_format($product->purchase_price) }}<br>
                            <label style="font-weight: bold;" for="">@lang('lang.description'): </label>
                            {!! $product->product_details !!}<br>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="col-sm-12 col-md-12 invoice-col">
                        <div class="thumbnail">
                            <img class="img-fluid"
                                src="@if (!empty($product->getFirstMediaUrl('product'))) {{ $product->getFirstMediaUrl('product') }}@else{{ asset('/uploads/' . session('logo')) }} @endif"
                                alt="Product Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $("[name='status']").bootstrapSwitch();
</script>
