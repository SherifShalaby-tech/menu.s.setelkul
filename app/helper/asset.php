<?php
use Illuminate\Support\Str;
function images_asset($path= null)
{
    // echo Str::replaceFirst('http://localhost:8000/', env('POS_SYSTEM_URL'), $path);
    $subject=$path?? asset('uploads/' . session('logo'));
    if(env('ENABLE_POS_SYNC')){
    
    return Str::replace(env('APP_URL'), env('POS_SYSTEM_URL'), $subject);

    }
    return $subject;
}