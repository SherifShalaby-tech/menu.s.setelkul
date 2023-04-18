<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, HasRoles;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function modulePermissionArray()
    {
        return [
            'category' => __('lang.category'),
            'product' => __('lang.product'),
            'offer' => __('lang.offer'),
            'order' => __('lang.order'),
            'messages' => __('lang.messages'),
            'sms' => __('lang.sms'),

            'settings' => __('lang.settings'),
        ];
    }
    public static function subModulePermissionArray()
    {
        return [
            'settings' => [
                'size' => __('lang.size'),
                'store' => __('lang.store'),
                'dining_room' => __('lang.dining_room'),
                'dining_table' => __('lang.dining_table'),
                'user' => __('lang.user'),
                'system_setting' => __('lang.system_setting'),
            ],

        ];
    }

    public function adminlte_image()
    {
        return !empty($this->getFirstMediaUrl('profile')) ? $this->getFirstMediaUrl('profile') : asset('/uploads/' . session('logo'));
    }

    public function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }

    public function adminlte_profile_url()
    {
        return 'admin/user/profile';
    }
}
