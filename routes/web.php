<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\backend\AffiliateController;
use App\Http\Controllers\backend\BlogController as BackendBlogController;
use App\Http\Controllers\backend\BrandController as BackendBrandController;
use App\Http\Controllers\backend\CommentController as BackendCommentController;
use App\Http\Controllers\backend\MarketplaceController;
use App\Http\Controllers\backend\OptionController;
use App\Http\Controllers\backend\SpecController;
use App\Http\Controllers\backend\StickyArticleController;
use App\Http\Controllers\Backend\TipsAndTrickController;
use App\Http\Controllers\backend\TypeController as BackendTypeController;
use App\Http\Controllers\backend\VehicleController as BackendVehicleController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\FinderController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\backend\DashboardController;


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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/privacy-policy', [PrivacyController::class, 'index'])->name('privacy.index');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('auth.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('auth.login.store');

    Route::get('auth/google/redirect', [AuthenticatedSessionController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('auth/google/callback', [AuthenticatedSessionController::class, 'handleGoogleCallback']);
});


Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('change-password', [AuthenticatedSessionController::class, 'newPassword'])->name('password.change');
    Route::post('change-password', [AuthenticatedSessionController::class, 'changePassword']);
    //wishlist
    Route::post('/vehicles/{id}/love', 'App\Http\Controllers\VehicleController@loveVehicle')->name('vehicle.love');
    Route::get('/keranjang', 'App\Http\Controllers\KeranjangController@index')->name('keranjang.index');
    Route::delete('/keranjang/{vehicleId}', [KeranjangController::class, 'remove'])->name('keranjang.remove');
    //wishlist

    Route::middleware('role:1')->group(function () {
        // Backend routes only for users with role 1
        Route::prefix('backend')->group(function () {
      
            // Option
            Route::get('/dashboard', [OptionController::class, 'dashboard'])->name('backend.dashboard');
            Route::put('/option', [OptionController::class, 'update'])->name('backend.option.update');
            Route::get('/option', [OptionController::class, 'index'])->name('backend.option.index');

            // Type
            Route::get('/type/create', [BackendTypeController::class, 'create'])
                ->name('backend.type.create');
            Route::post('/type/store', [BackendTypeController::class, 'store'])
                    ->name('backend.type.store');
            Route::get('/type/{type}/edit', [BackendTypeController::class, 'edit'])
                    ->name('backend.type.edit');
            Route::put('/type/{type}', [BackendTypeController::class, 'update'])
                    ->name('backend.type.update');
            Route::delete('bakcend/type/{type}', [BackendTypeController::class, 'destroy'])
                    ->name('backend.type.destroy');
            Route::get('/type', [BackendTypeController::class, 'index'])
                    ->name('backend.type.index');

            // Brand
            Route::get('/brand/create', [BackendBrandController::class, 'create'])
                ->name('backend.brand.create');
            Route::post('/brand/store', [BackendBrandController::class, 'store'])
                    ->name('backend.brand.store');
            Route::get('/brand/{brand}/edit', [BackendBrandController::class, 'edit'])
                    ->name('backend.brand.edit');
            Route::put('/brand/{brand}', [BackendBrandController::class, 'update'])
                    ->name('backend.brand.update');
            Route::delete('bakcend/brand/{brand}', [BackendBrandController::class, 'destroy'])
                    ->name('backend.brand.destroy');
            Route::get('/brand', [BackendBrandController::class, 'index'])
                    ->name('backend.brand.index');

            // Blog
            Route::get('/blog/create', [BackendBlogController::class, 'create'])
                ->name('backend.blog.create');
            Route::post('/blog/store', [BackendBlogController::class, 'store'])
                    ->name('backend.blog.store');
            Route::get('/blog/{blog}/edit', [BackendBlogController::class, 'edit'])
                    ->name('backend.blog.edit');
            Route::put('/blog/{blog}', [BackendBlogController::class, 'update'])
                    ->name('backend.blog.update');
            Route::delete('backend/blog/{blog}', [BackendBlogController::class, 'destroy'])
                    ->name('backend.blog.destroy');
            Route::get('/blog', [BackendBlogController::class, 'index'])
                    ->name('backend.blog.index');

            // Sticky Article
            Route::post('/sticky-articles/store/{blog}', [StickyArticleController::class, 'store'])
                ->name('backend.stickyArticle.store');
            Route::delete('/sticky-articles/{stickyArticle}', [StickyArticleController::class, 'destroy'])
                    ->name('backend.stickyArticle.destroy');
            Route::get('/sticky-articles', [StickyArticleController::class, 'index'])
                    ->name('backend.stickyArticle.index');

            // Tips and Trick
            Route::post('/tips-and-trick/store/{blog}', [TipsAndTrickController::class, 'store'])
                    ->name('backend.tipsAndTrick.store');
           Route::delete('/tips-and-trick/{tipsAndTrick}', [TipsAndTrickController::class, 'destroy'])
                ->name('backend.tipsAndTrick.destroy');
            Route::get('/tips-and-trick', [TipsAndTrickController::class, 'index'])
                    ->name('backend.tipsAndTrick.index');

                
            // Spec
            Route::prefix('spec')->group(function () {
                Route::get('category/create', [SpecController::class, 'createCategory'])->name('backend.spec.category.create');
                Route::post('category/store', [SpecController::class, 'storeCategory'])->name('backend.spec.category.store');
                Route::get('category/{spec}/edit', [SpecController::class, 'editCategory'])->name('backend.spec.category.edit');
                Route::put('category/{spec}', [SpecController::class, 'updateCategory'])->name('backend.spec.category.update');
                Route::delete('category/{spec}', [SpecController::class, 'destroyCategory'])->name('backend.spec.category.destroy');

                Route::get('spec/create', [SpecController::class, 'createSpec'])->name('backend.spec.spec.create');
                Route::post('spec/store', [SpecController::class, 'storeSpec'])->name('backend.spec.spec.store');
                Route::get('spec/{spec}/edit', [SpecController::class, 'editSpec'])->name('backend.spec.spec.edit');
                Route::put('spec/{spec}', [SpecController::class, 'updateSpec'])->name('backend.spec.spec.update');
                Route::delete('spec/{spec}', [SpecController::class, 'destroySpec'])->name('backend.spec.spec.destroy');
            });

            Route::get('/spec', [SpecController::class, 'index'])->name('backend.spec.index');

            // Vehicle
            Route::get('/vehicle/create', [BackendVehicleController::class, 'create'])
                ->name('backend.vehicle.create');
            Route::post('/vehicle/store', [BackendVehicleController::class, 'store'])
                    ->name('backend.vehicle.store');
            Route::get('/vehicle/{vehicle}/edit', [BackendVehicleController::class, 'edit'])
                    ->name('backend.vehicle.edit');
            Route::put('/vehicle/{vehicle}', [BackendVehicleController::class, 'update'])
                    ->name('backend.vehicle.update');
            Route::delete('backend/vehicle/{vehicle}', [BackendVehicleController::class, 'destroy'])
                    ->name('backend.vehicle.destroy');
            Route::get('/vehicle', [BackendVehicleController::class, 'index'])
                    ->name('backend.vehicle.index');

            // Affiliate Link
            Route::get('/affiliate/create/{vehicle}', [AffiliateController::class, 'create'])
                ->name('backend.affiliate.create');
            Route::post('/affiliate/store/{vehicle}', [AffiliateController::class, 'store'])
                    ->name('backend.affiliate.store');
            Route::get('/affiliate/{affiliate}/{vehicle}/edit', [AffiliateController::class, 'edit'])
                    ->name('backend.affiliate.edit');
            Route::put('/affiliate/{affiliate}', [AffiliateController::class, 'update'])
                    ->name('backend.affiliate.update');
            Route::delete('backend/affiliate/{affiliate}', [AffiliateController::class, 'destroy'])
                    ->name('backend.affiliate.destroy');
            Route::get('/affiliate/{vehicle}', [AffiliateController::class, 'show'])
                    ->name('backend.affiliate.show');

            // Marketplace
            Route::get('/marketplace/create', [MarketplaceController::class, 'create'])
                ->name('backend.marketplace.create');
            Route::post('/marketplace/store', [MarketplaceController::class, 'store'])
                    ->name('backend.marketplace.store');
            Route::get('/marketplace/{marketplace}/edit', [MarketplaceController::class, 'edit'])
                    ->name('backend.marketplace.edit');
            Route::put('/marketplace/{marketplace}', [MarketplaceController::class, 'update'])
                    ->name('backend.marketplace.update');
            Route::delete('backend/marketplace/{marketplace}', [MarketplaceController::class, 'destroy'])
                    ->name('backend.marketplace.destroy');
            Route::get('/marketplace', [MarketplaceController::class, 'index'])
                    ->name('backend.marketplace.index');

            // Comment
            Route::put('/comment/moderate-name/{comment}', [BackendCommentController::class, 'moderateName'])
                ->name('backend.comment.moderate.name');
            Route::put('/comment/moderate-comment/{comment}', [BackendCommentController::class, 'moderateComment'])
                    ->name('backend.comment.moderate.comment');
            Route::delete('/comment/{comment}', [BackendCommentController::class, 'destroy'])
                    ->name('backend.comment.destroy');
            Route::get('/comment', [BackendCommentController::class, 'index'])
                    ->name('backend.comment.index');
        });
    });
});




