<?php

namespace App\Providers;

use App\Models\System;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Blade directive to format number into required format.
        Blade::directive('num_format', function ($expression) {
            $currency_precision =  2;
            return "number_format($expression,  $currency_precision, '.', ',')";
        });

        //Blade directive to convert.
        Blade::directive('format_date', function ($date = null) {
            if (!empty($date)) {
                return "Carbon\Carbon::createFromTimestamp(strtotime($date))->format('m/d/Y')";
            } else {
                return null;
            }
        });

        //Blade directive to convert.
        Blade::directive('format_time', function ($date) {
            if (!empty($date)) {
                $time_format = 'h:i A';
                if (System::getProperty('time_format') == 24) {
                    $time_format = 'H:i';
                }
                return "\Carbon\Carbon::createFromTimestamp(strtotime($date))->format('$time_format')";
            } else {
                return null;
            }
        });

        Blade::directive('format_datetime', function ($date) {
            if (!empty($date)) {
                $time_format = 'h:i A';
                if (System::getProperty('time_format') == 24) {
                    $time_format = 'H:i';
                }

                return "\Carbon\Carbon::createFromTimestamp(strtotime($date))->format('m/d/Y ' . '$time_format')";
            } else {
                return null;
            }
        });

        //Blade directive to format currency.
        Blade::directive('format_currency', function ($number) {
            return '<?php
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["code"] . " ";
            }
            $formated_number .= number_format((float) ' . $number . ', config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["code"];
            }
            echo $formated_number; ?>';
        });

        //Blade directive to return appropiate class according to attendance status
        Blade::directive('attendance_status', function ($status) {
            return "<?php if($status == 'late'){
                    echo 'badge-warning';
                }elseif($status == 'on_leave'){
                    echo 'badge-danger';
                }elseif ($status == 'present') {
                    echo 'badge-success';
                }?>";
        });

        //Blade directive to convert.
        Blade::directive('replace_space', function ($string) {
            return "str_replace(' ', '_', $string)";
        });
        $path = base_path('.env');
        $test = file_get_contents($path);
        if(session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket')
        {   
            if (Schema::hasTable('systems'))
            {
                $new_app_url=System::getProperty('pos');
                $current_url=config('app.url');
                if(!empty($new_app_url) ){
                    $new_url='APP_URL='.$new_app_url;
                    $old_url='APP_URL='.$current_url;
                    if($new_url !==$old_url){
                    file_put_contents($path , str_replace($old_url,$new_url , $test));
                    }
                }
            }
        }
        
        // $path = base_path('.env');
        // $test = file_get_contents($path);
        // $new_app_url=System::getProperty('pos');
        // if(!empty($new_app_url)){
        //     $new_url='APP_URL='.$new_app_url;
        //     // file_put_contents($path , str_replace('APP_URL=https://setelkul.sherifshalaby.tech/',$new_url , $test));
        //     file_put_contents($path , str_replace('APP_URL=http://localhost:8000',$new_url , $test));
        // }
        // else{
        //     file_put_contents($path , str_replace( 'APP_URL=https://s.elhabib.sherifshalaby.tech','APP_URL=http://localhost:8000', $test));
        // }
    }
}
