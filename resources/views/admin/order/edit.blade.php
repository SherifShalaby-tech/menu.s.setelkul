<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Admin\OrderController@update', $order->id), 'method' => 'put', 'id' => 'product_class_add_form', 'files' => true]) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.order_no' ): {{ $order->id }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('customer_name', __('lang.customer_name') . ':*') !!}
                        {!! Form::text('customer_name', $order->customer_name, ['class' => 'form-control', 'placeholder' => __('lang.customer_name'), 'required']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('phone_number', __('lang.phone_number') . ':*') !!}
                        {!! Form::text('phone_number', $order->phone_number, ['class' => 'form-control', 'placeholder' => __('lang.phone_number'), 'required']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('notes', __('lang.notes') . ':') !!}
                        {!! Form::text('sales_note', $order->sales_note, ['class' => 'form-control', 'placeholder' => __('lang.notes')]) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('payment_type', __('lang.payment_type') . ':*') !!}
                        {!! Form::select('payment_type', ['pay_online' => __('lang.pay_online'), 'cash_on_delivery' => __('lang.cash_on_delivery')], $order->payment_type, ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('delivery_type', __('lang.delivery_type') . ':*') !!}
                        {!! Form::select('delivery_type', ['i_will_pick_it_up_my_self' => __('lang.i_will_pick_it_up_my_self'), 'home_delivery' => __('lang.home_delivery')], $order->delivery_type, ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('status', __('lang.status') . ':*') !!}
                        {!! Form::select('status', ['pending' => __('lang.pending'), 'completed' => __('lang.completed'), 'canceled' => __('lang.canceled')], $order->status, ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('delivery_status', __('lang.delivery_status') . ':*') !!}
                        {!! Form::select('delivery_status', ['pending' => __('lang.pending'), 'delivered' => __('lang.delivered')], $order->delivery_status, ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'lang.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>

</script>
