<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductHistory;
use App\Http\Controllers\Admin\ProductHistoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDistributionDataController;
use App\Http\Controllers\ProductTranslationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopTranslationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    Route::post('/logout',[LoginController::class,'logout']);


});

Route::apiResource('/categories',CategoryController::class);
Route::get('/get_featured_products',[ProductController::class,'getFeaturedProducts']);
Route::get('/get_products/{id}',[ProductController::class,'getProducts']);

Route::apiResource('/images',ImageController::class);
Route::apiResource('/managers',ManagerController::class);
Route::apiResource('/languages',LanguageController::class);

Route::apiResource('/products',ProductController::class);
Route::apiResource('/product_histories',ProductHistoryController::class);
Route::apiResource('/product_distribution_data',ProductDistributionDataController::class);

Route::apiResource('/product_translations',ProductTranslationController::class);
Route::post('/add_products/{id}',[ShopController::class,'addProducts']);
Route::post('/add_featured_products/{id}',[ProductController::class,'addFeaturedProduct']);
Route::get('/get_featured_product_detail/{id}',[ProductController::class,'getFeaturedProductDetail']);



Route::get('/shop_products/{id}',[ShopController::class,'getShopProducts']);
Route::apiResource('/shops',ShopController::class);
Route::get('/search',[ProductController::class,'search']);
Route::apiResource('/shop_translations',ShopTranslationController::class);


Route::post('/login',[LoginController ::class,'login']);
//->middleware('verified');
Route::post('/forgot',[ForgotPasswordController::class,'forgot']);
Route::post('/reset/{token}',[ResetPasswordController::class,'resetPassword']);


Route::apiResource('/roles',RoleController::class);
Route::apiResource('/permissions',PermissionController::class);


Route::apiResource('/order_statuses',OrderStatusController::class);
Route::apiResource('/orders',OrderController::class);

