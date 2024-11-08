<?php

use App\Http\Controllers\Api\AuthController;
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
});

Route::group([
    'middleware' => 'auth:sanctum',
    'namespace'  => 'App\Http\Controllers\Api',
], function () {

    Route::prefix('products')->group(function ()
    {
        Route::get('/list', 'ProductController@index')->middleware('can:access_product');
        Route::get('/categories', 'ProductController@categories')->middleware('can:access_category');
        Route::post('/create', 'ProductController@create')->middleware('can:create_product');
        Route::get('/detail', 'ProductController@detail')->middleware('can:detail_product');
        Route::post('/update', 'ProductController@update')->middleware('can:update_product');
        Route::delete('/destory', 'ProductController@destory')->middleware('can:delete_product');
    });

    Route::prefix('transactions')->group(function ()
    {
        Route::get('/list', 'TransactionController@index')->middleware('can:access_transaction');
        Route::post('/purchase', 'TransactionController@create');
        Route::get('/detail', 'TransactionController@detail')->middleware('can:detail_transaction');
        Route::delete('/destory', 'TransactionController@destory')->middleware('can:delete_transaction');
    });
}); 

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
