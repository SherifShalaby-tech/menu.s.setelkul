<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\System;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SettingController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    public function getSystemSettings()
    {
        if (!auth()->user()->can('settings.system_setting.create') && !auth()->user()->can('settings.system_setting.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        $settings = System::pluck('value', 'key');
        $currencies  = $this->commonUtil->allCurrencies();
        $locales = $this->commonUtil->getSupportedLocalesArray();

        return view('admin.setting.setting')->with(compact(
            'settings',
            'locales',
            'currencies'
        ));
    }
    public function saveSystemSettings(Request $request)
    {
        if (!auth()->user()->can('settings.system_setting.create') && !auth()->user()->can('settings.system_setting.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        try {
            System::updateOrCreate(
                ['key' => 'site_title'],
                ['value' => $request->site_title, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'facebook'],
                ['value' => $request->facebook, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'twitter'],
                ['value' => $request->twitter, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'whatsapp'],
                ['value' => $request->whatsapp, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'youtube'],
                ['value' => $request->youtube, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'instagram'],
                ['value' => $request->instagram, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'telegram'],
                ['value' => $request->telegram, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'phone_number_1'],
                ['value' => $request->phone_number_1, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'phone_number_2'],
                ['value' => $request->phone_number_2, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'system_email'],
                ['value' => $request->system_email, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'language'],
                ['value' => $request->language, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            $url= LaravelLocalization::getLocalizedURL($request->language);
            System::updateOrCreate(
                ['key' => 'currency'],
                ['value' => $request->currency, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            if (!empty($request->currency)) {
                $currency = Currency::find($request->currency);
                $currency_data = [
                    'country' => $currency->country,
                    'code' => $currency->code,
                    'symbol' => $currency->symbol,
                    'decimal_separator' => '.',
                    'thousand_separator' => ',',
                    'currency_precision' => 2,
                    'currency_symbol_placement' => 'before',
                ];
                $request->session()->put('currency', $currency_data);
            }
            System::updateOrCreate(
                ['key' => 'open_time'],
                ['value' => $request->open_time, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'address'],
                ['value' => $request->address, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'about_us_footer'],
                ['value' => $request->about_us_footer, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'about_us_content'],
                ['value' => $request->about_us_content, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'homepage_category_count'],
                ['value' => $request->homepage_category_count, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'homepage_category_carousel'],
                ['value' => !empty($request->homepage_category_carousel) ? 1 : 0, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            $data['logo'] = null;
            if ($request->has('logo') && !is_null('logo')) {
                // $data['logo'] = $this->commonUtil->ImageResizeAndUpload($request->logo, 'uploads', 250, 250);
                if(preg_match('/^data:image/', $request->input('logo')))
                {
                    $logo=System::where('key','logo')->first();
                    if(!empty($logo->value)&&$logo->value!=null && file_exists("uploads/".System::getProperty('logo'))){
                        unlink( "uploads/".System::getProperty('logo'));
                    }
                    $imageData = $this->getCroppedImage($request->logo);
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $data['logo'] = $image;
                    // $data['logo'] = $this->commonUtil->ImageResizeAndUpload($b, 'uploads', 250, 250);
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $logo_setting = System::updateOrCreate(
                        ['key' => 'logo'],
                        ['value' => $data['logo'], 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
                    );
                    $data['logo_url'] = asset('uploads/' . $data['logo']);
                    if(!env('ENABLE_POS_SYNC')){
                    $this->commonUtil->addSyncDataWithPos('System', $logo_setting, $data, 'POST', 'setting');
                    unset($data['logo_url']);
                    }
                }
            }
            $data['home_background_image'] = null;
            // $data['home_background_image'] = $this->commonUtil->ImageResizeAndUpload($request->home_background_image, 'uploads');
            if ($request->has('home') && !is_null('home')) {
                if(preg_match('/^data:image/', $request->input('home')))
                {
                    $home=System::where('key','home_background_image')->first();
                    if(!empty($home->value)&&$home->value!=null && file_exists("uploads/".System::getProperty('home_background_image'))){
                        // return System::getProperty('home_background_image');
                        unlink( "uploads/".System::getProperty('home_background_image'));
                    }
                    $imageData = $this->getCroppedImage($request->home);
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $data['home'] = $image;
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $home_setting = System::updateOrCreate(
                        ['key' => 'home_background_image'],
                        ['value' => $data['home'], 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
                    );
                }
            }
            
            $data['breadcrumb_background_image'] = null;
            if ($request->has('breadcrumb') ) {
                if(preg_match('/^data:image/', $request->input('breadcrumb')))
                {
                    $breadcrumb=System::where('key','breadcrumb_background_image')->first();
                    if(!empty($breadcrumb->value)&& $breadcrumb->value!=null && file_exists("uploads/".System::getProperty('breadcrumb_background_image'))){
                        // return System::getProperty('breadcrumb_background_image');
                        unlink( "uploads/".System::getProperty('breadcrumb_background_image'));
                    }
                    $imageData = $this->getCroppedImage($request->breadcrumb);
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $data['breadcrumb'] = $image;
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $breadcrumb_setting = System::updateOrCreate(
                        ['key' => 'breadcrumb_background_image'],
                        ['value' => $data['breadcrumb'], 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
                    );
                }
            }
          
            $data['page_background_image'] = null;
            if ($request->has('page') ) {
                if(preg_match('/^data:image/', $request->input('page')))
                {
                    $page=System::where('key','page_background_image')->first();
                    if(!empty($page->value)&&$page->value!=null && file_exists("uploads/".System::getProperty('page_background_image'))){
                        // return System::getProperty('page_background_image');
                        unlink( "uploads/".System::getProperty('page_background_image'));
                    }
                    $imageData = $this->getCroppedImage($request->page);
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $data['page'] = $image;
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $page_setting = System::updateOrCreate(
                        ['key' => 'page_background_image'],
                        ['value' => $data['page'], 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
                    );
                }
            }
            // $data['logo'] = null;
            // if ($request->hasFile('logo')) {
            //     $data['logo'] = $this->commonUtil->ImageResizeAndUpload($request->logo, 'uploads', 250, 250);
            //     $logo_setting = System::updateOrCreate(
            //         ['key' => 'logo'],
            //         ['value' => $data['logo'], 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            //     );
            //     $data['logo_url'] = asset('uploads/' . $data['logo']);
            //     $this->commonUtil->addSyncDataWithPos('System', $logo_setting, $data, 'POST', 'setting');
            //     unset($data['logo_url']);
            // }
            // $data['home_background_image'] = null;
            // if ($request->hasFile('home_background_image')) {
            //     $data['home_background_image'] = $this->commonUtil->ImageResizeAndUpload($request->home_background_image, 'uploads');
            // }
            // $data['breadcrumb_background_image'] = null;
            // if ($request->hasFile('breadcrumb_background_image')) {
            //     $data['breadcrumb_background_image'] = $this->commonUtil->ImageResizeAndUpload($request->breadcrumb_background_image, 'uploads');
            // }
            // $data['page_background_image'] = null;
            // if ($request->hasFile('page_background_image')) {
            //     $data['page_background_image'] = $this->commonUtil->ImageResizeAndUpload($request->page_background_image, 'uploads');
            // }

            // foreach ($data as $key => $value) {
            //     if (!empty($value)) {
            //         System::updateOrCreate(
            //             ['key' => $key],
            //             ['value' => $value, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            //         );
            //         $d = System::getProperty($key);
            //         $request->session()->put($key, $d);
            //     }
            // }


            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect($url)->with('status', $output);
    }

    public function removeImage($type)
    {
        try {
            System::where('key', $type)->update(['value' => null]);
            request()->session()->put($type, null);
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }
    function getBase64Image($Image)
    {
        $image_path = str_replace(env("APP_URL") . "/", "", $Image);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $image_path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $image_content = curl_exec($ch);
        curl_close($ch);
//    $image_content = file_get_contents($image_path);
        $base64_image = base64_encode($image_content);
        $b64image = "data:image/jpeg;base64," . $base64_image;
        return $b64image;
    }

    function getCroppedImage($img)
    {
        if (strlen($img) < 200) {
            return $this->getBase64Image($img);
        } else {
            return $img;
        }
    }
}
