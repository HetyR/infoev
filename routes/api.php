<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\FinderController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Api\LoginController; // Perbaiki penamaan class
use App\Http\Controllers\Api\ListController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;


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

Route::get('/', [HomeController::class, 'index']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    // Search
    Route::get('/cari', [HomeController::class, 'search'])
        ->name('search');

    // Finder
    Route::get('/find', [FinderController::class, 'ind'])->name('finder.ind');
    Route::get('/find/search', [FinderController::class, 'show'])->name('finder.show');
    Route::get('/finder', [FinderController::class, 'index'])->name('finder.index');
    Route::get('/finder/search', [FinderController::class, 'search'])->name('finder.search');

    // Blog
    Route::get('/berita/{blog}', [BlogController::class, 'show'])
        ->name('blog.show');
    Route::get('/berita', [BlogController::class, 'index'])
        ->name('blog.index');

    // Brand
    Route::get('/merek/{brand}', [BrandController::class, 'show'])
        ->name('brand.show');
    Route::get('/merek', [BrandController::class, 'index'])
        ->name('brand.index');

    // Type
    Route::get('/tipe/{type}', [TypeController::class, 'show'])
        ->name('type.show');
    Route::get('/tipe', [TypeController::class, 'index'])
        ->name('type.index');

    // Vehicle
    Route::get('/{vehicle}', [VehicleController::class, 'show'])
        ->name('vehicle.show');
    Route::post('/comment/store', [CommentController::class, 'store'])
        ->name('comment.post');

    Route::get('/vehicles/{slug}/lists', [ListController::class, 'show']);

