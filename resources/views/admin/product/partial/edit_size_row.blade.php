
<tr class="row_{{ $row_id }}" data-row_id="{{ $row_id }}">
    @if (!empty($item))
        {!! Form::hidden('sizes[' . $row_id . '][id]', !empty($item) ? $item->id : null, ['class' => 'form-control']) !!}
        {{-- {!! Form::hidden('variations[' . $row_id . '][pos_model_id]', !empty($item) ? $item->pos_model_id : null, ['class' => 'form-control']) !!} --}}
    @endif
    <td style = 'width: 15%'>
        <div class="input-group my-group ">
            {!! Form::select('sizes[' . $row_id . '][size_id]', $sizes, !empty($item) ? $item->pivot->size_id : false,['class' => 'form-control select2','style' => 'width: 60%','data-live-search' => 'true', 'placeholder' => __('lang.size')]) !!}
            <span class="input-group-btn">
                @can('settings.size.create')
                    <button class="btn-modal btn btn-default bg-white btn-flat"
                        data-href="{{ action('Admin\SizeController@create') }}"
                        data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                @endcan
            </span>
        </div>
    </td>
    <td> 
        {!! Form::text('sizes[' . $row_id . '][purchase_price]', @num_format($item->pivot->purchase_price), ['class' => 'form-control', 'placeholder' => session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket' ? __('lang.purchase_price') : __('lang.cost')]) !!}
    </td>
    <td>
        {!! Form::text('sizes[' . $row_id . '][sell_price]', @num_format($item->pivot->sell_price), ['class' => 'form-control', 'placeholder' => __('lang.sell_price'), 'required']) !!}
    </td>

    <td>
        {!! Form::select('sizes[' . $row_id . '][discount_type]', ['fixed' => __('lang.fixed'), 'percentage' => __('lang.percentage')], $item->pivot->discount_type, ['class' => 'selectpicker form-control', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select')]) !!}
    </td>
    <td>
        {!! Form::text('sizes[' . $row_id . '][discount]', @num_format($item->pivot->discount), ['class' => 'form-control', 'placeholder' => __('lang.discount')]) !!}
    </td>
    <td>
        {!! Form::text('sizes[' . $row_id . '][discount_start_date]', !empty($item->pivot->discount_start_date) ? @format_date($item->discount_start_date) : null, ['class' => 'form-control datepicker', 'placeholder' => __('lang.discount_start_date')]) !!}
    </td>
    <td>
        {!! Form::text('sizes[' . $row_id . '][discount_end_date]', !empty($item->pivot->discount_end_date) ? @format_date($item->discount_end_date) : null, ['class' => 'form-control datepicker', 'placeholder' => __('lang.discount_end_date')]) !!}
    </td>
    <td> <button type="button" class="btn btn-danger btn-xs remove_row mt-2"><i class="fa fa-times"></i></button>
    </td>
</tr>
