<?php
use Illuminate\Support\Str;
function images_asset($path= null)
{
    if ($path==null){
        return $subject=images_asset(env('APP_URL').'/uploads/' . session('logo'));
    }
    $subject=$path;
    if(env('ENABLE_POS_SYNC')){
    
    return Str::replace(env('APP_URL'), env('POS_SYSTEM_URL'), $subject);

    }
    return $subject;
}