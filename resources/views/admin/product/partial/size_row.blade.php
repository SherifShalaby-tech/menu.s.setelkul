<tr class="row_{{ $row_id }} size_row" data-row_id="{{ $row_id }}">
    @if (!empty($item))
        {!! Form::hidden('sizes[' . $row_id . '][id]', !empty($item) ? $item->id : null, ['class' => 'form-control']) !!}
    @endif
    <td style = 'width: 15%'>
        <div class="input-group my-group ">
            {!! Form::select('sizes[' . $row_id . '][size_id]', $sizes,null,['class' => 'form-control select2','data-live-search' => 'true', 'placeholder' => __('lang.size')]) !!}
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
        {!! Form::text('sizes[' . $row_id . '][purchase_price]', null, ['class' => 'form-control', 'placeholder' => session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket' ? __('lang.purchase_price') : __('lang.cost')]) !!}
    </td>
    <td>
        {!! Form::text('sizes[' . $row_id . '][sell_price]', null, ['class' => 'form-control', 'placeholder' => __('lang.sell_price'), 'required']) !!}
    </td>
    <td> <button type="button" class="btn btn-danger btn-xs remove_row mt-2"><i class="fa fa-times"></i></button>
</tr>

