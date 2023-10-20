<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\LegacyController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\StudentController;
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

    Route::get('accounts', [AccountController::class, 'index']);
    Route::get('accounts/{account}', [AccountController::class, 'show']);
    Route::post('accounts/{account}/sync', [AccountController::class, 'sync']);


    Route::get('activities', [ActivityController::class, 'index']);
    Route::get('activities/{activity}', [ActivityController::class, 'show']);
    Route::post('activities/{activity}/sync', [ActivityController::class, 'sync']);


    Route::get('addresses', [AddressController::class, 'index']);
    Route::get('addresses/{address}', [AddressController::class, 'show']);
    Route::post('addresses/{address}/sync', [AddressController::class, 'sync']);

    Route::get('applications', [ApplicationController::class, 'index']);
    Route::get('applications/{application}', [ApplicationController::class, 'show']);
    Route::post('applications/{application}/sync', [ApplicationController::class, 'sync']);

    Route::get('children', [ChildController::class, 'index']);
    Route::get('children/{child}', [ChildController::class, 'show']);
    Route::post('children/{child}/sync', [ChildController::class, 'sync']);

    Route::get('students', [StudentController::class, 'index']);
    Route::get('students/{student}', [StudentController::class, 'show']);
    Route::post('students/{student}/sync', [StudentController::class, 'sync']);

    Route::get('legacies', [LegacyController::class, 'index']);
    Route::get('legacies/{legacy}', [LegacyController::class, 'show']);
    Route::post('legacies/{legacy}/sync', [LegacyController::class, 'sync']);

    Route::resource('schools', SchoolController::class);

});