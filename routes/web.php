<?php

use Illuminate\Support\Facades\Route;

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

Route::group([
    'namespace'  => 'App\Http\Controllers',
], function () {
    Route::get('/', 'VendingMachineController@index');
}); 
Route::post('/make-transaction', 'App\Http\Controllers\Admin\TransactionCrudController@store');