<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductHistory;
use App\Http\Controllers\Admin\ProductHistoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\AgentLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserForgotPasswordController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDistributionDataController;
use App\Http\Controllers\ProductTranslationController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopDashbordController;
use App\Http\Controllers\ShopeProductController;
use App\Http\Controllers\ShopTranslationController;
use App\Http\Controllers\SubscriptionEmailController;
use App\Http\Controllers\SystemUserController;
use App\Http\Controllers\User\OrderController as UserOrderController;
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

  
  Route::middleware(['system_user'])->group(function () {

        ////////dashboard
    Route::get('/dashboard',[DashboardController::class,'getData']);
    Route::get('/dashboard_bargraph',[DashboardController::class,'getBarGraphData']);
    Route::get('/dashboard_piechart',[DashboardController::class,'getPichartData']);


    ////////revuenue
    Route::get('/revenue',[RevenueController::class,'getData']);
    Route::get('/revenue_bargraph',[RevenueController::class,'getBarGraphData']);
    Route::get('/revenue_piechart',[RevenueController::class,'getPichartData']);


  });

  Route::middleware(['customer'])->group(function () {

  });


  Route::middleware(['agent'])->group(function () {

      ////////dashboard
      Route::get('/shop_dashboard',[ShopDashbordController::class,'getData']);
      Route::get('/shop_dashboard_bargraph',[ShopDashbordController::class,'getBarGraphData']);
      Route::get('/shop_dashboard_piechart',[ShopDashbordController::class,'getPichartData']);

  });

    Route::post('/logout',[LoginController::class,'logout']);
    Route::apiResource('/shop_products',ShopeProductController::class);
  //  Route::get('/shop_product/{id}',[ShopController::class,'getShopProducts']);
  Route::apiResource('/orders',OrderController::class);
  Route::post('/add_user_order',[UserOrderController::class,'addUserOrder']);
  
  Route::post('/search_shop_order',[OrderController::class,'searchShopOrder']);
  Route::get('/search_shop_products',[ShopeProductController::class,'searchProductShop']);


  Route::get('/shop_users',[UserController::class,'getShopUser']);
  Route::post('/accept_product_request',[ShopeProductController::class,'acceptProductRequest']);
  Route::post('/order_products',[OrderController::class,'orderProduct']);
  Route::get('/all_orders',[OrderController::class,'allOrders']);
  Route::post('/change_password',[LoginController::class,'changePassword']);

  //////////////////////===================below are userside route==============////////////
  Route::post('/change_user_password',[UserLoginController::class,'changePassword']);



});
Route::get('/user_orders/{user_id}',[UserOrderController::class,'getUserOrders']);
Route::get('/user_order_address/{user_id}',[UserOrderController::class,'getUserOrderAddress']);

Route::get('/sales',[SalesController::class,'getSales']);
Route::apiResource('/users',UserController::class);
Route::post('/add_user_by_admin',[UserController::class,'registerUserAdminSide']);

Route::apiResource('/reviews',ReviewController::class);



Route::apiResource('/system_users',SystemUserController::class);

/////
//Route::apiResource('/orders',OrderController::class);


Route::apiResource('/categories',CategoryController::class);
Route::get('/category_detail/{id}',[CategoryController::class,'categoryDetail']);
Route::get('/get_featured_products',[ProductController::class,'getFeaturedProducts']);
Route::get('/get_products/{id}',[ProductController::class,'getProducts']);
Route::post('/set_featured_products/{id}',[ProductController::class,'setFeaturedProduct']);
Route::post('/set_product_active/{id}',[ProductController::class,'setActive']);
Route::post('/set_order_status/{id}',[OrderController::class,'changeOrderStatus']);
Route::get('/product_filter',[ProductController::class,'productFilter']);

Route::apiResource('/images',ImageController::class);
Route::apiResource('/managers',ManagerController::class);
Route::apiResource('/languages',LanguageController::class);

//product related
Route::apiResource('/products',ProductController::class);
Route::apiResource('/product_histories',ProductHistoryController::class);
Route::apiResource('/product_distribution_data',ProductDistributionDataController::class);
Route::apiResource('/user_products',UserProductController::class);
Route::post('/change_user_status/{id}',[UserController ::class,'changeUserStatus']);
Route::post('/change_system_user_status/{id}',[SystemUserController ::class,'changeUserStatus']);


Route::apiResource('/product_translations',ProductTranslationController::class);
Route::post('/add_products/{id}',[ShopController::class,'addProducts']);
Route::post('/add_featured_products/{id}',[ProductController::class,'addFeaturedProduct']);
Route::get('/get_featured_product_detail/{id}',[ProductController::class,'getFeaturedProductDetail']);

Route::post('/get_featured_product_translation_detail/{id}',[ProductController::class,'getFeaturedProductDetailTranslate']);

//shop related
Route::apiResource('/shops',ShopController::class);
Route::get('/user_shops',[ShopController::class,'getShopsForUserSide']);
Route::get('/search',[ProductController::class,'search']);
Route::get('/search_from_user/{id}',[ProductController::class,'searchFromUser']);

Route::apiResource('/shop_translations',ShopTranslationController::class);

Route::post('/login',[LoginController ::class,'login']);
Route::post('/agent_login',[AgentLoginController ::class,'login']);
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
Route::post('/assign_role/{id}',[SystemUserController::class,'assignRoleToEmployee']);
Route::post('/search_order',[OrderController::class,'search']);
Route::post('/search_shops',[ShopController::class,'search']);


Route::apiResource('/payment_types',PaymentTypeController::class);
Route::post('/send_sms',[UserController::class,'sendSmsNotificaition']);
Route::post('/send_sms_not',[UserController::class,'sendSms']);


Route::post('/verify_otp', [UserLoginController::class, 'verifyPhone']);
Route::post('/verify_reset_otp/{token}', [UserLoginController::class, 'checkResetOtp']);
Route::post('/subscribe', [SubscriptionEmailController::class, 'subscribe_email']);






