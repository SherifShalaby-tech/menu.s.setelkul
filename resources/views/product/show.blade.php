@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
@section('content')
    @include('layouts.partials.product-header')

    <div class="container mx-auto mt-14">
        <div class="flex flex-row flex-wrap h-96 min-h-full">
            <div class="flex-1 xs:w-full lg:w-1/2 px-16 @if ($product->getMedia('product')->count() == 0) md:block xs:hidden @endif">

                <div class="flex flex-row items-center @if ($product->getMedia('product')->count() == 0) xs:hidden @endif">
                    <div class="flex-3 w-20 block md:block xs:hidden ">
                        <div class="owl-nav">
                            <div class="prev-nav">
                                <img src="{{ asset('images/slider-arrow-left.png') }}" alt="" class="m-auto">
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 ">
                        <div class="product-slider">
                            @foreach ($product->getMedia('product') as $image)
                                <img src="@if (!empty($image->getUrl())) {{ $image->getUrl() }}@else{{ asset('uploads/' . session('logo')) }} @endif"
                                    class="aspect-square" alt="" style="">
                            @endforeach
                        </div>
                    </div>
                    <div class="flex-3 w-20 block md:block xs:hidden  justify-center">
                        <div class="owl-nav">
                            <div class="next-nav">
                                <img src="{{ asset('images/slider-arrow-right.png') }}" alt="" class="m-auto">
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="flex-1 xs:w-full lg:w-1/2">
                <div class="flex flex-col bg-white opacity-70 px-16 py-8">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
                        <p class="py-2 text-gray-600">{!! $product->product_details !!}</p>
                    </div>
                    <div class="flex-1 pt-4">
                        <div class="flex flex-col">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold">
                                    @if (!empty($product->discount_value) && $product->discount_value > 0)
                                        <span
                                            class="strikethrough text-gray-600 mr-4">{{ @num_format($product->sell_price) }}
                                            {{ session('currency')['code'] }}
                                    @endif
                                    </span> {{ @num_format($product->sell_price - $product->discount_value) }}
                                    {{ session('currency')['code'] }}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 pt-4">
                        <div class="flex flex-col">
                            <div class="flex-1">
                                <div class="flex flex-row">
                                    <button
                                        class="minus border-2 rounded-full text-lg text-center border-dark text-dark h-8 w-8">-</button>
                                    <input type="quantity" value="1"
                                        class="quantity text-center focus:outline-none text-dark bg-transparent w-16">
                                    <button
                                        class="plus border-2 rounded-full text-lg text-center border-dark text-dark h-8 w-8">+</button>
                                </div>
                                <div class="flex">
                                    <a href="{{ action('CartController@addToCart', $product->id) }}"
                                        class="add_to_cart_btn bg-red text-white font-semibold rounded-lg px-4 py-2 mt-4 ">@lang('lang.add_to_cart')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.partials.cart-row')
    </div>
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js"></script>
    <script>
        $(document).on('click', '.plus', function() {
            let quantity = __read_number($('.quantity'));
            $('.quantity').val(quantity + 1);
            $('.quantity').change();
        })
        $(document).on('click', '.minus', function() {
            let quantity = __read_number($('.quantity'));
            if (quantity > 1) {
                $('.quantity').val(quantity - 1);
                $('.quantity').change();
            }
        })
        $(document).on('change', '.quantity', function() {
            $('.add_to_cart_btn').attr('href',
                '{{ action('CartController@addToCart', $product->id) }}?quantity=' +
                $(this).val());
        })

        $(document).ready(function() {
            var slider = tns({
                container: ".product-slider",
                items: 1,
                // slideBy: "page",
                autoplay: false,
                mouseDrag: true,
                controls: false,
                nav: false,
                loop: true,
                swipeAngle: false,
            });

            document.querySelector(".next-nav").onclick = function() {
                slider.goTo("next");
            };
            document.querySelector(".prev-nav").onclick = function() {
                slider.goTo("prev");
            };
        });
    </script>
@endsection
