<?php

use App\Events\NewOrderEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('general/switch-language/{lang}', 'GeneralController@switchLanguage');
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['SetSessionData', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'prevent-back-history']], function () {
    Route::get('/', 'HomeController@index');
    
    Route::get('sitemap.xml', [SitemapController::class, 'index' ])->name('website.sitemap');
    
    Route::get('/product/list/{category_id}', 'ProductController@getProductListByCategory');
    Route::get('/product/promotions', 'ProductController@getPromotionProducts');
    Route::resource('/product', 'ProductController');
    Route::resource('/about-us', 'AboutUsController');
    Route::get('/cart/view', 'CartController@view');
    Route::get('/cart/clear', 'CartController@clearCart');
    Route::get('/cart/remove-product/{product_id}', 'CartController@removeProduct');
    Route::get('/cart/add-to-cart/{product_id}/{size_id?}', 'CartController@addToCart');
    Route::get('/cart/add-to-cart-extra/{product_id}', 'CartController@addToCartExtra');
    Route::get('/cart/update-product-variation/{product_id}/{variation_id}', 'CartController@updateProductVariation');
    Route::get('/cart/update-product-quantity/{product_id}/{quantity}', 'CartController@updateProductQuantity');
    Route::resource('/cart', 'CartController');
    Route::resource('/order', 'OrderController');
});


require __DIR__ . '/auth.php';


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'SetSessionData']], function () {
    Auth::routes();
    Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
        Route::get('/test', function () {
            return app()->getLocale();
        });
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
        Route::get('/category/get-dropdown', 'Admin\ProductClassController@getDropdown');
        Route::resource('/category', Admin\ProductClassController::class)->name('index', 'categories.index');
        Route::get('product/delete-product-image/{id}', 'Admin\ProductController@deleteProductImage');
        Route::get('product/get-variation-row', 'Admin\ProductController@getVariationRow');
        Route::get('product/get-size-row', 'Admin\ProductController@getSizeRow');
        Route::resource('/product', Admin\ProductController::class)->name('index', 'product.index')->name('create', 'product.create');
        Route::post('user/check-password/{id}', 'Admin\UserController@checkPassword');
        Route::get('user/profile', 'Admin\UserController@getProfile');
        Route::post('user/profile', 'Admin\UserController@updateProfile');
        Route::resource('/user', Admin\UserController::class)->name('index', 'user.index')->name('create', 'user.create');
        Route::get('/offers/toggle-status/{offer_id}', 'Admin\OfferController@toggleOfferStatus');
        Route::get('/offers/get-product-dropdown-by-category/{category_id}', 'Admin\OfferController@getProductDropdownByCategory');
        Route::resource('/offers', Admin\OfferController::class)->name('index', 'offers.index')->name('create', 'offers.create');
        Route::get('size/get-dropdown', 'Admin\SizeController@getDropdown');
        Route::resource('size', Admin\SizeController::class)->name('index', 'size.index');
        Route::post('settings/remove-image/{type}', 'Admin\SettingController@removeImage');
        Route::get('system-settings', 'Admin\SettingController@getSystemSettings')->name('system.settings');
        Route::post('system-settings', 'Admin\SettingController@saveSystemSettings');
        Route::resource('/orders', Admin\OrderController::class)->name('index', 'orders.index');
        Route::post('sms/save-setting', 'Admin\SmsController@saveSetting');
        Route::get('sms/setting', 'Admin\SmsController@getSetting')->name('sms.settings');
        Route::get('sms/resend/{id}', 'Admin\SmsController@resend');
        Route::resource('/sms', 'Admin\SmsController')->name('index', 'sms.index')->name('create', 'sms.create');
        Route::get('messages/resend/{id}', 'Admin\MessageController@resend');
        Route::post('messages/save-setting', 'Admin\MessageController@saveSetting');
        Route::get('messages/setting', 'Admin\MessageController@getSetting')->name('messages.settings');
        Route::resource('/messages', 'Admin\MessageController')->name('index', 'messages.index')->name('create', 'messages.create');
        Route::resource('/store', 'Admin\StoreController')->name('index', 'store.index')->name('create', 'store.create');
        Route::resource('/customer-type', 'Admin\CustomerTypeController')->name('index', 'customer_type.index')->name('create', 'customer_type.create');
        Route::post('general/upload-image-temp', 'Admin\GeneralController@uploadImageTemp');
        Route::resource('/dining-room', 'Admin\DiningRoomController')->name('index', 'dining_room.index')->name('create', 'dining_room.create');
        Route::resource('/dining-table', 'Admin\DiningTableController')->name('index', 'dining_table.index')->name('create', 'dining_table.create');
    });
});



Route::get('/clear-cache', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');

    echo 'cache cleared!';
});
