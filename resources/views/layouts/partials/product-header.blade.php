<div class="w-full flex flex-row bg-no-repeat bg-center bg-cover text-center items-center"
    style="height: 200px; background-image: url('@if (!empty(session('breadcrumb_background_image'))) {{ asset('uploads/' . session('breadcrumb_background_image')) }}@else{{ asset('images/default-breadcrumb-bg.png') }} @endif');">
    <div class="w-full">
        <div class="flex flex-row">
            <div class="flex-3 text-white text-4xl font-bold text-left">
                @include('layouts.partials.language_dropdown')
            </div>
            <div class="flex-1">

            </div>
            <div class="flex-3 w-48 items-end">
                @include('layouts.partials.social_icons')
            </div>
        </div>
        <div class="flex flex-row mt-2 text-center">
            <div class="flex-1">
                <p class="font-bold md:text-4xl xs:text-xl text-white text-center">{{ $product->name }}</p>
            </div>
        </div>

        <div class="container mx-auto mt-16">
            <div class="flex justify-end w-full">
                <a class="md:text-xl xs:text-sm text-white font-semibold px-2"
                    href="{{ action('HomeController@index') }}">@lang('lang.home')</a>
                <span class="md:text-xl xs:text-sm text-white font-semibold px-1">-</span>
                <a class="md:text-xl xs:text-sm text-white font-semibold px-2"
                    href="{{ action('ProductController@getProductListByCategory', $product->category->id) }}">{{ $product->category->name }}</a>
                <span class="md:text-xl xs:text-sm text-white font-semibold px-1">-</span>
                <a class="md:text-xl xs:text-sm text-white font-semibold px-2"
                    href="{{ action('ProductController@show', $product->id) }}">{{ $product->name }}</a>
            </div>
        </div>
    </div>
</div>
