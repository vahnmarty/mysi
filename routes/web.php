<?php

use App\Http\Livewire\SamplePayment;
use App\Http\Livewire\Auth\LoginPage;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Auth\RegisterPage;
use App\Http\Livewire\Profile\MyProfile;
use App\Http\Livewire\Profile\EditProfile;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Auth\ResetPasswordPage;
use App\Http\Livewire\Auth\ForgotUsernamePage;
use App\Http\Livewire\Auth\CreateAccountPassword;
use App\Http\Livewire\Application\ApplicationForm;
use App\Http\Livewire\Application\ParentInformation;
use App\Http\Livewire\Application\AddressInformation;
use App\Http\Livewire\Application\ChildrenInformation;
use App\Http\Livewire\Application\AdmissionApplication;

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
    Route::get('user/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('user/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('user/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('login', LoginPage::class)->name('login')->middleware('guest');
Route::get('register', RegisterPage::class)->name('register')->middleware('guest');
Route::get('account/create/{token}', CreateAccountPassword::class)->name('account.request');
Route::get('forgot-username', ForgotUsernamePage::class)->name('forgot-username');

Route::group(['middleware' => 'auth', 'verified'], function(){

    Route::get('profile', MyProfile::class)->name('profile.index');
    Route::get('profile/edit', EditProfile::class)->name('profile.edit');

});


Route::group(['middleware' => 'auth', 'verified'], function(){

    Route::get('parents', ParentInformation::class)->name('application.parents');
    Route::get('children', ChildrenInformation::class)->name('application.children');
    Route::get('address', AddressInformation::class)->name('application.address');
    Route::get('admission', AdmissionApplication::class)->name('application.admission');
    Route::get('admission/{uuid}', ApplicationForm::class)->name('application.form');
});

Route::get('test-payment', SamplePayment::class)->middleware('auth');