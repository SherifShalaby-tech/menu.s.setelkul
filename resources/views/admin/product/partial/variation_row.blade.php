<tr class="row_{{ $row_id }} variation_row" data-row_id="{{ $row_id }}">
    @if (!empty($item))
        {!! Form::hidden('variations[' . $row_id . '][id]', !empty($item) ? $item->id : null, ['class' => 'form-control']) !!}
    @endif
    @php
        $product_name = null;
        $product_sale_price = null;
        $product_purchase_price = null;
        if (!empty($item)) {
            $product_name = $item->name;
            $product_sale_price = $item->sale_price;
            $product_purchase_price = $item->purchase_price;
        } elseif (!empty($name)) {
            $product_name = $name;
            $product_sale_price = $sell_price;
            $product_purchase_price = $purchase_price;
        }
    @endphp
    <td>{!! Form::hidden('name_hidden', $product_name, ['class' => 'form-control name_hidden']) !!}
        {!! Form::hidden('variations[' . $row_id . '][sub_sku]', !empty($item) ? $item->sub_sku : null, ['class' => 'form-control v_sub_sku']) !!}
        {!! Form::text('variations[' . $row_id . '][name]', $product_name, ['class' => 'form-control v_name']) !!}</td>

    <td>{!! Form::select('variations[' . $row_id . '][size_id]', $sizes, !empty($item) ? $item->size_id : false, ['class' => 'form-control select2 v_size', 'data-live-search' => 'true', 'placeholder' => '']) !!}
    </td>
    <td>{!! Form::text('variations[' . $row_id . '][default_purchase_price]', $product_purchase_price, [
    'class' => 'form-control
        default_purchase_price',
    'required',
]) !!}</td>
    <td>{!! Form::text('variations[' . $row_id . '][default_sell_price]', $product_sale_price, ['class' => 'form-control default_sell_price', 'required']) !!}</td>
    <td> <button type="button" class="btn btn-danger btn-xs remove_row mt-2"><i class="fa fa-times"></i></button>
    </td>
</tr>
