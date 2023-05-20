@extends('layouts.app')
@php
$locale_direction = LaravelLocalization::getCurrentLocaleDirection();
@endphp
@section('content')
    @include('layouts.partials.cart-header')
    <div class="container mx-auto mt-14">
        <div class="flex mt-40 bg-red">
            <div class="relative overflow-hidden w-full h-48 ">
                <img src="{{ asset('images/cart-top.png') }}" class="mx-auto w-full h-full object-cover " alt="cart-top">
                <div
                    class="absolute w-full py-2.5 px-5 bottom-0 inset-x-0 text-white text-xs text-center leading-4 bg-gradient-to-t from-black">
                    <p class="text-tiny font-semibold text-white py-10"></p>
                </div>

            </div>
        </div>
    </div>

    <div class="container mx-auto py-4">
        {!! Form::open(['url' => action('OrderController@store'), 'method' => 'pos', 'id' => 'cart_form']) !!}
        <div class="flex lg:flex-row xs:flex-col py-4 bg-white opacity-70 ">
            <div class="flex-1 xl:px-16 lg:px-2 md:px-4 xs:px-4 flow-root">
                <div class="form-group">
                    <label
                        class="font-semibold text-base text-dark  @if ($locale_direction == 'rtl') float-right @else float-left @endif"
                        for="sales_note">@lang('lang.notes')</label>
                    <textarea class="border-b border-dark rounded-lg w-full px-4" name="sales_note" id="sales_note" rows="3"></textarea>
                </div>
                <div class="flex flex-row py-2 flow-root">
                    <label
                        class="font-semibold text-base text-dark pr-2 pt-1 @if ($locale_direction == 'rtl') float-right @else float-left @endif"
                        for="customer_name">@lang('lang.name')</label>
                    <input type="text" name="customer_name" required
                        class="border-b border-dark rounded-lg w-full px-4 w-3/5 @if ($locale_direction == 'rtl') float-left @else float-right @endif "
                        value="">
                </div>
                <div class="flex flex-row py-2 flow-root">
                    <label
                        class="font-semibold text-base text-dark pr-2 pt-1 @if ($locale_direction == 'rtl') float-right @else float-left @endif"
                        for="phone_number">@lang('lang.phone_number')</label>
                    <input type="text" name="phone_number" required
                        class="border-b border-dark rounded-lg w-full px-4 w-3/5 @if ($locale_direction == 'rtl') float-left @else float-right @endif "
                        value="">
                </div>
                <div class="flex flex-row py-2 flow-root">
                    <label
                        class="font-semibold text-base text-dark pr-2 pt-1 @if ($locale_direction == 'rtl') float-right @else float-left @endif"
                        for="address">@lang('lang.address')</label>
                    <input type="text" name="address"
                        class="border-b border-dark rounded-lg w-full px-4 w-3/5 @if ($locale_direction == 'rtl') float-left @else float-right @endif "
                        value="">
                </div>

                <div class="flex py-2 justify-center">
                    <div class="flex-1">
                        <label class="order_now font-semibold text-base text-dark pr-2 pt-1 float-right"
                            for="order_now">@lang('lang.order_now')</label>
                    </div>
                    <div class="flex w-16 justify-center">
                        <div class="mt-1">
                            <label for="order" class="flex relative items-center mb-4 cursor-pointer">
                                <input type="checkbox" name="order_type" id="order" value="1" class="sr-only">
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full border border-red toggle-bg dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="order_later font-semibold text-base pr-2 pt-1 float-left"
                            for="order_later">@lang('lang.order_later')</label>
                    </div>
                </div>
                <div class="flex flex-row justify-center order_later_div hidden ">
                    <img class="md:h-8 md:w-12 xs:h-4 xs:w-8 px-2 md:mt-1 xs:mt-4"
                        src="{{ asset('images/calender-icon.png') }}" alt="">
                    <select id="month" name="month"
                        class="font-w w-32 mx-2 bg-gray-50 border border-gray-300 text-gray-900 md:text-base xs:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:p-2.5 xs:p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @foreach ($month_array as $key => $month)
                            <option @if ($key == date('m')) selected @endif value="{{ $key }}">
                                {{ $month }}</option>
                        @endforeach
                    </select>
                    <select id="day" name="day"
                        class="font-w w-32 mx-2 bg-gray-50 border border-gray-300 text-gray-900 md:text-base xs:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:p-2.5 xs:p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @foreach (range(1, 31, 1) as $i)
                            <option @if ($i == date('d')) selected @endif value="{{ $i }}">
                                {{ $i }}</option>
                        @endforeach
                    </select>
                    <img class="md:h-8 md:w-12 xs:h-4 xs:w-8 px-2 md:mt-1 xs:mt-4"
                        src="{{ asset('images/time-icon.png') }}" alt="">

                    <input type="time" name="time" id="base-input" value="{{ date('H:i') }}"
                        class="font-w w-32 bg-gray-50 border border-gray-300 text-gray-900 md:text-base xs:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 px-0 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                </div>
                <div class="flex flex-row py-2  justify-center">
                    <div class="flex-1">
                        <label class="pay_online font-semibold text-base  pr-2 pt-1 float-right"
                            for="pay_online">@lang('lang.pay_online')</label>
                    </div>
                    <div class="flex w-16 justify-center">
                        <div class="mt-1">
                            <label for="payment_type" class="flex relative items-center mb-4 cursor-pointer">
                                <input type="checkbox" id="payment_type" name="payment_type" checked value="1"
                                    class="sr-only">
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full border border-red toggle-bg dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="cash_on_delivery font-semibold text-base text-dark pr-2 pt-1 float-left"
                            for="cash_on_delivery">@lang('lang.cash_on_delivery')</label>
                    </div>
                </div>

                <div class="flex flex-row py-2 justify-center items-center">
                    <div class="flex-1 text-center">
                        <input type="radio" name="delivery_type" value="i_will_pick_it_up_my_self" required
                            class="w-4 h-4 border-red focus:ring-2 focus:ring-red dark:focus:ring-red dark:focus:bg-red dark:bg-gray-700 dark:border-red"
                            aria-labelledby="radio" aria-describedby="radio">
                        <label class="i_will_pick font-semibold md:text-base xs:text-xs text-dark pl-2"
                            for="i_will_pick_it_up_my_self">@lang('lang.i_will_pick_it_up_my_self')</label>
                    </div>
                    <div class="flex-1 text-center">
                        <input type="radio" name="delivery_type" value="home_delivery" checked required
                            class="w-4 h-4 border-red focus:ring-2 focus:ring-red dark:focus:ring-red dark:focus:bg-red dark:bg-gray-700 dark:border-red"
                            aria-labelledby="radio" aria-describedby="radio">
                        <label class="i_will_pick font-semibold md:text-base xs:text-xs text-dark pl-2"
                            for="home_delivery">@lang('lang.home_delivery')</label>
                    </div>
                    <div class="flex-1 text-center">
                        <input type="radio" name="delivery_type" value="dining_in" required
                            class="w-4 h-4 border-red focus:ring-2 focus:ring-red dark:focus:ring-red dark:focus:bg-red dark:bg-gray-700 dark:border-red"
                            aria-labelledby="radio" aria-describedby="radio">
                        <label class="i_will_pick font-semibold md:text-base xs:text-xs text-dark pl-2"
                            for="dining_in">@lang('lang.dining_in')</label>
                    </div>
                </div>

                <div class="flex flex-row justify-center inside_restaurant_div hidden ">
                    <label class="font-semibold text-base text-dark pr-2 pt-1 float-left"
                        for="table_no">@lang('lang.table_no')</label>

                    <select id="table_no" name="table_no"
                        class="w-1/4 mx-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">@lang('lang.please_select')</option>
                        @foreach ($dining_tables as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @if(env('ENABLE_POS_SYNC'))
                <div class="flex flex-row justify-center mt-4">
                    <select id="store_id" name="store_id" required
                        class="w-1/2 mx-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                           @if(count($stores)==1)
                        @foreach ($stores as $id => $store)
                            <option value="{{ $id }}">{{ $store }}</option>
                        @endforeach
                        @else
                        <option selected value="">@lang('lang.enter_restaurant_store')</option>
                        @foreach ($stores as $id => $store)
                            <option value="{{ $id }}">{{ $store }}</option>
                        @endforeach
                        @endif
                    </select>

                </div>
                @endif

            </div>
            
            <div class="flex-1 xl:px-16 lg:px-2 md:px-4 xs:px-4 xs:mt-8 xs:border-t-2">
                @foreach ($cart_content as $item)
                    @if ($item->attributes->extra != 1)
                    
                      <div class="flex-col justify-center py-4">
                            <div class="flex @if ($locale_direction == 'rtl') flex-row-reverse @else flex-row @endif ">
                                <div class="w-1/2 @if ($locale_direction == 'rtl') text-right @else text-left @endif">
                                    <h3 class="font-semibold text-lg text-dark">{{ $item->name }}</h3>
                                </div>
                                <div class="w-1/2 @if ($locale_direction == 'rtl') text-right @else text-left @endif">
                                    <h3 class="font-semibold text-lg text-dark">{{$item->attributes->size?$item->attributes->size:'' }}</h3>
                                </div>
                                <div class="md:w-1/3 xs:w-5/12">
                                    <div class="flex flex-row qty_row justify-center w-full">
                                        <button type="button"
                                            class="minus border-2 rounded-full text-lg text-center border-dark text-dark h-8 w-8">-</button>
                                        {{-- <input type="text" data-id="{{ $item->id }}" value="{{ $item->quantity }}"
                                            class="quantity text-center text-dark w-16 line leading-none border-transparent bg-transparent focus:border-transparent focus:ring-0 "> --}}
                                            <input type="text" data-id="{{ $item->id }}" value="{{ $item->quantity }}"
                                            class="quantity text-center text-dark w-16 line leading-none border-transparent bg-transparent focus:border-transparent focus:ring-0 ">
                                        <button type="button"
                                            class="plus border-2 rounded-full text-lg text-center border-dark text-dark h-8 w-8">+</button>
                                    </div>
                                </div>
                                <div
                                    class="md:w-1/6 xs:w-1/12  @if ($locale_direction == 'rtl') text-left @else text-right @endif ">
                                    <a href="{{ action('CartController@removeProduct', $item->id) }}"
                                        class="mt-2 rounded-full text-lg text-center border-lightgrey text-rose-700 h-8 w-8">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <p class="text-xs text-dark font-semibold">{!! $item->associatedModel->product_details !!}</p>
                            <h3
                                class="font-semibold text-base text-dark py-2 @if ($item->associatedModel->variations->first()!=null) @if ($item->associatedModel->variations->first()->name == 'Default') hidden @endif @endif"></h3>
                                @foreach ($item->associatedModel->variations as $variation)
                                @if ( $variation->id==$item->attributes->variation_id)
                                    <div
                                        class="flex @if ($locale_direction == 'rtl') flex-row-reverse @else flex-row @endif ">
                                        {{-- <div class="flex-1">
                                            <div
                                                class="flex @if ($locale_direction == 'rtl') flex-row-reverse @else flex-row @endif items-center mb-4">
                                                <input type="radio" data-id="{{ $item->id }}"
                                                    @if ($item->attributes->variation_id == $variation->id) checked @endif
                                                    value="{{ $variation->id }}"
                                                    class="variation_radio w-4 h-4 border-red focus:ring-2 focus:ring-red dark:focus:ring-red dark:focus:bg-red dark:bg-gray-700 dark:border-red"
                                                    aria-labelledby="radio" aria-describedby="radio">
                                                <label for="radio"
                                                    class="block ml-2 text-sm font-medium text-gray-900 dark:text-gray-300 px-2">
                                                    @if ($variation->name == 'Default')
                                                        {{ $item->name }}
                                                    @else
                                                        {{ $variation->size->name ?? '' }}
                                                    @endif
                                                </label>
                                            </div>
                                        </div> --}}
                                        <div
                                            class="flex-1 text-base @if ($locale_direction == 'rtl') text-left @else text-right @endif font-semibold">
                                            {{ @num_format($variation->default_sell_price - $item->attributes->discount) }}
                                            <span
                                                class="font-bold">
                                            {{ session('currency')['code'] }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    {{-- @endfor --}}
                    @endif
                @endforeach

                <div class="flex @if ($locale_direction == 'rtl') justify-end @endif">
                    <h3
                        class="font-semibold text-lg text-dark pt-5 @if ($locale_direction == 'rtl') text-right @else text-right @endif @if ($extras->count() == 0) hidden @endif">
                        @lang('lang.extras')</h3>
                </div>
                @foreach ($extras as $extra)
                    <div class="flex @if ($locale_direction == 'rtl') flex-row-reverse @else flex-row @endif py-2">
                        <div class="flex-1">
                            <div class="flex @if ($locale_direction == 'rtl') flex-row-reverse @else flex-row @endif">
                                <input @if (in_array($extra->id, $cart_content->pluck('id')->toArray())) checked @endif
                                    class="extra_checkbox form-check-input appearance-none h-4 w-4 border border-red rounded-sm bg-white checked:bg-red checked:border-red focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                    type="checkbox" value="{{ $extra->id }}" id="extra">
                                <label class="form-check-label inline-block text-gray-800 font-semibold px-2" for="extra">
                                    {{ $extra->name }}
                                </label>
                            </div>
                        </div>
                        <div
                            class="flex-1 text-base @if ($locale_direction == 'rtl') text-left @else text-right @endif font-semibold">
                            {{ @num_format($extra->sell_price - $extra->discount_value) }}<span class="font-bold">
                                {{ session('currency')['code'] }}</span>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="flex justify-center">
            <button type="button" class="lg:w-1/4 md:w-1/2 xs:w-full h-10 mt-4 rounded-lg  bg-red text-white relative"
                id="send_the_order">@lang('lang.send_the_order')
                <span class="text-white text-base absolute right-2">{{ @num_format($total) }}
                    {{ session('currency')['code'] }}</span></button>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@section('javascript')
    <script>
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

            window.location.href = base_path + "/cart/update-product-quantity/" + product_id + "/" +
                quantity;

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
    </script>
@endsection
