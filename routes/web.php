<?php

use App\Http\Livewire\Auth\LoginPage;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Auth\RegisterPage;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Auth\CreateAccountPassword;
use App\Http\Livewire\Application\ParentInformation;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('login', LoginPage::class)->name('login')->middleware('guest');
Route::get('register', RegisterPage::class)->name('register')->middleware('guest');
Route::get('account/create/{token}', CreateAccountPassword::class)->name('account.request');


Route::group(['middleware' => 'auth', 'verified'], function(){

    Route::get('parents', ParentInformation::class)->name('application.parents');
});