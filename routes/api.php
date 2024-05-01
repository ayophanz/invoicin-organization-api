<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrganizationSettingController;

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

Route::group(['prefix' => 'organization'], function () {
    Route::controller(OrganizationController::class)->group( function () {
        Route::post('validate', 'orgValidate');
        Route::post('verify', 'verifyOrganization');
    });
    Route::group(['middleware' => ['auth']], function () {
        Route::controller(OrganizationController::class)->group( function () {
            Route::post('store', 'store');
            Route::get('show', 'show');
            Route::get('profile/show', 'showProfile');
            Route::put('profile/update', 'updateProfile');        
        });
        
        Route::controller(AddressController::class)->group( function () {
            Route::get('addresses', 'index');
            Route::get('addresses/show', 'show');
            Route::post('addresses/store', 'store');
            Route::put('addresses/update', 'update');
            Route::delete('addresses/destroy', 'destroy');
        });
    });
});

Route::fallback(function () {
    return response()->json(['Error' => 'Not Found'], Response::HTTP_NOT_FOUND);
});