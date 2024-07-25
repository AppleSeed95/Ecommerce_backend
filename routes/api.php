<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\MenuController;
use App\Http\Controllers\api\V1\BannerController;
use App\Http\Controllers\api\V1\YtubeController;
use App\Http\Controllers\api\V1\ProductController;
use App\Http\Controllers\api\V1\CategoriesController;
use App\Http\Controllers\api\V1\CollectionController;
use App\Http\Controllers\api\V1\BlogController;
use App\Http\Controllers\api\V1\SettingsController;
use App\Http\Controllers\api\V1\PageController;
use App\Http\Controllers\api\V1\SubscriptionController;
use App\Http\Controllers\api\V1\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();


// });


Route::group([
    'prefix'=>'v1',
    // 'namespace'=> 'api\v1',
    'middleware'=> ['auth:api']
], function(){
    Route::name('api.')->group(function(){
        Route::prefix('menu')->controller(MenuController::class)->group(function(){
            Route::post('/save-menu', 'saveMenu')->name('save-menu');
            Route::post('/menus', 'listMenu')->name('listMenu');
            Route::post('/delete', 'destroy')->name('destroyMenu');
        });

        Route::prefix('banners')->controller(BannerController::class)->group(function(){
            // Route::get('/{bannerGroup?}', 'getBanner')->name('banner');
            Route::post('/banner', 'listBanner')->name('listBanner');
            Route::post('/delete', 'destroy')->name('destroyBanner');
        });

        Route::prefix('yt')->controller(YtubeController::class)->group(function(){
            Route::post('/save', 'save')->name('save-video');
            Route::post('/videos', 'listVideo')->name('listVideo');
            Route::post('/delete', 'destroy')->name('destroyYTVideo');
        });

        Route::prefix('product')->controller(ProductController::class)->group(function(){
            Route::post('/', 'listProducts')->name('listProducts');
            Route::post('/delete', 'destroy')->name('destroyProduct');
            Route::post('/deleteImg', 'destroyImg')->name('destroyProductImg');
            Route::get('/{slug}', 'getProdcut')->name('product');
        });
        
        Route::prefix('category')->controller(CategoriesController::class)->group(function(){
            Route::post('/categories', 'listCategory')->name('listCategory');
            Route::get('/categories/*', 'listCategory')->name('cat-menus');
            Route::post('/save', 'store')->name('save-category');
            Route::post('/delete', 'destroy')->name('destroyCategory');
        });
        
        Route::prefix('collections')->controller(CollectionController::class)->group(function(){
            Route::post('/', 'listCollection')->name('listCollection');
            Route::get('/categories/*', 'listCategory')->name('coll-menus');
            Route::post('/save', 'store')->name('save-collection');
            Route::post('/delete', 'destroy')->name('destroyCollection');
        });

        Route::prefix('blogs')->controller(BlogController::class)->group(function(){
            Route::post('/', 'listBlogs')->name('listBlogs');
            Route::get('/{slug}', 'getBlog')->name('Blog');
            Route::post('/delete', 'destroy')->name('destroyBlog');
            Route::post('/deleteImg', 'destroyImg')->name('destroyBlogImg');
        });

        Route::prefix('pages')->controller(PageController::class)->group(function(){
            Route::post('/', 'listPages')->name('listPages');
            Route::get('/{slug}', 'getPage')->name('Page');
            Route::post('/delete', 'destroy')->name('destroyPage');
            Route::post('/deleteImg', 'destroyImg')->name('destroyPageImg');

        });

        Route::prefix('users')->controller(UsersController::class)->group(function(){
            Route::post('/', 'listUsers')->name('listUsers');
            Route::post('/save', 'save')->name('save-user');
            Route::post('/delete', 'destroy')->name('destroyUser');
        });

        Route::controller(SubscriptionController::class)->group(function(){
            Route::post('/contact-us', 'contactUs')->name('contact-us');
            Route::post('/subscriptions', 'subscriptions')->name('subscriptions');
        });

    });
});

Route::group([
    'prefix'=>'react/v1',
    // 'namespace'=> 'api\v1',
    // 'middleware'=> ['auth:api']
], function(){
    Route::name('react.')->group(function(){
        
        Route::prefix('menu')->controller(MenuController::class)->group(function(){
            Route::get('/', 'reactMenus')->name('react_menu');
        });
        
        Route::prefix('banners')->controller(BannerController::class)->group(function(){
            Route::get('/{bannerType}/{bannerGroup}', 'getBanner')->name('banner');
            Route::post('/banner', 'listBanner')->name('listBanner');
        });
        
        Route::prefix('videos')->controller(YtubeController::class)->group(function(){
            Route::get('/', 'reactVideo')->name('videos');
        });
        
        Route::prefix('products')->controller(ProductController::class)->group(function(){
            Route::post('/category/{slug?}', 'getProducts')->name('catProducts');
            Route::post('/tags', 'getProductTags')->name('productTags');
            Route::get('/feturedProducts/{count?}', 'getFeturedProds')->name('feturedProds');
            Route::get('/homepageRrecentProducts', 'getRecentProductsByCats')->name('getRecentProductsByCats');
            Route::any('/{slug}', 'getProduct')->name('product');
        });

        Route::prefix('category')->controller(CategoriesController::class)->group(function(){
            Route::post('/categories', 'categoryListCount')->name('reactCategoryCount');
            Route::get('/categories/{slug}', 'getCategoryDetails')->name('reactCategoryDetails');
        });

        Route::prefix('collections')->controller(CollectionController::class)->group( function(){
            Route::get('/filters', 'getFilterCollection')->name('filterCollection');
        });

        Route::prefix('blogs')->controller(BlogController::class)->group(function(){
            Route::get('/blog/{page?}', 'latestBlogs')->name('latestBlogs')->where('page', '[0-9]+');
            Route::get('/blog/{slug?}', 'getBlog')->name('getBlog');
            Route::get('/blog/tags/{slug?}', 'getTagBlogs')->name('getTagBlog');
            Route::get('/homepageListing', 'homepageListing')->name('homepageListing');
            Route::get('/tags', 'getBlogTags')->name('blogTags');
        });


        Route::prefix('setting')->controller(SettingsController::class)->group(function(){
            Route::get('/', 'settings')->name('settings');
            Route::get('/{key?}', 'setting')->name('setting');
        });

        Route::prefix('pages')->controller(PageController::class)->group(function(){
            Route::get('/homepageAboutBollard', 'aboutBollard')->name('aboutBollard');
            Route::get('/footerData', 'footerData')->name('footerData');
            Route::get('/search', 'globleSearch')->name('globle-search');
            Route::get('/{page?}', 'getPages')->name('getPages');
        });


        Route::prefix('subscribe')->controller(SubscriptionController::class)->group(function(){
            Route::post('/save', 'save')->name('subscribe');
            Route::post('/saveContact', 'saveContactUs')->name('save-Contact-us');

        });
        


    });
    
});


