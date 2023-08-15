
<div class="w-full footer bg-dark">
    <div class="mx-auto ">

    <div class="grid flex-row px-2 md:flex xs:text-sm">
             <div class="w-1/4 text-center md:block xs:hidden">

            </div>
            <div class="w-1/2 md:block xs:w-full" style="color: #fff;">
                  </h4><br> <br>

                  <p class="stext-107 cl0 size-201">
                      <span>  <i class="fas fa-phone-alt" style="color: #fff;"></i>  {{ App\Models\System::getProperty('phone_number_1') }} <br>
                              <i class="fab fa-whatsapp" style="color: #fff;"></i>   {{ App\Models\System::getProperty('whatsapp') }}
                      </span>
                  </p>

                  <p class="stext-107 cl0 size-201">
                      <span> <i class="fas fa-envelope" style="color: #fff;"></i>    {{ App\Models\System::getProperty('system_email') }} </span>
                  </p>

                  <p class="stext-107 cl0 size-201">
                      <span>

                       <a class="cl7"  href="https://maps.google.com/maps?q={{ App\Models\System::getProperty('address') }}" target="_blank">
                            <i class="fas fa-map-marker-alt" style="color: #fff;"></i>  {{ App\Models\System::getProperty('address') }}</a>
                      </span>
                  </p>
            </div>
            <div class="w-1/2 text-right xs:w-full">
                <div class="flex-col mt-6">
                    <div class="pt-2 pr-4 text-lg font-semibold text-white">@lang('lang.about_us')</div>
                    <div class="pt-2 pr-4 font-semibold text-white sm:text-base xs:text-xs">
                        <a href="{{ action('AboutUsController@index') }}">
                            {{ App\Models\System::getProperty('about_us_footer') }}
                        </a>
                    </div>

                </div>
            </div>

            <div class="text-left md:w-1/4">
                <img src="{{ images_asset(asset('uploads/' . session('logo'))) }}" alt="logo" class="w-24 h-24 mt-8">
            </div>

        </div>
        <div class="flex flex-row">
            <div class="w-3/4 text-sm text-right xs:w-full">
                <div class="pt-2 pr-2 mt-8">
                    <a href="{{ action('AboutUsController@index') }}"
                        class="px-4 py-2 font-bold text-white border-2 border-white rounded-lg bg-red md:text-base xs:text-sm">@lang('lang.show_more')
                    </a>
                </div>
            </div>
            <div class="w-1/4 text-center md:block xs:hidden">
            </div>
        </div>
    </div>
    <div class="flex w-full">
        <div class="flex-1 mt-10" style="padding-bottom: 5px;font-size: small;">
            <p class="text-center text-white">@lang('lang.footer_copyright')</p>
             <p class="text-center text-white">Tel :00905386531059 - 0097433231457</p>
        </div>
    </div>
</div>
