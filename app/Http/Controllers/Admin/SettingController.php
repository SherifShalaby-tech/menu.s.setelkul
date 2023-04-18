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
            if ($request->hasFile('logo')) {
                $data['logo'] = $this->commonUtil->ImageResizeAndUpload($request->logo, 'uploads', 250, 250);
                $logo_setting = System::updateOrCreate(
                    ['key' => 'logo'],
                    ['value' => $data['logo'], 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
                );
                $data['logo_url'] = asset('uploads/' . $data['logo']);
                $this->commonUtil->addSyncDataWithPos('System', $logo_setting, $data, 'POST', 'setting');
                unset($data['logo_url']);
            }
            $data['home_background_image'] = null;
            if ($request->hasFile('home_background_image')) {
                $data['home_background_image'] = $this->commonUtil->ImageResizeAndUpload($request->home_background_image, 'uploads');
            }
            $data['breadcrumb_background_image'] = null;
            if ($request->hasFile('breadcrumb_background_image')) {
                $data['breadcrumb_background_image'] = $this->commonUtil->ImageResizeAndUpload($request->breadcrumb_background_image, 'uploads');
            }
            $data['page_background_image'] = null;
            if ($request->hasFile('page_background_image')) {
                $data['page_background_image'] = $this->commonUtil->ImageResizeAndUpload($request->page_background_image, 'uploads');
            }

            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    System::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
                    );
                    $d = System::getProperty($key);
                    $request->session()->put($key, $d);
                }
            }


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

        return redirect()->back()->with('status', $output);
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
}
