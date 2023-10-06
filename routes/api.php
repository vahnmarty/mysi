<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\LegacyController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\SalesforceController;
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

Route::get('/', function(){
    return response()->json([
        'message' => 'Hello'
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// ['middleware' => [ 'client' ] 
Route::group( ['middleware' => [] ], function(){

    Route::get('salesforce', [SalesforceController::class, 'index']);


    Route::post('parents/{parent}/sync', [ParentController::class, 'sync']);
    Route::resource('parents', ParentController::class);

    // Route::resource('accounts', AccountController::class);
    Route::get('accounts', [AccountController::class, 'index']);


    Route::get('activities', [ActivityController::class, 'index']);


    Route::get('addresses', [AddressController::class, 'index']);

    Route::get('applications', [ApplicationController::class, 'index']);
    Route::patch('applications/{uuid}', [ApplicationController::class, 'update']);

    Route::get('children', [ChildController::class, 'index']);

    Route::get('legacies', [LegacyController::class, 'index']);

    Route::resource('schools', SchoolController::class);

});