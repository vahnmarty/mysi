<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group( ['middleware' => ['client']], function(){

    Route::get('parents', [ParentController::class, 'index']);
    Route::get('accounts', [AccountController::class, 'index']);
    Route::get('activities', [ActivityController::class, 'index']);
    Route::get('addresses', [AddressController::class, 'index']);
    Route::get('applications', [ApplicationController::class, 'index']);
    Route::get('children', [ChildController::class, 'index']);

});

