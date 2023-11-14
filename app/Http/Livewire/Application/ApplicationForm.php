<?php

namespace App\Http\Livewire\Application;

use Auth;
use Mail;
use App\Models\Payment;
use Livewire\Component;
use App\Enums\PaymentType;
use App\Models\Application;
use Illuminate\Support\HtmlString;
use App\Mail\NewApplicationSubmitted;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use net\authorize\api\contract\v1 as AnetAPI;
use App\Forms\Components\WritingSampleSection;
use App\Notifications\Admission\PaymentReceipt;
use Filament\Forms\Concerns\InteractsWithForms;
use net\authorize\api\controller as AnetController;
use App\Notifications\Admission\ApplicationSubmitted;
use App\Http\Livewire\Application\Forms\FinalStepsTrait;
use App\Http\Livewire\Application\Forms\LegacyFormTrait;
use App\Http\Livewire\Application\Forms\ParentFormTrait;
use App\Http\Livewire\Application\Forms\AddressFormTrait;
use App\Http\Livewire\Application\Forms\SiblingFormTrait;
use App\Http\Livewire\Application\Forms\StudentFormTrait;
use App\Http\Livewire\Application\Forms\ActivityFormTrait;
use App\Http\Livewire\Application\Forms\FamilyMatrixTrait;
use App\Http\Livewire\Application\Forms\ReligionFormTrait;
use App\Http\Livewire\Application\Forms\PlacementFormTrait;
use App\Http\Livewire\Application\Forms\WritingSampleTrait;
use App\Http\Livewire\Application\Forms\ParentStatementTrait;
use App\Http\Livewire\Application\Forms\StudentStatementTrait;

class ApplicationForm extends Component implements HasForms
{
    use InteractsWithForms;

    # Import Traits
    use StudentFormTrait, AddressFormTrait, ParentFormTrait, SiblingFormTrait, FamilyMatrixTrait, LegacyFormTrait, ReligionFormTrait, ParentStatementTrait, StudentStatementTrait, ActivityFormTrait, WritingSampleTrait, PlacementFormTrait, FinalStepsTrait;
    
    # Model
    public Application $app;

    # Constant Variables
    const NotListed = 'Not Listed';

    # Form
    public $data = [];
    public $is_validated = false;
    public $is_submitted = false;
    public $amount = 100;
    public $active;

    protected $queryString = ['active'];

    protected $messages = [
        'data.writing_sample_essay_acknowledgement.accepted' => 'This field is required.',
        'data.activities.min' => 'There must be at least 1 school activity listed.',
        'data.addresses.min' => 'There must be at least 1 address listed.',
        'data.file_learning_documentation.required' => 'There is no file attached to the application.  Please upload your documentation.',
        'data.legacy.*.graduation_year.max' => 'The Graduation Year must not be greater than :max.',
        //'data.siblings.*.graduation_year' => 'The Graduation Year must not be greater than :input.',
    ];

    public function render()
    {
        return view('livewire.application.application-form')
                ->layoutData(['title' => 'Admission Application']);
    }

    public function mount($uuid)
    {
        $this->app = Application::with('activities', 'student')->whereUuid($uuid)->firstOrFail();
        
        $status = $this->app->appStatus()->firstOrCreate([
            'application_id' => $this->app->id,
        ],[
            'application_started' => 1,
            'application_start_date' => now()
        ]);

        if($status->application_submitted){
            $this->is_submitted = true;
            return;
        }

        $account = $this->app->account->load('addresses', 'guardians', 'parents', 'children', 'legacies');
        $user = Auth::user();

        $data = $this->app->toArray();
        $data['student'] = $this->app->student->toArray();
        $data['addresses'] = $account->addresses->toArray();
        $data['parents'] = $account->guardians->toArray();
        $data['parents_matrix'] = $account->parents->toArray();
        $data['siblings'] = $account->children()->where('id', '!=', $this->app->child_id)->get()->toArray();
        $data['siblings_matrix'] = $account->children()->where('id', '!=', $this->app->child_id)->get()->toArray();
        $data['legacy'] = $account->legacies->toArray();
        $data['activities'] = $this->app->activities->toArray();
        $data['autosave'] = true;
        $data['placement_test_date'] = settings('placement_test_date');

        if($this->app->payment){
            $this->amount = $this->app->payment?->final_amount;
        }

        $this->form->fill($data);
    }


