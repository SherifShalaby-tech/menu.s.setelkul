// $("#product_class_id").select2();

$("form#product_form").validate();

$(function () {
    $(".datepicker").daterangepicker({
        autoUpdateInput: true,
        locale: {
            cancelLabel: "Clear",
        },
        singleDatePicker: true,
    });

    $(".datepicker").on("apply.daterangepicker", function (ev, picker) {
        $(this).val(picker.startDate.format("MM/DD/YYYY"));
    });

    $(".datepicker").on("cancel.daterangepicker", function (ev, picker) {
        $(this).val("");
    });
});

$(document).on("click", "#submit_btn", function (e) {
    e.preventDefault();
    if ($("form#product_form").valid()) {
        $("form#product_form").submit();
    }
});

$(document).on("submit", "form#quick_add_product_class_form", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        success: function (result) {
            if (result.success) {
                swal("Success", result.msg, "success");
                $(".view_modal").modal("hide");
                var class_id = result.id;
                $.ajax({
                    method: "get",
                    url: "/admin/category/get-dropdown",
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        $("#product_class_id").empty().append(data_html);
                        $("#product_class_id").select2();
                        $("#product_class_id").val(class_id).change();
                    },
                });
            } else {
                swal("Error", result.msg, "error");
            }
        },
    });
});
$(".activeSwitch").bootstrapSwitch();
// $("[name='active']").bootstrapSwitch();

$(document).on("click", ".delete-image", function () {
    let url = $(this).attr("data-href");
    let images_div = $(this).parent(".images_div");

    $.ajax({
        method: "get",
        url: url,
        data: {},
        success: function (result) {
            if (result.success) {
                swal("Success", result.msg, "success");
                $(images_div).remove();
            }
            if (!result.success) {
                swal("Error", result.msg, "error");
            }
        },
    });
});

$(".this_product_have_variant_div").slideUp();
$(document).on("change", "#this_product_have_variant", function () {
    if ($(this).prop("checked")) {
        $(".select2").select2();
        $(".this_product_have_variant_div").slideDown();
        $('input[name=sell_price]').prop('required',false);
    } else {
        $(".select2").select2();
        $(".this_product_have_variant_div").slideUp();
        $('input[name=sell_price]').prop('required',true);
    }
});

$(document).on("click", ".add_row", function () {
    var row_id = parseInt($("#row_id").val());
    $("#row_id").val(row_id + 1);

    $.ajax({
        method: "get",
        url: "/admin/product/get-variation-row?row_id=" + row_id,
        data: {
            name: $("#name").val(),
            purchase_price: $("#purchase_price").val(),
            sell_price: $("#sell_price").val(),
        },
        contentType: "html",
        success: function (result) {
            $("#variation_table tbody").prepend(result);
            $(".select2").select2();

        },
    });
});
$(document).on("click", ".remove_row", function () {
    row_id = $(this).closest("tr").data("row_id");
    $(this).closest("tr").remove();
});
$(document).on("change", "#purchase_price", function () {
    // var row=$(this).parent('td').parent('tr').data('row_id');
    if($('table .variation_row').length==1){
        if($('.variation_name').val()=="Default"){
            let purchase_price = __read_number($(this));
            __write_number($(".default_purchase_price"), purchase_price);
        }
    } 
});
$(document).on("change", "#sell_price", function () {
    // var row=$(this).parent('td').parent('tr').data('row_id');
    if($('table .variation_row').length==1){
        if($('.variation_name').val()=="Default"){
        let sell_price = __read_number($(this));
        __write_number($(".default_sell_price"), sell_price);
        }
    }
});
//////
$('body').delegate('.add_size_row' , 'click', function() {
// $(document).on('click', "", function (e) {
    var row_size_id = parseInt($("#row_size_id").val());
    $.ajax({
        method: "get",
        url: "/admin/product/get-size-row?row_id=" + row_size_id,
        // data: {
        //     name: $("#name").val(),
        //     purchase_price: $("#purchase_price").val(),
        //     sell_price: $("#sell_price").val(),
        // },
        contentType: "html",
        success: function (result) {
            console.log(result)
            $("#size_table tbody").prepend(result);
            $(".select2").select2();
            $(".activeSwitch").bootstrapSwitch();
            $("#row_size_id").val(row_size_id + 1);
        },
    });
});
