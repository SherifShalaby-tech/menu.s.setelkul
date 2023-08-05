<div class="w-full flex flex-row bg-no-repeat bg-center text-center items-center" id="header"
    style="background-size:100% 100%; height: 200px; background-image: url('@if(!empty(session('breadcrumb_background_image'))){{ images_asset(asset('uploads/' . session('breadcrumb_background_image'))) }}@else{{ asset('images/default-breadcrumb-bg.png') }}@endif');">
    <div class="w-full">
        <div class="flex flex-row">
            <div class="flex-3 text-white text-4xl font-bold text-left">
                @include('layouts.partials.language_dropdown')
            </div>
            <div class="flex-1 mt-8 text-center">
                <p class="font-bold md:text-4xl xs:text-2xl text-white">{{ $category->name }}</p>
            </div>
            <div class="flex-3 w-48 items-end">
                @include('layouts.partials.social_icons')
            </div>
        </div>

        <div class="container mx-auto mt-16">
            <div class="flex justify-end w-full">
                <a class="md:text-xl xs:text-sm text-white font-semibold px-2"
                    href="{{ action('HomeController@index') }}">@lang('lang.home')</a> <span
                    class="md:text-xl xs:text-sm text-white font-semibold px-1">-</span>
                <a class="md:text-xl xs:text-sm text-white font-semibold px-2"
                    href="{{ action('ProductController@getProductListByCategory', $category->id) }}">{{ $category->name }}</a>
            </div>
        </div>
    </div>
</div>
