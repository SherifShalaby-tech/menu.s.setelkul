<a href="{{ action('ProductController@show', $offer['product_id']) }}" class="text-center w-full">
    <div class="flex-col h-78 mx-auto rounded-tl-lg rounded-tr-lg border-2 border-lightblue bg-white">
        <div class="w-full xl:h-48 lg:h-30 md:h-20 sm:h-20 xs:h-16 rounded-tl-lg rounded-tr-lg">
            <img src="{{ !empty($offer['image']) ? $offer['image'] : asset('uploads/' . session('logo')) }}"
                class="mx-auto xl:h-48 lg:h-30 md:h-20 sm:h-20 xs:h-16 rounded-tr-lg rounded-tl-lg w-full" alt="offer">
        </div>
        <div class="h-10 w-full mx-auto text-center px-4 sm:px-1">
            <h3 class="md:text-sm xs:text-tiny font-semibold text-dark sm:text-sm">{{ $offer['product_name'] }}</h3>
            {{-- <h3 class="md:text-base xs:text-xtiny font-semibold text-dark sm:text-sm">{!! Str::limit($offer['product_details'], 20) !!}</h3> --}}
        </div>
        <div class="flex flex-row px-4">
            <div class="flex-1 md:text-base xs:text-tiny font-semibold pt-1 text-left">
                {{ @num_format($offer['discount_price']) }}TL</div>
            <div class="flex-1 md:text-base xs:text-tiny font-semibold pt-1 text-right"><span
                    class="strikethrough">{{ @num_format($offer['sell_price']) }}TL</span></div>
        </div>
        <div class=" text-center w-full">
            <h3 class="md:text-lg xs:text-tiny py-4 font-bold">@lang('lang.order_now') <i class="fa fa-angle-right"></i>
            </h3>
        </div>
    </div>
</a>
