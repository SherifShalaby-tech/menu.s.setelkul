<a href="{{ action('ProductController@getProductListByCategory', $category->id) }}"
class="relative w-full h-0 bg-center bg-no-repeat bg-cover border-2 shadow-lg pb-full rounded-xl border-dark mb:mb-8 xs:mb-16"
    style="background-image: url('{{ !empty($category->getFirstMediaUrl('product_class')) ? images_asset($category->getFirstMediaUrl('product_class')) : images_asset(asset('uploads/' . session('logo'))) }}')">
    <div class="absolute flex w-full md:-bottom-16 xs:-bottom-10">
        <div class="mx-auto text-center lg:h-10 md:h-8 xs:h-6 md:w-48 xs:w-40 bg-darkblue rounded-3xl category">
            <h3 class="py-3 font-semibold text-white lg:text-xl md:text-sm xs:text-tiny">{{ $category->name }}</h3>
        </div>
    </div>
</a>
