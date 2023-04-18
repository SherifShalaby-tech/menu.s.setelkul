<div class="w-full mb-4">
    <div class="w-full  shadow-lg pb-full rounded-xl bg-center bg-no-repeat bg-cover relative border-2 border-dark product_card"
        style="background-image: url('{{ !empty($product->getFirstMediaUrl('product'))? $product->getFirstMediaUrl('product'): asset('uploads/' . session('logo')) }}')">

        <div class="flex  w-full text-center">
            <div class="absolute bottom-0 mx-auto w-full">
                <button data-product_id="{{ $product->id }}" type="button"
                    class="bg-white text-red hover:bg-red hover:text-white transition-all duration-500 md:w-12 md:h-12 xs:w-8 xs:h-8 rounded-full mb-16 opacity-0 cart_button">
                    <i class="fa md:text-xl xs:text-sm fa-cart-plus cart_icon"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="flex">
        <a href="{{ action('ProductController@show', $product->id) }}"
            class=" w-full text-white text-xs text-center bg-black opacity-70 rounded-xl py-4 mt-2">
            <p class="md:text-sm xs:text-tiny font-semibold text-white py-0">{{ Str::limit($product->name, 15) }}</p>
            <p class="md:text-sm xs:text-tiny font-semibold text-white py-0">
                {{ session('currency')['code'] }} {{ @num_format($product->sell_price - $product->discount_value) }}
            </p>
        </a>
    </div>
</div>
