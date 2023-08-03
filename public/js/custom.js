$(document).on("mouseleave", ".cart_button", function () {
    $(this).find(".cart_icon").removeClass("rotate360");
});
$(document).on("mouseover", ".cart_button", function () {
    $(this).find(".cart_icon").addClass("rotate360");
});
$(document).on("mouseenter", ".product_card", function () {
    console.log("enter");
    $(this).find(".cart_button").addClass("slideup");
    $(this).find(".cart_button").css("opacity", "1");
});
$(document).on("mouseleave", ".product_card", function () {
    console.log("leave");
    $(this).find(".cart_button").removeClass("slideup");
    $(this).find(".cart_button").css("opacity", "0");
});
$(document).on('click', '.cart_button', function(){

    var variationId=$(this).closest('.productCard').find('input[name=variation]').val();
    $.ajax({
        type: "GET",
        url: '/cart/add-to-cart/' + variationId,
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
    alert(33)
    e.preventDefault();
    var price=$(this).data('price');
    var size_id=$(this).data('size_id');
    var variation_id=$(this).data('variation_id');
    $(this).parent().parent().parent().siblings().find('.sell-price').text(price);
    $(this).closest('.productCard').children('input[name=size]').val(size_id);
    var size=$(this).data('size_name');
    var s=$(this).parent().parent().parent().siblings().find('.size-menu').text(size);
    $(this).closest('.productCard').children('input[name=variation]').val(variation_id);
    // __write_number(size,)
    console.log($('input[name=size]').val())
    // var size_id=$(this).data('size_id');
});

        $(document).on('click', '#send_the_order', function(e) {
            e.preventDefault();
            $('input[type=text]').blur();
            if ($('#cart_form').valid()) {
                $('#cart_form').submit();
            }
        });
        $(document).on('change', '.extra_checkbox', function() {
            let product_id = $(this).val();
            if ($(this).prop('checked') == true) {
                window.location.href = base_path + "/cart/add-to-cart-extra/" + product_id;
            } else {
                window.location.href = base_path + "/cart/remove-product/" + product_id;
            }
        })

        $(document).on('change', '.variation_radio', function() {

            if ($(this).prop('checked') == true) {
                let product_id = $(this).data('id');
                let variation_id = $(this).val();

                window.location.href = base_path + "/cart/update-product-variation/" + product_id + "/" +
                    variation_id;
            }
        })
        $(document).on('change', '.quantity', function() {

            let product_id = $(this).data('id');
            let quantity = $(this).val();

            $.ajax({
                type: "GET",
                url: "/cart/update-product-quantity/" + product_id + "/" +quantity,
                success: function (response) {
                    $('.order-total-price').text(response.total);
                }
            });

        })


        $(document).on('click', '.plus', function() {
            let qty_row = $(this).closest('.qty_row')
            let quantity = __read_number($(qty_row).find('.quantity'));
            $(qty_row).find('.quantity').val(quantity + 1);
            $(qty_row).find('.quantity').change();
        })
        $(document).on('click', '.minus', function() {
            let qty_row = $(this).closest('.qty_row')
            let quantity = __read_number($(qty_row).find('.quantity'));
            if (quantity > 1) {
                $(qty_row).find('.quantity').val(quantity - 1);
                $(qty_row).find('.quantity').change();
            }
        })

        $(document).on('change', '#order', function() {
            if ($(this).prop('checked') == true) {
                $('.order_now').removeClass('text-dark');
              //  $('.order_now').addClass('text-lightgrey');

                $('.order_later').addClass('text-dark');
             //   $('.order_later').removeClass('text-lightgrey');
                $('.order_later_div').removeClass('hidden');
            } else {
                $('.order_now').addClass('text-dark');
              //  $('.order_now').removeClass('text-lightgrey');

                $('.order_later').removeClass('text-dark');
             //   $('.order_later').addClass('text-lightgrey');
                $('.order_later_div').addClass('hidden');
            }
        })

        $(document).on('change', 'input[name="delivery_type"]', function() {
            if ($(this).val() == 'dining_in') {
                $('.inside_restaurant_div').removeClass('hidden');
                $('#table_no').attr('required', true);
            } else {
                $('.inside_restaurant_div').addClass('hidden');
                $('#table_no').attr('required', false);
            }
        })

        $(document).on('change', '#delivery', function() {
            if ($(this).prop('checked') == true) {
                $('.i_will_pick').removeClass('text-dark');
               // $('.i_will_pick').addClass('text-lightgrey');

                $('.home_delivery').addClass('text-dark');
               // $('.home_delivery').removeClass('text-lightgrey');
            } else {
                $('.i_will_pick').addClass('text-dark');
              //  $('.i_will_pick').removeClass('text-lightgrey');

                $('.home_delivery').removeClass('text-dark');
               // $('.home_delivery').addClass('text-lightgrey');
            }
        })

        $(document).on('change', '#payment_type', function() {
            if ($(this).prop('checked') == true) {
                $('.pay_online').removeClass('text-dark');
              //  $('.pay_online').addClass('text-lightgrey');

                $('.cash_on_delivery').addClass('text-dark');
               // $('.cash_on_delivery').removeClass('text-lightgrey');
            } else {
                $('.pay_online').addClass('text-dark');
              //  $('.pay_online').removeClass('text-lightgrey');

                $('.cash_on_delivery').removeClass('text-dark');
              //  $('.cash_on_delivery').addClass('text-lightgrey');
            }
        })