    protected function getFormSchema(): array
    {
        return [];
        return [
            // Toggle::make('autosave')
            //     ->label('AutoSave')
            //     ->extraAttributes(['class' => 'bg-blue-400'])
            //     //->helperText('This form is always safe with our autosave feature. We save your progress every few seconds, so you can rest assured that nothing will be lost. ')
            //     ->helperText(new HtmlString(''))

            //     ->disabled(),
            Placeholder::make('form_description')
                ->label('')
                ->content(new HtmlString('* The application saves your work automatically after you enter your information and click out of the text box. All required fields are  <span class="font-bold text-primary-red">red</span> and have an asterisk (<span class="text-primary-red">*</span>).')),
            Section::make('Applicant Information')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getStudentForm()),
            Section::make('Address Information')
                ->schema($this->getAddressForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Parent/Guardian Information')
                ->schema($this->getParentForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Sibling Information')
                ->schema($this->getSiblingForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Family Information')
                ->description(new HtmlString('If all family members are not listed, <a  href="?active=matrix#matrix" class="underline text-link">click here</a> to refresh this tab.'))
                ->schema($this->getFamilyMatrix())
                ->collapsible()
                ->collapsed(fn() => $this->active == 'matrix' ? false : true )
                ->extraAttributes(['id' => 'matrix']),
            Section::make('Legacy Information')
                ->schema($this->getLegacyForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Spiritual and Community Information')
                ->schema($this->getReligionForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Parent/Guardian Statement')
                ->schema($this->getParentStatement())
                ->collapsible()
                ->collapsed(true),
            Section::make('Applicant Statement')
                ->schema($this->getStudentStatement())
                ->collapsible()
                ->collapsed(true),
            Section::make('School Activities')
                ->schema($this->getActivityForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Writing Sample')
                ->schema($this->getWritingSampleForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('High School Placement Test')
                ->schema($this->getPlacementForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Final Steps')
                ->schema($this->getFinalStepsForm())
                ->collapsible()
                ->collapsed(true),
        ];
    }

    protected function getFormModel() 
    {
        return $this->app;
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    } 

    public function autoSaveFiles($column, $files)
    {
        $model = $this->app;
        $model->$column = $files;
        $model->save();
    }

    public function autoSave($column, $value, $relationship = null)
    {
        if($relationship){
            $model = $this->app->{$relationship};
        }else{
            $model = $this->app;
        } 

        if(is_array($value)){
            $value = implode(',', $value);
        }

        try {
            $model->$column = $value;
            $model->save();
        } catch (\Exception $e) {

            throw $e;
            
            Notification::make()
                ->title('System Error!')
                ->body('Please check error message (s) below the field.')
                ->danger()
                ->send();
        }

        
    }

    

    public function __autoSave($model, $column, $value)
    {
        try {
            $model->$column = $value;
            $model->save();
            
        } catch (\Exception $e) {

            Notification::make()
                ->title('Error! Invalid value: ' . $value)
                ->danger()
                ->send();
        }
    }

    public function validateForm()
    {
        $data = $this->form->getState();

        $this->is_validated = true;

        $payment = Payment::firstOrCreate([
            'application_id' => $this->app->id,
            'user_id' => Auth::id(),
            'initial_amount' => 100,
            'final_amount' => 100
        ]);
    }

    function authorizeCreditCard(Payment $payment, $data)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.login_id'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.transaction_key'));
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($data['card_number']);
        $creditCard->setExpirationDate($data['card_expiration']);
        $creditCard->setCardCode($data['card_cvv']);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create order information
        $order = new AnetAPI\OrderType();

        $invoice_number = PaymentType::AppFee .'-'. $this->app->id . '-' . date('Ymd') ;

        $order->setInvoiceNumber($invoice_number);
        $order->setDescription("Payment For Admission Application");

        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($data['first_name']);
        $customerAddress->setLastName($data['last_name']);
        $customerAddress->setAddress($data['address']);
        $customerAddress->setCity($data['city']);
        $customerAddress->setState($data['state']);
        $customerAddress->setZip($data['zip_code']);
        $customerAddress->setCountry("USA");

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId(Auth::id());
        $customerData->setEmail($data['email']);


        # Variables
        $amount = $payment->final_amount;


        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction"); 
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);


        $environment = config('services.authorize.env');

        if($environment == 'production'){
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }else{
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        }
        


        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getMessages() != null) {

                    $payment->update([  
                        'name_on_card' => $data['first_name'] . ' ' . $data['last_name'],
                        'payment_type' => PaymentType::AppFee,
                        'transaction_id' => $tresponse->getTransId(),
                        'auth_id' => $tresponse->getAuthCode(),
                        'initial_amount' => $amount,
                        'final_amount' => $amount,
                        'quantity' => 1,
                        'total_amount' => $amount
                    ]);

                    return $payment;

                    // echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
                    // echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
                    // echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
                    // echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
                    // echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";

                } else {
                    Notification::make()
                    ->title('Transaction Failed!')
                    ->danger()
                    ->send();

                    // echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {

                        Notification::make()
                            ->title('Transaction Error: ' . $tresponse->getErrors()[0]->getErrorCode())
                            ->body($tresponse->getErrors()[0]->getErrorText())
                            ->danger()
                            ->send();

                        // echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        // echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                    }

                    return false;
                }
                // Or, print errors if the API request wasn't successful
            } else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                $code = '';
                $message = '';
                
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $code = $tresponse->getErrors()[0]->getErrorCode();
                    $message = $tresponse->getErrors()[0]->getErrorText() ;
                } else {
                    $code = $response->getMessages()->getMessage()[0]->getCode();
                    $message = $response->getMessages()->getMessage()[0]->getText();
                }

                Notification::make()
                    ->title('Transaction Failed! ' . $code)
                    ->danger()
                    ->send();

                Notification::make()
                    ->title('Transaction Failed! ' . $message)
                    ->danger()
                    ->send();
            }      
        } else {

            Notification::make()
                ->title('No response returned')
                ->danger()
                ->send();
        }

        return $response;
    }


    public function submit()
    {
        $data = $this->form->getState();
        
        $this->dispatchBrowserEvent('page-loading-open');

        $app = Application::with('appStatus')->find($this->app->id);


        if($this->amount <= 0){

            $app->appStatus()->update([
                'application_submitted' => true,
                'application_submit_date' => now()
            ]);

            $this->saveAppArchive($app, $data);

            Auth::user()->notify( new ApplicationSubmitted($app));

            $this->is_submitted = true;

            $this->dispatchBrowserEvent('page-loading-close');

            return true;

        }else{
            $payment = $this->app->payment;

            $payment = $this->authorizeCreditCard($payment, $data['billing']);

            if($payment instanceof Payment){

                $app->appStatus()->update([
                    'application_submitted' => true,
                    'application_submit_date' => now()
                ]);

                $this->saveAppArchive($app, $data);

                Auth::user()->notify( new ApplicationSubmitted($app));
                Auth::user()->notify( new PaymentReceipt($app));

                //Mail::to(config('settings.si.admissions.email'))->send(new NewApplicationSubmitted($app));

                $this->is_submitted = true;

                $this->dispatchBrowserEvent('page-loading-close');
            }else{

                Notification::make()
                    ->title('Payment failed.')
                    ->danger()
                    ->send();


                $this->dispatchBrowserEvent('page-loading-close');
            }
        }
        
        
    }

    public function saveAppArchive(Application $app, $data)
    {
        $app->archive()->create([
            'account_id'        => $app->account_id,
            'user_id'           => Auth::id(),
            'application_id'    => $app->id,
            'application'       => $app->toArray(),
            'student'           => $data['student'],
            'addresses'         => $data['addresses'],
            'parents'           => $data['parents'],
            'parents_matrix'    => $data['parents_matrix'],
            'siblings'          => $data['siblings'],
            'siblings_matrix'   => $data['siblings_matrix'],
            'legacy'            => $data['legacy'],
            'activities'        => $data['activities']
        ]);
    }

}
