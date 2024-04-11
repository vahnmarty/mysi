<?php

namespace App\Http\Livewire\Application;

use Auth;
use App\Models\Account;
use App\Models\Payment;
use Livewire\Component;
use App\Enums\PaymentType;
use App\Models\Application;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use App\Models\UnsettledApplication;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput\Mask;
use net\authorize\api\contract\v1 as AnetAPI;
use App\Notifications\Admission\PaymentReceipt;
use Filament\Forms\Concerns\InteractsWithForms;
use net\authorize\api\controller as AnetController;

class PayApplicationFee extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $app;

    public $paid, $amount, $is_submitted;

    public $data = [];

    public $app_uuid;

    public function render()
    {
        return view('livewire.application.pay-application-fee');
    }

    public function mount($uuid = null)
    {
        $this->app_uuid = $uuid;

        
        if($uuid){

            $app = Application::whereUuid($uuid)->firstOrFail();


            $this->app = $app;

            $this->amount = $app->applicationFee->final_amount ?? config('settings.payment.application_fee');

            $this->form->fill();  
            
            if($app->isPaid()){
                $this->paid = true;
            }

        }else{
            $account = Account::find(accountId());
            $applications = $account->applications()->submitted()->get() ;
            
            if(count($applications)){
                foreach($applications as $app){
                    if(!$app->isPaid()){
                        return redirect()->route('application.payment', ['uuid' => $app->uuid]);
                    }
                }

                // FIX all unpaid.
            }else{
                dd('This account has no application submitted.');
            }

            
            
        }
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema()
    {
        return [
            Grid::make(3)
                ->schema([
                    Placeholder::make('amount')
                        ->columnSpan('full')
                        ->label('')
                        ->content(new HtmlString('
                            <div>
                                <h4>Amount Due: <strong class="font-bold text-primary-red">$'. number_format($this->amount, 2).'</strong></h4>
                            </div>    
                        '))
                        ->reactive(),
                    TextInput::make('first_name')
                        ->label('First Name')
                        ->required()
                        ->lazy(),
                    TextInput::make('last_name')
                        ->label('Last Name')
                        ->required()
                        ->lazy(),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        //->rules(['email:rfc,dns'])
                        ->columnSpan('full')
                        ->required()
                        ->lazy(),
                    TextInput::make('card_number')
                        ->label('Credit Card Number')
                        ->required()
                        ->minLength(13)
                        ->maxLength(16),
                    TextInput::make('card_cvv')
                        ->maxLength(3)
                        ->minLength(3)
                        ->required()
                        ->mask(fn (Mask $mask) => $mask->pattern('{000}'))
                        ->label('CVV'),
                    TextInput::make('card_expiration')
                        ->label(new HtmlString('Expiration <strong>(MM/YYYY)</strong>'))
                        ->placeholder('e.g. 06/2030')
                        ->required()
                        ->mask(fn (Mask $mask) => $mask->pattern('{00}/{0000}')),
                    TextInput::make('address')
                        ->label('Billing Address')
                        ->required(),
                    TextInput::make('city')
                        ->label('Billing City')
                        ->required(),
                    TextInput::make('state')
                        ->label('Billing State')
                        ->required(),
                    TextInput::make('zip_code')
                        ->label('Billing Zip Code')
                        ->required(),
                ])
        ];
    }

    public function checkout()
    {
        $data = $this->form->getState();

        $paymentRecord = $this->app->applicationFee;

        $payment = $this->authorizeCreditCard($paymentRecord, $data);

        if($payment instanceof Payment){

            Auth::user()->notify( new PaymentReceipt($this->app));
            
            $this->paid = true;

            $unsettled = UnsettledApplication::where('account_id', accountId())->first();

            if($unsettled){
                $unsettled->delete();
            }
            
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
            if ($response->getMessages()->getResultCode() == "Ok") {
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
}
