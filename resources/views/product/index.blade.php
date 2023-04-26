@extends('layouts.app')

@section('content')
@include('layouts.partials.category-header')

<div class="container mx-auto mt-14">

    <div class="w-full mx-auto p-4">
        <div class="grid xs:grid-cols-3 md:grid-cols-4 xs:gap-2 md:gap-16 md:mt-12 xs:mt-6">
            @foreach ($products as $product)
            @include('home.partial.product_card', ['product' => $product])
            @endforeach
        </div>
    </div>


    @include('layouts.partials.cart-row')
</div>



@endsection

@section('javascript')
<script>
    // $(document).on('click', '.product_card', function(e){
    //     if(!$(e.target).is('i.cart_icon') && !$(e.target).is('button.cart_button *')){
    //         window.location.href = $(this).data('href');
    //     }
    // })
    $(document).on('click', '.cart_button', function(){
        var sizeId=$(this).closest('.productCard').find('input[name=size]').val();
        var product_id=$(this).data('product_id');
        $.ajax({
            type: "GET",
            url: '/cart/add-to-cart/' + $(this).data('product_id')+'/'+sizeId,
            // data: "data",
            dataType: "json",
            success: function (response) {
                if (response.status.success) {
                    swal.fire("", response.status.msg, "success");
                }else{
                    swal.fire("@lang('lang.error')!", response.status.msg, "error");
                }
            $('.cart_items').load(document.URL +  ' .cart_items');

            }

        });


        // window.location.href = base_path + '/cart/add-to-cart/' + $(this).data('product_id')+'/'+sizeId;
    })
    // })
    $(document).on('click', '.changeSize', function(e){
        e.preventDefault();
        var price=$(this).data('price');
        var size_id=$(this).data('size_id');
        $(this).parent().parent().parent().siblings().find('.sell-price').text(price);
        $(this).closest('.productCard').children('input[name=size]').val(size_id);
        var size=$(this).data('size_name');
        var s=$(this).parent().parent().parent().siblings().find('.size-menu').text(size);
        // __write_number(size,)
        console.log($('input[name=size]').val())
        // var size_id=$(this).data('size_id');
    });
</script>

@endsection