// Compare
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare', [CompareController::class, 'index'])->name('compare.result');

// Finder
Route::get('/finder', [FinderController::class, 'index'])->name('finder.index');
Route::get('/finder/search', [FinderController::class, 'search'])->name('finder.search');
Route::get('/finder/brands', [FinderController::class, 'getBrandsByType'])->name('finder.getBrandsByType');

// Frontend Route
Route::get('/cari', [HomeController::class, 'search'])->name('search');
Route::get('/berita/{blog}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/berita', [BlogController::class, 'index'])->name('blog.index');
Route::get('/merek/{brand}', [BrandController::class, 'show'])->name('brand.show');
Route::get('/merek', [BrandController::class, 'index'])->name('brand.index');
Route::get('/tipe/{type}', [TypeController::class, 'show'])->name('type.show');
Route::get('/tipe', [TypeController::class, 'index'])->name('type.index');
Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('vehicle.show');
Route::post('/comment/store', [CommentController::class, 'store'])->name('comment.post');
Route::post('/vehicle/toggle-love/{id}', [VehicleController::class, 'toggleLove'])->name('vehicle.toggleLove');

// Tips and Trick
Route::get('/tips-and-trick', [TipsAndTrickController::class, 'index'])->name('tips.index');
Route::get('/tips-and-trick/{tips}', [TipsAndTrickController::class, 'show'])->name('tips.show');
