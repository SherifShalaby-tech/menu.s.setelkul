@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
@section('content')
<div style="display:none;">@lang('lang.categories')</div>
    <div class="container mx-auto">
        <div class="flex">
            <div class="flex-1">
                <div class="w-1/2 h-10 bg-red text-white mx-auto text-center -mt-5 rounded-xl">
                    <h3 class="text-2xl text-white font-semibold py-1">@lang('lang.categories')</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto mt-14">
        @if (!empty($homepage_category_carousel))
            <div class="flex flex-row items-center">
                <div class="flex-3 w-48 md:block xs:hidden ">
                    <div class="owl-nav">
                        <div class="prev-nav">
                            <img src="{{ asset('images/slider-arrow-left.png') }}" alt="" class="m-auto">
                        </div>
                    </div>
                </div>
                <div class="flex-1 w-80">
                    <div class="category-slider">
                        @foreach ($categories as $category)
                            <div class="text-center md:w-1/4 xs:w-1/3 xl:p-8 lg:p-2 md:p-2 xs:p-1">
                                <a href="{{ action('ProductController@getProductListByCategory', $category->id) }}"
                                    class="">
                                    <div class="flex-col mx-auto">
                                        <div class="">
                                            <img src="{{ !empty($category->getFirstMediaUrl('product_class')) ? images_asset($category->getFirstMediaUrl('product_class')) : images_asset(asset('uploads/' . session('logo'))) }}"
                                                class="border-2 border-dark mx-auto w-full aspect-square rounded-lg"
                                                alt="category-1">
                                        </div>
                                        <div
                                            class="lg:h-10 md:h-6 xs:h-6 lg:w-36 md:w-22 xs:w-22 bg-darkblue mx-auto text-center rounded-3xl mt-4">
                                            <h3 class="lg:text-lg md:text-tiny xs:text-tiny text-white font-semibold py-1">
                                                {{ $category->name }}</h3>
                                        </div>
                                    </div>
                                </a>

                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex-3 w-48 md:block xs:hidden ">
                    <div class="owl-nav">
                        <div class="next-nav">
                            <img src="{{ asset('images/slider-arrow-right.png') }}" alt="" class="m-auto">
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="w-full mx-auto p-4">
                <div class="grid xs:grid-cols-3 md:grid-cols-4 xs:gap-2 md:gap-16 md:mt-12 xs:mt-6">
                    @foreach ($categories as $category)
                        @include('home.partial.category_card', [
                            'category' => $category,
                        ])
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @if(count($offers_array) > 0)
        <div class="container mx-auto">
            <div class="flex">
                <div class="flex-1">
                    <div class="w-1/2 h-10 bg-red text-white mx-auto text-center mt-14 rounded-xl">
                        <h3 class="text-2xl text-white font-semibold py-1">@lang('lang.promotions')</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mx-auto mt-14">
            <div class="w-full mx-auto p-4">
                <div class="grid xs:grid-cols-3 md:grid-cols-4 xs:gap-2 md:gap-16 md:mt-12 xs:mt-6">
                    @foreach ($offers_array as $offer)
                        @if ($loop->index == 4)
                        @break
                    @endif
                    @include('home.partial.promotion_card', [
                        'offer' => $offer,
                    ])
                @endforeach
            </div>
        </div>

        @if (count($offers_array) != 0 && $offers_count > 4)
            <div class="container mx-auto">
                <div class="flex md:justify-end xs:justify-center">
                    <a href="{{ action('ProductController@getPromotionProducts') }}"
                        class="bg-red text-white font-semibold py-1 md:px-4 xs:px-8 rounded-md md:mr-16 md:mt-8">@lang('lang.show_more')</a>
                </div>
            </div>
        @endif
    @endif

    @include('layouts.partials.cart-row')
</div>
@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js"></script>
@if (!empty($homepage_category_carousel))
    <script>
        $(document).ready(function() {
            var slider = tns({
                container: ".category-slider",
                items: 4,
                slideBy: "page",
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
@endif
@endsection
