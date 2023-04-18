<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Admin\DiningTableController@store'), 'method' => 'post', 'id' => $quick_add ? 'quick_add_new_table_form' : 'dining_table_add_form']) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang('lang.add_new_table')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __('lang.name') . ':*') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
            </div>
            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
            <div class="form-group">
                {!! Form::label('dining_room_id', __('lang.dining_room') . ':*') !!}
                {!! Form::select('dining_room_id', $dining_rooms, false, ['class' => 'select2 form-control', 'placeholder' => __('lang.please_select'), 'required', 'style' => 'width: 100%;']) !!}
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('lang.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $('.select2').select2();
</script>
