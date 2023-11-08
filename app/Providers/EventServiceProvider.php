<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\System;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $order_count = Order::where('status', 'pending')->count();
            $event->menu->addAfter('offers', [
                'key' => 'orders',
                'text' => 'orders',
                'route'  => 'orders.index',
                'icon' => 'fas fa-edit',
                'label' => $order_count,
                'label_color' => 'success',
            ]);

            config(['adminlte.logo_img' => images_asset()]);
            config(['adminlte.logo' => System::getProperty('site_title')]);
        });
    }
}
