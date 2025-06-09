<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\AppToken;
use Carbon\Carbon;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\FinderController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ListController;
use App\Http\Controllers\Api\ChargerStationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\SpecTestController;
use App\Http\Controllers\Api\FavoriteController;

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

/*
|--------------------------------------------------------------------------
| App Authentication
|--------------------------------------------------------------------------
*/
// Middleware untuk memverifikasi app key
Route::post('/app-handshake', function (Request $request) {
    $request->validate([
        'device_id' => 'required|string',
        'platform' => 'required|string',
    ]);

    $deviceId = $request->device_id;
    $platform = $request->platform;
    $expiryDays = 7;

    $token = AppToken::where('device_id', $deviceId)->first();

    if ($token) {
        // Cek apakah token expired
        $expired = $token->created_at->lt(Carbon::now()->subDays($expiryDays));
        if (!$expired) {
            // Token masih valid, kembalikan token lama
            return response()->json(['app_key' => $token->app_key]);
        }
    }

    // Token belum ada atau expired, buat token baru
    $appKey = Str::random(40);

    $token = AppToken::updateOrCreate(['device_id' => $deviceId], ['app_key' => $appKey, 'platform' => $platform, 'created_at' => Carbon::now()]);

    return response()->json(['app_key' => $appKey]);
});

/*
|--------------------------------------------------------------------------
| User Authentication Routes
|--------------------------------------------------------------------------
*/
// Auth
Route::prefix('auth')->group(function () {
    // Login
    Route::post('/login', [LoginController::class, 'login']);
    // Login with google
    Route::post('/google/login', [LoginController::class, 'loginWithGoogle']);
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});

// Get authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| App Key Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('verify.app.key')->group(function () {
    //Home
    Route::get('/', [HomeController::class, 'index']);

    /*
    | Blog Routes
    */
    Route::get('/berita/{blog}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/berita', [BlogController::class, 'index'])->name('blog.index');
    Route::post('/blogs', [BlogController::class, 'store']);

    /*
    | Authenticated User Routes
    */
    Route::middleware('auth:sanctum')->group(function () {
        // Favorites
        Route::get('/favorites', [FavoriteController::class, 'index']);  
        Route::post('/favorites', [FavoriteController::class, 'store']);  
        Route::delete('/favorites/{vehicleId}', [FavoriteController::class, 'remove']);  
    });

    /*
    | Charger Station Routes
    */
    Route::get('/charger/search', [ChargerStationController::class, 'search'])->name('charger.search');

    /*
    | Location Routes
    */
    Route::get('/cities', [ChargerStationController::class, 'getCities']);
    Route::get('/cities/search', [ChargerStationController::class, 'searchCities']);
});

/*
|--------------------------------------------------------------------------
| Testing Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v2')->group(function () {
    Route::get('/spec-categories', [SpecTestController::class, 'getSpecCategories']);
    Route::get('/specs', [SpecTestController::class, 'getSpecs']);
    Route::get('/spec-lists', [SpecTestController::class, 'getSpecLists']);
});

/*
|--------------------------------------------------------------------------
| Other Routes
|--------------------------------------------------------------------------
*/
// Search
Route::get('/cari', [HomeController::class, 'search'])->name('search');

/*
| Finder Routes
*/
Route::get('/find/search', [FinderController::class, 'show'])->name('finder.show');
Route::get('/finder', [FinderController::class, 'index'])->name('finder.index');
Route::get('/finder/search', [FinderController::class, 'search'])->name('finder.search');

/*
| Brand Routes
*/
Route::get('/merek/{brand}', [BrandController::class, 'show'])->name('brand.show');
Route::get('/merek', [BrandController::class, 'index'])->name('brand.index');
Route::post('/merek', [BrandController::class, 'store']);
 
/*
| Type Routes
*/
Route::get('/tipe', [TypeController::class, 'index'])->name('type.index');
Route::get('/tipe/{type}', [TypeController::class, 'show'])->name('type.show'); 

/*
| Vehicle Routes
*/
Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('vehicle.show');
Route::post('/vehicles', [VehicleController::class, 'store']);
Route::get('/vehicles/{slug}/lists', [ListController::class, 'show']);

/*
| Comment Routes
*/
Route::post('/comment/store', [CommentController::class, 'storeApi'])->name('comment.post');
