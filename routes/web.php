<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Livewire\SampleForm;
use App\Http\Livewire\SiPrepShop;
use App\Http\Livewire\SurveyForm;
use App\Http\Livewire\ContactPage;
use App\Http\Livewire\SamplePayment;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Auth\LoginPage;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ViewFinancialAid;
use App\Filament\Pages\ViewNotification;
use App\Http\Livewire\Auth\RegisterPage;
use App\Http\Livewire\Profile\MyProfile;
use App\Http\Livewire\Page\ManageDevices;
use App\Http\Livewire\RecommendationForm;
use App\Http\Livewire\NotificationPreview;
use App\Http\Livewire\Profile\EditProfile;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Auth\ResetPasswordPage;

use App\Http\Livewire\Page\SiFamilyDirectory;
use App\Http\Livewire\Page\StudentHsptScores;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Livewire\Auth\ForgotUsernamePage;
use App\Http\Livewire\Page\DeviceCompatability;
use App\Http\Controllers\NotificationController;
use App\Http\Livewire\Page\FroshCoursePlacement;
use App\Http\Livewire\Admission\ViewApplications;
use App\Http\Livewire\Auth\CreateAccountPassword;
use App\Http\Livewire\Notifications\FinancialAid;
use App\Http\Livewire\Application\ApplicationForm;
use App\Http\Livewire\Application\ViewApplication;
use App\Http\Livewire\Page\StudentHsptInformation;
use App\Http\Livewire\Admission\TransactionHistory;
use App\Http\Livewire\Auth\ReRegistrationLoginPage;
use App\Http\Livewire\Registration\ReRegistrations;
use App\Http\Livewire\Application\LegacyInformation;
use App\Http\Livewire\Application\ParentInformation;
use App\Http\Livewire\Application\PayApplicationFee;
use App\Http\Livewire\Registration\RegistrationForm;
use App\Http\Livewire\Transfer\TransferApplications;
use App\Http\Livewire\Application\AddressInformation;
use App\Http\Livewire\Application\ChildrenInformation;
use App\Http\Livewire\Registration\ReRegistrationForm;
use App\Http\Livewire\Application\AdmissionApplication;
use App\Http\Livewire\Admission\ApplicationNotification;
use App\Http\Livewire\Application\HealthcareInformation;
use App\Http\Livewire\Registration\SelectReregistration;
use App\Http\Livewire\Registration\StudentRegistrations;
use App\Http\Livewire\Application\AccommodationDocuments;
use App\Http\Livewire\Page\ManageCommunicationPreference;
use App\Http\Livewire\Registration\RegistrationCompleted;
use App\Http\Livewire\Registration\ReRegistrationCompleted;
use App\Http\Livewire\Registration\FinancialAidNotifications;
use App\Http\Livewire\Application\EmergencyContactInformation;
use App\Http\Livewire\Application\UploadAccommodationDocuments;
use App\Http\Livewire\Application\SupplementalRecommendationPage;
use App\Http\Livewire\Application\SupplementalRecommendationRequestForm;

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
})->middleware(['auth', 'verified', 'role:user'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('user/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('user/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('user/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('login', LoginPage::class)->name('login')->middleware('guest');
Route::get('reregistration/login', ReRegistrationLoginPage::class)->name('login.reregistration')->middleware('guest');
Route::get('register', RegisterPage::class)->name('register')->middleware('guest');
Route::get('account/create/{token}', CreateAccountPassword::class)->name('account.request');
Route::get('forgot-username', ForgotUsernamePage::class)->name('forgot-username');
Route::get('devices', ManageDevices::class)->name('user-devices');

Route::group(['middleware' => 'auth', 'verified'], function(){

    Route::get('profile', MyProfile::class)->name('profile.index');
    Route::get('profile/edit', EditProfile::class)->name('profile.edit');

});


Route::group(['middleware' => 'auth', 'verified', 'role:user'], function(){

    Route::get('parents', ParentInformation::class)->name('application.parents');
    Route::get('children', ChildrenInformation::class)->name('application.children');
    Route::get('address', AddressInformation::class)->name('application.address');
    Route::get('legacy', LegacyInformation::class)->name('application.legacy');
    Route::get('healthcare', HealthcareInformation::class)->name('application.healthcare');
    Route::get('emergency-contact', EmergencyContactInformation::class)->name('application.emergecy-contact');
    Route::get('admission', AdmissionApplication::class)->name('application.admission');
    Route::get('admission/payment/{uuid?}', PayApplicationFee::class)->name('application.payment');
    Route::get('admission/{uuid}', ApplicationForm::class)->name('application.form');
    Route::get('admission/{uuid}/readonly', ViewApplication::class)->name('application.show');

    Route::get('accommodation-documents', AccommodationDocuments::class)
        ->name('application.accommodation-documents')
        ->middleware('timeline:upload_accommodation_document_start_date,upload_accommodation_document_end_date');

    Route::get('help', ContactPage::class)->name('help');
    Route::get('prep-shop', SiPrepShop::class)->name('si-prep-shop');
    Route::get('supplemental-recommendation', SupplementalRecommendationPage::class)->name('application.supplemental-recommendation');
    Route::get('supplemental-recommendation/{uuid}', SupplementalRecommendationRequestForm::class)->name('application.supplemental-recommendation-request');
    Route::get('notifications', ApplicationNotification::class)->name('notifications.index');
    Route::get('notifications/{uuid}', ViewNotification::class)->name('notifications.show');
    Route::get('notifications/{uuid}/pdf', [NotificationController::class, 'pdf'])->name('notifications.pdf');
    Route::get('notifications/{uuid}/financial-aid', FinancialAid::class)->name('notifications.financial-aid');
    Route::get('transactions', TransactionHistory::class)->name('transactions.index');
    Route::get('applications', ViewApplications::class);

    Route::get('registration', StudentRegistrations::class)->name('registration.index');
    Route::get('registration/{uuid}', RegistrationForm::class)->name('registration.form');
    Route::get('registration/{uuid}/completed', RegistrationCompleted::class)->name('registration.completed');

    Route::get('survey/{uuid}', SurveyForm::class)->name('survey-form');

    Route::get('hspt-scores', StudentHsptScores::class);
    Route::get('hspt-information', StudentHsptInformation::class);
    Route::get('course-placement', FroshCoursePlacement::class);
    Route::get('device-compatability', DeviceCompatability::class)->name('device-compatability');

    Route::group(['middleware' => 'si-access'], function(){
        Route::get('family-directory', SiFamilyDirectory::class);
        Route::get('communication-preference', ManageCommunicationPreference::class);
    });
    

    Route::get('transfer-applications',TransferApplications::class)->name('transfer.index');
    Route::get('reregistration', ReRegistrations::class)->name('registration.re');
    Route::get('reregistration/{id}/select', SelectReregistration::class)->name('registration.re.select');
    Route::get('reregistration/{uuid?}', ReRegistrationForm::class)->name('registration.re.form');
    Route::get('reregistration/{uuid}/completed', ReRegistrationCompleted::class)->name('registration.re.completed');

    Route::get('financial-aid-notifications', FinancialAidNotifications::class)->name('notifications.fa');
    Route::get('financial-aid-notifications/{uuid}', ViewFinancialAid::class)->name('notifications.fa.show');
});


Route::get('recommendation/{uuid}', RecommendationForm::class)->name('recommendation-form');

Route::get('test-payment', SamplePayment::class)->middleware('auth');
Route::get('sample-form', SampleForm::class);
Route::get('notification-sample/{id}', [NotificationController::class, 'sample']);
Route::impersonate();

Route::get('ping', function(){
    return 'Hello!';
});

Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));
 
    $query = http_build_query([
        'client_id' => '99d55d5d-46b3-4f71-8e6e-d35361ef7bb5',
        'redirect_uri' => 'https://test.salesforce.com/services/oauth2/authorize',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        // 'prompt' => '', // "none", "consent", or "login"
    ]);
    
    return redirect(url('oauth/authorize') . '?'.$query);
});

Route::get('salesforce/callback', function (Request $request) {
    $state = $request->session()->pull('state');

    $url = url('oauth/token');
    $clientId = config('services.salesforce.key');
    $clientSecret = config('services.salesforce.secret');
    $callbackUrl = config('services.salesforce.callback');
 
    $response = Http::asForm()->post($url, [
        'grant_type' => 'authorization_code',
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $callbackUrl,
        'code' => $request->code,
    ]);
 
    return $response->json();
});

Route::get('/callback', function (Request $request) {
    $state = $request->session()->pull('state');
 
    // throw_unless(
    //     strlen($state) > 0 && $state === $request->state,
    //     InvalidArgumentException::class,
    //     'Invalid state value.'
    // );
 
    $response = Http::asForm()->post('http://passport-app.test/oauth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'redirect_uri' => 'http://third-party-app.com/callback',
        'code' => $request->code,
    ]);
 
    return $response->json();
});

Route::get('changelog', function(){

    $file = base_path('CHANGELOG.md');
    $content = \File::get($file);

    return view('changelog', ['changelog' => $content]);
});

Route::get('impersonator/logout', function(){
    auth()->user()->leaveImpersonation();
    return redirect('admin/force-login');
});
