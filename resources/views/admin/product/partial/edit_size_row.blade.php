
<tr class="row_{{ $row_id }}" data-row_id="{{ $row_id }}">
    @if (!empty($item))
        {!! Form::hidden('sizes[' . $row_id . '][id]', !empty($item) ? $item->id : null, ['class' => 'form-control']) !!}
        {{-- {!! Form::hidden('variations[' . $row_id . '][pos_model_id]', !empty($item) ? $item->pos_model_id : null, ['class' => 'form-control']) !!} --}}
    @endif
    <td style = 'width: 30%'>
        <div class="input-group my-group ">
            {!! Form::select('sizes[' . $row_id . '][size_id]', $sizes, !empty($item) ? $item->pivot->size_id : false,['class' => 'form-control select2','style' => 'width: 80%','data-live-search' => 'true', 'placeholder' => __('lang.size')]) !!}
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
        {!! Form::text('sizes[' . $row_id . '][purchase_price]', @num_format($item->pivot->purchase_price), ['class' => 'form-control','id'=>'purchase_price', 'placeholder' => session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket' ? __('lang.purchase_price') : __('lang.cost')]) !!}
    </td>
    <td>
        {!! Form::text('sizes[' . $row_id . '][sell_price]', @num_format($item->pivot->sell_price), ['class' => 'form-control','id'=>'sell_price', 'placeholder' => __('lang.sell_price'), 'required']) !!}
    </td>
    {{-- <td>
        {!! Form::checkbox('sizes[' . $row_id . '][active]',  $item->pivot->active ? true : false, ['class']) !!}
    </td>  --}}
    <td> <button type="button" class="btn btn-danger btn-xs remove_row mt-2"><i class="fa fa-times"></i></button>
    </td>
</tr>
