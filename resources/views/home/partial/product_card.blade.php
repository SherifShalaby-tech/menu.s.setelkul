@php
$variation_products='';
if($product->variations->where('name','!=','Default')->count()>0){
    $variation_products=$product->variations->where('name','!=','Default');
}else{
    $variation_products=$product->variations->where('name','Default');
}
@endphp
<div class="w-full mb-4 productCard" >
    @foreach($variation_products as $size)
        <input type="hidden" value="{{$size->size_id}}" name="size"/>
        <input type="hidden" value="{{$size->id}}" name="variation"/>
        @break
    @endforeach
    {{-- {{$product->getFirstMediaUrl('product')}} --}}
    <div class="relative w-full bg-center bg-no-repeat bg-cover border-2 shadow-lg pb-full rounded-xl border-dark product_card"
        style="background-image: url('{{ !empty($product->getFirstMediaUrl('product'))? images_asset($product->getFirstMediaUrl('product')): images_asset(asset('uploads/' . session('logo'))) }}')">

        <div class="flex w-full text-center">
            <div class="absolute bottom-0 w-full mx-auto">
                <button data-product_id="{{ $product->id }}" type="button"
                    class="mb-16 transition-all duration-500 bg-white rounded-full opacity-0 text-red hover:bg-red hover:text-white md:w-12 md:h-12 xs:w-8 xs:h-8 cart_button">
                    <i class="fa md:text-xl xs:text-sm fa-cart-plus cart_icon" style="pointer-events:none;"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="flex" style="aspect-ratio: 1/1.2;">
        <div
            class="w-full mt-1 text-center text-white bg-black text-s opacity-70 rounded-xl">
            <a href="{{ action('ProductController@show', $product->id) }}">
                <p class="py-0 text-white md:text-sm xs:text-tiny">{{ Str::limit($product->name, 25) }}</p>
            </a>
            <p class="h-4 px-2 py-0 text-white product-details md:text-sm xs:text-tiny sm:flex sm:justify-between">

                    @foreach($variation_products as $s)
                        <span>
                            {{ session('currency')['code'] }}
                            <span class="sell-price">
                                {{ @num_format($s->default_sell_price - $product->discount_value) }}
                            </span>

                        </span>
                        <button id="dropdownMenuIconHorizontalButton" data-dropdown-toggle="dropdownDotsHorizontal{{$product->id}}" class="inline-flex items-center text-center text-white bg-gray-900 rounded-lg size-btn hover:bg-gray-600 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600" type="button">
                            @if($s->size_id!==null)
                             <span class="size-menu">
                                {{$s->size->name}}</span>
                                &nbsp;

                                <span>
                                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                </span>
                                @endif
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdownDotsHorizontal{{$product->id}}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconHorizontalButton">
                                @foreach($variation_products as $size)
                                    <li>
                                    <a data-size_id="{{$size->size_id}}" data-variation_id="{{$size->id}}"  data-size_name="{{$size->size->name}}" data-price="{{ @num_format($size->default_sell_price - $product->discount_value) }}"  class="block px-4 py-2 changeSize hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{$size->size->name}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @break
                    @endforeach

                {{-- {{ session('currency')['code'] }} {{ @num_format($product->sell_price - $product->discount_value) }} --}}
            </p>
        </div>
    </div>
</div>
