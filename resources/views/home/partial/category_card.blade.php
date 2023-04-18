<a href="{{ action('ProductController@getProductListByCategory', $category->id) }}"
    class="w-full h-0 shadow-lg pb-full rounded-xl bg-center bg-no-repeat bg-cover relative border-2 border-dark mb:mb-8 xs:mb-16"
    style="background-image: url('{{ !empty($category->getFirstMediaUrl('product_class')) ? $category->getFirstMediaUrl('product_class') : asset('uploads/' . session('logo')) }}')">
    <div class="flex absolute md:-bottom-16 xs:-bottom-10 w-full">
        <div class="lg:h-10 md:h-8 xs:h-6 md:w-48 xs:w-40 bg-darkblue mx-auto text-center rounded-3xl">
            <h3 class="lg:text-xl md:text-sm xs:text-tiny text-white font-semibold py-1">{{ $category->name }}</h3>
        </div>
    </div>
</a>
