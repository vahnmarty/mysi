<?php

namespace App\Filament\Pages;

use Auth;
use App\Models\Payment;
use Filament\Pages\Page;
use App\Enums\RecordType;
use App\Enums\PaymentType;
use App\Models\Application;
use App\Models\NotificationLetter;
use Filament\Pages\Actions\Action;
use Illuminate\Support\HtmlString;
use App\Models\NotificationMessage;
use App\Enums\NotificationStatusType;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\CreateAction;
use Filament\Forms\Components\Placeholder;
use Illuminate\Contracts\Support\Htmlable;
use net\authorize\api\contract\v1 as AnetAPI;
use Filament\Forms\Concerns\InteractsWithForms;
use net\authorize\api\controller as AnetController;
use Illuminate\Contracts\View\View;

class ViewNotification extends Page {

    
    protected static string $view = 'filament.pages.view-notification';

    protected static string $layout = 'layouts.app';

    protected static bool $shouldRegisterNavigation = false;

    public $content, $faq, $fa_content;

    public $show_fa = false, $checked, $declined, $decision_status;

    public Application $app;

    public NotificationMessage $notification;

    public $billing = [];
    public $deposit_amount;



    public function mount($uuid)
    {
        $notification = NotificationMessage::whereUuid($uuid)->firstOrFail();
        $app = $notification->application;
        $appStatus = $app->appStatus;
        $content = $notification->content;

        $faq = '';

        if($appStatus->application_status == NotificationStatusType::WaitListed){
            $faq_cms = NotificationLetter::where('title', 'Waitlist FAQ')->first();

            if($faq_cms){
                $this->faq = $faq_cms->content;
            }
        }

        $this->notification = $notification;
        $this->content = $content;
        $this->app = $app;
        $this->fa_content = $notification->financial_aid_content;
        $this->declined = $app->declined();
        $this->decision_status = $appStatus->candidate_decision_status;
        $this->deposit_amount = $app->appStatus->deposit_amount;

        if($this->decision_status){
            $this->initSurveyForm($app);
        }
    }

    protected function getHeading(): string | Htmlable
    {
        return ' ';
    }

    protected function getTitle(): string | Htmlable
    {
        return 'Notification';
    }

    protected function getFormSchema(): array 
    {
        return [
            Checkbox::make('checked')
                ->columnSpan('full')
                ->label('By checking the box, I acknowledged the Financial Aid')
                ->lazy()
                ->required()
        ];
    }

