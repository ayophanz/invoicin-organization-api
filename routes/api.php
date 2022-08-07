<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationAddressController;

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

Route::group(['prefix' => 'organization', 'middleware' => ['auth']], function () {
    Route::controller(OrganizationController::class)->group( function () {
        Route::get('/settings', 'settings');
        Route::get('/addresses', 'addresses');
    });
    Route::controller(OrganizationAddressController::class)->group( function () {
        Route::post('/addresses/store', 'store');
        Route::put('/addresses/update', 'update');
    });
});