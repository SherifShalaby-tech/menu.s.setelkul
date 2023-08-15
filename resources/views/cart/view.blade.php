@extends('layouts.app')
@php
$locale_direction = LaravelLocalization::getCurrentLocaleDirection();
@endphp
@section('content')
    @include('layouts.partials.cart-header')
{{--    <div class="container mx-auto mt-14">--}}
{{--        <div class="flex mt-40 bg-red">--}}
{{--            <div class="relative w-full h-48 overflow-hidden ">--}}
{{--                <img src="{{ asset('images/cart-top.png') }}" class="object-cover w-full h-full mx-auto " alt="cart-top">--}}
{{--                <div--}}
{{--                    class="absolute w-full py-2.5 px-5 bottom-0 inset-x-0 text-white text-xs text-center leading-4 bg-gradient-to-t from-black">--}}
{{--                    <p class="py-10 font-semibold text-white text-tiny"></p>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

<div class="container py-4 mx-auto">
        {!! Form::open(['url' => action('OrderController@store'), 'method' => 'pos', 'id' => 'cart_form']) !!}
        <div class="py-4 bg-white lg:flex-row xs:flex-col opacity-70">
            <div class="flex-1 flow-root xl:px-16 lg:px-2 md:px-4 xs:px-4">
                <div class="form-group">
                    <label
                        class="font-semibold text-base text-dark  @if ($locale_direction == 'rtl') float-right @else float-left @endif"
                        for="sales_note">@lang('lang.notes')</label>
                        <textarea class="w-full px-4 border-b rounded-lg border-dark" name="sales_note" id="sales_note" rows="3"></textarea>
                </div>
                <div class="flex flex-row flow-root py-2">
                    <label
                        class="font-semibold text-base text-dark pr-2 pt-1 @if ($locale_direction == 'rtl') float-right @else float-left @endif"
                        for="customer_name">@lang('lang.name')</label>
                    <input type="text" name="customer_name" required
                        class="border-b border-dark rounded-lg w-full px-4 w-3/5 @if ($locale_direction == 'rtl') float-left @else float-right @endif "
                        value="">
                </div>
                {{-- <div class="flex flex-row flow-root py-2">
                    <label
                        class="font-semibold text-base text-dark pr-2 pt-1 @if ($locale_direction == 'rtl') float-right @else float-left @endif"
                        for="phone_number">@lang('lang.phone_number')</label>
                    <input type="text" name="phone_number" required
                        class="border-b border-dark rounded-lg w-full px-4 w-3/5 @if ($locale_direction == 'rtl') float-left @else float-right @endif "
                        value="">
                </div> --}}
                <div class="flex flex-row flow-root py-2">
                    <label
                        class="font-semibold text-base text-dark pr-2 pt-1 @if ($locale_direction == 'rtl') float-right @else float-left @endif"
                        for="address">@lang('lang.address')</label>
                    <input type="text" name="address"
                        class="border-b border-dark rounded-lg w-full px-4 w-3/5 @if ($locale_direction == 'rtl') float-left @else float-right @endif "
                        value="">
                </div>

                <div class="flex justify-center py-2">
                    <div class="flex-1">
                        <label class="float-right pt-1 pr-2 text-base font-semibold order_now text-dark"
                            for="order_now">@lang('lang.order_now')</label>
                    </div>
                    <div class="flex justify-center w-16">
                        <div class="mt-1">
                            <label for="order" class="relative flex items-center mb-4 cursor-pointer">
                                <input type="checkbox" name="order_type" id="order" value="1" class="sr-only">
                                <div
                                    class="h-6 bg-gray-200 border rounded-full w-11 border-red toggle-bg dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="float-left pt-1 pr-2 text-base font-semibold order_later"
                            for="order_later">@lang('lang.order_later')</label>
                    </div>
                </div>
                <div class="flex flex-row justify-center hidden order_later_div ">
                    <img class="px-2 md:h-8 md:w-12 xs:h-4 xs:w-8 md:mt-1 xs:mt-4"
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
                    <img class="px-2 md:h-8 md:w-12 xs:h-4 xs:w-8 md:mt-1 xs:mt-4"
                        src="{{ asset('images/time-icon.png') }}" alt="">

                    <input type="time" name="time" id="base-input" value="{{ date('H:i') }}"
                        class="font-w w-32 bg-gray-50 border border-gray-300 text-gray-900 md:text-base xs:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 px-0 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                </div>
                <div class="flex flex-row justify-center py-2">
                    <div class="flex-1">
                        <label class="float-right pt-1 pr-2 text-base font-semibold pay_online"
                            for="pay_online">@lang('lang.pay_online')</label>
                    </div>
                    <div class="flex justify-center w-16">
                        <div class="mt-1">
                            <label for="payment_type" class="relative flex items-center mb-4 cursor-pointer">
                                <input type="checkbox" id="payment_type" name="payment_type" checked value="1"
                                    class="sr-only">
                                <div
                                    class="h-6 bg-gray-200 border rounded-full w-11 border-red toggle-bg dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="float-left pt-1 pr-2 text-base font-semibold cash_on_delivery text-dark"
                            for="cash_on_delivery">@lang('lang.cash_on_delivery')</label>
                    </div>
                </div>

                <div class="flex flex-row items-center justify-center py-2">
                    <div class="flex-1 text-center">
                        <input type="radio" name="delivery_type" value="i_will_pick_it_up_my_self" required
                            class="w-4 h-4 border-red focus:ring-2 focus:ring-red dark:focus:ring-red dark:focus:bg-red dark:bg-gray-700 dark:border-red"
                            aria-labelledby="radio" aria-describedby="radio">
                        <label class="pl-2 font-semibold i_will_pick md:text-base xs:text-xs text-dark"
                            for="i_will_pick_it_up_my_self">@lang('lang.i_will_pick_it_up_my_self')</label>
                    </div>
                    <div class="flex-1 text-center">
                        <input type="radio" name="delivery_type" value="home_delivery" checked required
                            class="w-4 h-4 border-red focus:ring-2 focus:ring-red dark:focus:ring-red dark:focus:bg-red dark:bg-gray-700 dark:border-red"
                            aria-labelledby="radio" aria-describedby="radio">
                        <label class="pl-2 font-semibold i_will_pick md:text-base xs:text-xs text-dark"
                            for="home_delivery">@lang('lang.home_delivery')</label>
                    </div>
                    <div class="flex-1 text-center">
                        <input type="radio" name="delivery_type" value="dining_in" required
                            class="w-4 h-4 border-red focus:ring-2 focus:ring-red dark:focus:ring-red dark:focus:bg-red dark:bg-gray-700 dark:border-red"
                            aria-labelledby="radio" aria-describedby="radio">
                        <label class="pl-2 font-semibold i_will_pick md:text-base xs:text-xs text-dark"
                            for="dining_in">@lang('lang.dining_in')</label>
                    </div>
                </div>

                <div class="flex flex-row justify-center hidden inside_restaurant_div ">
                    <label class="float-left pt-1 pr-2 text-base font-semibold text-dark"
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

                                <p class="font-semibold text-tiny text-dark">{{ $item->name }}</ح>
                                </div>
                                <div class="w-1/2 @if ($locale_direction == 'rtl') text-right @else text-left @endif">
                                    <p class="font-semibold text-tiny text-dark">{{$item->attributes->size?$item->attributes->size:'' }}</p>
                                </div>
                                <div class="md:w-1/3 xs:w-5/12">
                                    <div class="flex flex-row justify-center w-full qty_row">
                                        <button type="button"
                                            class="w-8 h-8 px-2 text-lg text-center border-2 rounded-full minus border-dark text-dark">-</button>
                                        {{-- <input type="text" data-id="{{ $item->id }}" value="{{ $item->quantity }}"
                                            class="w-16 leading-none text-center bg-transparent border-transparent quantity text-dark line focus:border-transparent focus:ring-0 "> --}}
                                            <input type="text" data-id="{{ $item->id }}" value="{{ $item->attributes->quantity }}"
                                            class="w-10 leading-none text-center bg-transparent border-transparent quantity text-dark line focus:border-transparent focus:ring-0 ">
                                        <button type="button"
                                            class="w-8 h-8 px-2 text-lg text-center border-2 rounded-full plus border-dark text-dark ">+</button>
                                    </div>
                                </div>
                                <div
                                    class="md:w-1/6 xs:w-1/12  @if ($locale_direction == 'rtl') text-left times-right @else text-right times-left @endif" style="transform:translatey(-15px);">
                                    <a href="{{ action('CartController@removeProduct', $item->id) }}"
                                        class="w-8 h-8 mt-2 mb-3 text-lg text-center rounded-full border-lightgrey text-rose-700">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <p class="text-xs font-semibold text-dark">{!! $item->associatedModel->product_details !!}</p>
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
                                                    class="w-4 h-4 variation_radio border-red focus:ring-2 focus:ring-red dark:focus:ring-red dark:focus:bg-red dark:bg-gray-700 dark:border-red"
                                                    aria-labelledby="radio" aria-describedby="radio">
                                                <label for="radio"
                                                    class="block px-2 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
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
                                    class="float-left w-4 h-4 mt-1 mr-2 align-top transition duration-200 bg-white bg-center bg-no-repeat bg-contain border rounded-sm appearance-none cursor-pointer extra_checkbox form-check-input border-red checked:bg-red checked:border-red focus:outline-none"
                                    type="checkbox" value="{{ $extra->id }}" id="extra">
                                <label class="inline-block px-2 font-semibold text-gray-800 form-check-label" for="extra">
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

        <div class="flex justify-center" id="button_xs">
            <button type="button" class="relative h-10 mt-4 text-white rounded-lg lg:w-1/4 md:w-1/2 xs:w-full bg-red"
                id="send_the_order">@lang('lang.send_the_order')
                <span class="absolute text-base text-white right-2 order-total-price">{{ @num_format($total) }}
                    {{ session('currency')['code'] }}</span></button>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@section('javascript')

@endsection