    protected function getActions(): array
    {
        $notification_setting = notification_setting('registration_end_date');
        $registration_end_date = date(('g:i a T \o\n F j, Y'), strtotime($notification_setting->value));
        $class_year = config('settings.class_year');

        return [
            Action::make('enroll')
                ->label('Enroll at SI')
                ->action('enroll')
                ->color('success')
                ->modalHeading('Registration Deposit Fee')
                ->modalButton('PAY ($' . number_format($this->deposit_amount,2) .')')
                ->form([
                    Placeholder::make('note')
                        ->label('')
                        ->content(new HtmlString('NOTE:  You must make a deposit payment of <strong> $'.number_format($this->deposit_amount,2).'</strong> before '. $registration_end_date .' to reserve your spot in the SI Class of '. $class_year .'.')),
                    Fieldset::make('Payment Information')
                        ->label(new HtmlString('<strong>Payment Information</strong>'))
                        ->columns(3)
                        ->schema([
                            TextInput::make('billing.first_name')
                        ->label('First Name')
                        ->required(),
                    TextInput::make('billing.last_name')
                        ->label('Last Name')
                        ->required(),
                    TextInput::make('billing.email')
                        ->label('Email')
                        ->email()
                        ->rules(['email:rfc,dns'])
                        ->columnSpan('full')
                        ->required(),
                    TextInput::make('billing.card_number')
                        ->label('Credit Card Number')
                        ->required()
                        ->minLength(13)
                        ->maxLength(16),
                    TextInput::make('billing.card_cvv')
                        ->maxLength(3)
                        ->minLength(3)
                        ->required()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('{000}'))
                        ->label('CVV'),
                    TextInput::make('billing.card_expiration')
                        ->label(new HtmlString('Expiration <strong>(MM/YYYY)</strong>'))
                        ->placeholder('e.g. 06/2030')
                        ->required()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('{00}/{0000}')),
                    TextInput::make('billing.address')
                        ->label('Billing Address')
                        ->required(),
                    TextInput::make('billing.city')
                        ->label('Billing City')
                        ->required(),
                    TextInput::make('billing.state')
                        ->label('Billing State')
                        ->required(),
                    TextInput::make('billing.zip_code')
                        ->label('Billing Zip Code')
                        ->required()
                    ])
                ])
                ->action(function (array $data): void {
                    $this->checkout($data['billing']);
                })
                ->visible(function(){
                    $app = $this->app;
                    
                    if( $app->applicationAccepted() ){
                        if($app->hasRegistered() || $app->declined() || $app->enrolled()) {
                            return false;
                        }

                        if($app->hasFinancialAid()){
                            return $app->fa_acknowledged();
                        }

                        return true;
                    }

                    return false;
                }),
            Action::make('decline')
                ->label('Decline Acceptance at SI')
                ->requiresConfirmation()
                ->action('decline')
                ->color('primary')
                ->visible(function(){
                    $app = $this->app;
                    
                    if( $app->applicationAccepted() ){
                        if($app->hasRegistered() || $app->declined() || $app->enrolled()) {
                            return false;
                        }

                        if($app->hasFinancialAid()){
                            return $app->fa_acknowledged();
                        }
                        
                        return true;
                    }

                    return false;
                }),
            Action::make('remove_waitlist')
                ->label('Remove from Waitlist')
                ->requiresConfirmation()
                ->action('removeWaitlist')
                ->color('primary')
                ->visible(fn() => $this->app->waitlisted() && !$this->app->waitlistRemoved() ),

        ];
    }

    public function decline()
    {
        $app = $this->app;
        $app->appStatus()->update([
            'candidate_decision' => false,
            'candidate_decision_date' => now(),
            'candidate_decision_status' => 'Declined',
        ]);

        $this->initSurveyForm($app);

        return redirect(request()->header('Referer'));
    }

    public function removeWaitlist()
    {
        $app = $this->app;
        $app->appStatus()->update([
            'candidate_decision' => 3,
            'candidate_decision_date' => now(),
            'candidate_decision_status' => 'Waitlist Removed',
        ]);

        return redirect(request()->header('Referer'));
    }

    public function acknowledgeFinancialAid()
    {
        $appStatus = $this->app->appStatus;
        $appStatus->fa_acknowledged_at = now();
        $appStatus->save();

        $this->dispatchBrowserEvent('close-modal');
    }

    public function applicationAccepted()
    {
        return $this->app->appStatus->application_status == 'Accepted';
    }

    public function checkout($form)
    {
        $app = $this->app;

        $paymentRecord = Payment::firstOrCreate(
            [
            'application_id' => $this->app->id,
            'user_id' => Auth::id(),
            'payment_type' => PaymentType::RegFee
        ],
            [ 
            'initial_amount' => $this->deposit_amount,
            'final_amount' => $this->deposit_amount
        ]);

        
        $payment = $this->authorizeCreditCard($paymentRecord, $form);

        if($payment instanceof Payment){

            $app->appStatus()->update([
                'candidate_decision' => true,
                'candidate_decision_date' => now(),
                'candidate_decision_status' => 'Accepted'
            ]);
            
            # Create Registration
            $registration = $app->registration()->firstOrCreate([
                'child_id' => $app->child_id
            ],[
                'account_id' => accountId(),
                'record_type_id' => RecordType::Student,
            ]);

            $this->initSurveyForm($app);

            return redirect(request()->header('Referer'));
        }else{

            Notification::make()
                ->title('Payment failed.')
                ->danger()
                ->send();

        }
        
        
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

        $invoice_number = PaymentType::RegFee .'-'. $this->app->id . '-' . date('Ymd') ;

        $order->setInvoiceNumber($invoice_number);
        $order->setDescription("Payment For Registration Deposit Fee");

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
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getMessages() != null) {

                    $payment->update([  
                        'name_on_card' => $data['first_name'] . ' ' . $data['last_name'],
                        'payment_type' => PaymentType::RegFee,
                        'transaction_id' => $tresponse->getTransId(),
                        'auth_id' => $tresponse->getAuthCode(),
                        'initial_amount' => $amount,
                        'final_amount' => $amount,
                        'quantity' => 1,
                        'total_amount' => $amount
                    ]);

                    return $payment;

                } else {
                    Notification::make()
                    ->title('Transaction Failed!')
                    ->danger()
                    ->send();

                    if ($tresponse->getErrors() != null) {

                        Notification::make()
                            ->title('Transaction Error: ' . $tresponse->getErrors()[0]->getErrorCode())
                            ->body($tresponse->getErrors()[0]->getErrorText())
                            ->danger()
                            ->send();
                    }

                    return false;
                }
            } else {
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

    public function initSurveyForm(Application $app)
    {
        return $app->survey()->firstOrCreate([
            'application_id' => $app->id
        ],[
            'type' => $app->candidateAccepted() ? 'Accepted' : 'Declined'
        ]);
    }
}
