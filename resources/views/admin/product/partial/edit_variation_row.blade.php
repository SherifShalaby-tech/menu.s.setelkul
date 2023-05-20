<tr class="row_{{ $row_id }}" data-row_id="{{ $row_id }}">
    @if (!empty($item))
        {!! Form::hidden('variations[' . $row_id . '][id]', !empty($item) ? $item->id : null, ['class' => 'form-control']) !!}
        {!! Form::hidden('variations[' . $row_id . '][pos_model_id]', !empty($item) ? $item->pos_model_id : null, ['class' => 'form-control']) !!}
    @endif
    <td>{!! Form::text('variations[' . $row_id . '][name]', !empty($item) ? $item->name : null, ['class' => 'form-control']) !!}{!! Form::hidden('variations[' . $row_id . '][sub_sku]', !empty($item) ? $item->sub_sku : null, ['class' => 'form-control']) !!}</td>
    <td>{!! Form::select('variations[' . $row_id . '][size_id]', $sizes, !empty($item) ? $item->size_id : false, ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder' => __('lang.please_select')]) !!}
    </td>
    <td>{!! Form::text('variations[' . $row_id . '][default_purchase_price]', !empty($item) ? @num_format($item->default_purchase_price) : null, ['class' => 'form-control default_purchase_price']) !!}</td>
    <td>{!! Form::text('variations[' . $row_id . '][default_sell_price]', !empty($item) ? @num_format($item->default_sell_price) : null, ['class' => 'form-control default_sell_price', 'required']) !!}</td>
    <td> <button type="button" class="btn btn-danger btn-xs remove_row mt-2"><i class="fa fa-times"></i></button>
    </td>
</tr>
