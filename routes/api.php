<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductHistory;
use App\Http\Controllers\Admin\ProductHistoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserForgotPasswordController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDistributionDataController;
use App\Http\Controllers\ProductTranslationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopeProductController;
use App\Http\Controllers\ShopTranslationController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout',[LoginController::class,'logout']);
    Route::apiResource('/shop_products',ShopeProductController::class);
  //  Route::get('/shop_product/{id}',[ShopController::class,'getShopProducts']);

});

Route::apiResource('/orders',OrderController::class);


Route::apiResource('/categories',CategoryController::class);
Route::get('/get_featured_products',[ProductController::class,'getFeaturedProducts']);
Route::get('/get_products/{id}',[ProductController::class,'getProducts']);
Route::post('/set_featured_products/{id}',[ProductController::class,'setFeaturedProduct']);
Route::post('/set_product_active/{id}',[ProductController::class,'setActive']);
Route::post('/set_order_status/{id}',[OrderController::class,'setOrderStatus']);
Route::get('/product_filter',[ProductController::class,'productFilter']);



Route::apiResource('/images',ImageController::class);
Route::apiResource('/managers',ManagerController::class);
Route::apiResource('/languages',LanguageController::class);

//product related
Route::apiResource('/products',ProductController::class);
Route::apiResource('/product_histories',ProductHistoryController::class);
Route::apiResource('/product_distribution_data',ProductDistributionDataController::class);
Route::apiResource('/user_products',UserProductController::class);


Route::apiResource('/product_translations',ProductTranslationController::class);
Route::post('/add_products/{id}',[ShopController::class,'addProducts']);
Route::post('/add_featured_products/{id}',[ProductController::class,'addFeaturedProduct']);
Route::get('/get_featured_product_detail/{id}',[ProductController::class,'getFeaturedProductDetail']);


//shop related
Route::apiResource('/shops',ShopController::class);
Route::get('/search',[ProductController::class,'search']);
Route::apiResource('/shop_translations',ShopTranslationController::class);


Route::post('/login',[LoginController ::class,'login']);
//->middleware('verified');
Route::post('/forgot',[ForgotPasswordController::class,'forgot']);
Route::post('/reset/{token}',[ResetPasswordController::class,'resetPassword']);
//user
Route::post('/user_login',[UserLoginController ::class,'login']);
//->middleware('phone_verified');
Route::post('/user_forgot',[UserForgotPasswordController::class,'forgot']);
Route::post('/user_reset/{token}',[UserForgotPasswordController::class,'resetPassword']);


//role and permission
Route::apiResource('/roles',RoleController::class);
Route::apiResource('/permissions',PermissionController::class);

//order related
Route::apiResource('/order_statuses',OrderStatusController::class);
Route::get('/order_detail/{id}',[OrderController::class,'orderDetail']);
Route::post('/assign_permission/{id}',[RoleController::class,'assignPermissions']);
Route::post('/assign_roles/{id}',[ManagerController::class,'assignRoleToEmployee']);
Route::post('/search_order',[OrderController::class,'search']);


Route::apiResource('/payment_types',PaymentTypeController::class);
Route::post('/send_sms',[UserController::class,'sendSmsNotificaition']);
Route::post('/send_sms_not',[UserController::class,'sendSms']);


Route::post('/verify_otp', [UserLoginController::class, 'checkOtp']);



Route::apiResource('/users',UserController::class);



Route::post('/accept_product_request',[ShopeProductController::class,'acceptProductRequest']);
 Route::post('/order_products',[OrderController::class,'orderProduct']);

 Route::get('/all_orders',[OrderController::class,'allOrders']);


