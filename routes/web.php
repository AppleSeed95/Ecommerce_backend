<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\YtubeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscriptionController;

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

Route::middleware(['auth'])->group(function(){
    
    // Route::prefix('admin')->group(function(){
        Route::get('/', [HomeController::class, 'index' ])->name('home');
        // Route::get('/logout', function(){})->name('logout');

        Route::controller(DashboardController::class)->group(function(){
            Route::get('/dashboard', 'index')->name('dashboard');
        });

        Route::controller(UsersController::class)->group(function(){
            Route::get('/users', 'index')->name('users');
            Route::get('/add-user/{id?}', 'add')->name('add-user');
        });

        Route::controller(MenuController::class)->group(function(){
            Route::get('/menus', 'index')->name('menus');
            Route::post('/save-menu', 'saveMenu')->name('save-menu');
        });

        Route::controller(BannerController::class)->group(function(){
            Route::get('/banner', 'index')->name('banners');
            Route::post('/save-banner', 'saveBanner')->name('save-banner');
            Route::get('/form-banner/{id?}', 'showBannerFrm')->name('bannerFrm');
        });

        Route::controller(YtubeController::class)->group(function(){
            Route::get('/videos', 'index')->name('videos');
        });

        Route::controller(ProductController::class)->group(function(){
            Route::get('/products', 'index')->name('products');
            Route::post('/save-product', 'store')->name('save-product');
            Route::get('/add/{id?}', 'create')->name('productFrm');
        });

        Route::controller(CategoriesController::class)->group(function(){
            Route::get('/categories', 'index')->name('categories');
        });
        
        Route::controller(CollectionController::class)->group(function(){
            Route::get('/collections', 'index')->name('collections');
        });

        Route::controller(BlogController::class)->group(function(){
            Route::get('/blogs', 'index')->name('blogs');
            Route::post('/save-blog', 'store')->name('save-blog');
            Route::get('/create/{id?}', 'create')->name('blogFrm');
        });

        Route::controller(SettingsController::class)->group(function(){
            Route::get('/settings', 'index')->name('settings');
            Route::post('/save-sattings', 'store')->name('save-sattings');
        });

        Route::prefix('pages')->controller(PageController::class)->group(function(){
            Route::get('/', 'index')->name('pages');
            Route::post('/save-page', 'store')->name('save-page');
            Route::get('/create/{id?}', 'create')->name('pageFrm');
        });


        Route::controller(SubscriptionController::class)->group(function(){
            Route::get('/contact-us', 'contactUs')->name('contact-us');
            Route::get('/subscriptions', 'subscriptions')->name('subscriptions');
            
        });

    // });


});


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/store', [LoginController::class, 'store'])->name('store');
Route::post('/logout', ['logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout1');


// Route::get('/', function () {
//     return view('welcome');
// });

