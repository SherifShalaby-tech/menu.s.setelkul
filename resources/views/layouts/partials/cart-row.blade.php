@php
$user_id = request()
    ->session()
    ->get('user_id');
$cart_count = 0;
if (!empty($user_id)) {
    $cart_collection = Cart::session($user_id)->getContent();
    $cart_count = $cart_collection->count();
}
@endphp
<div class="flex pb-8 mt-32">
    <div class="flex-1">
    </div>
     <div class="flex-1 mt-6 text-center cart_items">
        <a href="{{ action('CartController@view') }}" class="text-center ">
            <button class="relative text-2xl font-semibold text-white rounded-full bg-red"
                style="height: 70px; width: 70px;">
                <span class="absolute p-1 text-sm text-white item_count text-dark left-8 top-5" style="
                padding-inline: 10px; background-color:rgba(0,0,0,.5); border-radius:5px; transform:translatey(-18px); color:white; "
                    style="margin-top: 2px;">{{ $cart_count }}</span><i
                    class="fa fa-lg fa-shopping-cart "></i></button>
        </a>
    </div>
    <div class="flex-1 text-right">
        <button class="px-3 py-2 mt-10 font-semibold text-white rounded-full bg-red" id="goToTop" onclick="topFunction()"><i
                class="fa fa-arrow-up"></i></button>
    </div>
</div>
