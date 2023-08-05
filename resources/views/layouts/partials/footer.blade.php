
<div class="footer w-full bg-dark">
    <div class=" mx-auto">
        <div class="grid xs:grid-cols-12 md:grid-cols-4">
            
            <div class="w-full mb-4  md:block text-center font-semibold sm:text-base text-sm" style="color: #fff;">
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
            <div class="w-1/4 mb-4 text-center md:block xs:hidden">
               
            </div>
            <div class="w-full mb-4 text-right ">
                <div class="flex-col mt-6">
                    <div class="pr-4 pt-2 font-semibold text-lg text-white">@lang('lang.about_us')</div>
                    <div class="pr-4 pt-2 font-semibold text-md text-white">
                        <a href="{{ action('AboutUsController@index') }}">
                            {{ App\Models\System::getProperty('about_us_footer') }}
                        </a>
                    </div>
                
                </div>
            </div>

            <div class="w-full mb-4  flex justify-center items-center">
                <img src="{{ images_asset(asset('uploads/' . session('logo'))) }}" alt="logo" class="mt-8 w-24 h-24">
            </div>

        </div>
        <div class="flex flex-row">
            <div class="w-3/4 text-right xs:w-full">
                <div class="pr-2 pt-2 mt-8">
                    <a href="{{ action('AboutUsController@index') }}"
                        class="bg-red text-white md:text-base xs:text-sm font-bold px-4 py-2 border-2 border-white rounded-lg">@lang('lang.show_more')
                    </a>
                </div>
            </div>
            <div class="w-1/4 text-center md:block xs:hidden"> 
            </div>
        </div>
    </div>
    <div class="flex w-full">
        <div class="flex-1 mt-10" style="padding-bottom: 5px;font-size: small;">
            <p class="text-white text-center">@lang('lang.footer_copyright')</p>
             <p class="text-white text-center">Tel :00905386531059 - 0097433231457</p>
        </div>
    </div>
</div>
