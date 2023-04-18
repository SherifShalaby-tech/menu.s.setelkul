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
