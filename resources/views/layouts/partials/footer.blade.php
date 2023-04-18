
<div class="footer w-full bg-dark">
    <div class=" mx-auto">
        <div class="flex flex-row">
             <div class="w-1/4 text-center md:block xs:hidden">
               
            </div>
            <div class="w-1/2   md:block xs:hidden" style="color: #fff;">
                <h4 class="stext-301 cl0 p-b-10">
                    
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
            <div class="w-1/2 text-right">
                <div class="flex-col mt-6">
                    <div class="pr-4 pt-2 font-semibold text-lg text-white">@lang('lang.about_us')</div>
                    <div class="pr-4 pt-2 font-semibold sm:text-base xs:text-xs text-white">
                        <a href="{{ action('AboutUsController@index') }}">
                            {{ App\Models\System::getProperty('about_us_footer') }}
                        </a>
                    </div>
                    <div class="pr-4 pt-2 mt-8">
                        <a href="{{ action('AboutUsController@index') }}"
                            class="bg-red text-white md:text-base xs:text-sm font-bold px-4 py-2 border-2 border-white rounded-lg">@lang('lang.show_more')
                        </a>
                    </div>
                </div>
            </div>

            <div class="w-1/4 text-left">
                <img src="{{ asset('uploads/' . session('logo')) }}" alt="logo" class="mt-8 w-24 h-24">
            </div>

        </div>
    </div>
    <div class="flex w-full">
        <div class="flex-1 mt-10" style="padding-bottom: 5px;font-size: small;">
            <p class="text-white text-center">@lang('lang.footer_copyright')</p>
             <p class="text-white text-center">Tel : 00201003836917 - 00905386531059 - 0097433231457</p>
        </div>
    </div>
</div>
