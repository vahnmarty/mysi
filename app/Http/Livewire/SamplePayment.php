<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Payment;
use Livewire\Component;
use App\Enums\PaymentType;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use net\authorize\api\contract\v1 as AnetAPI;
use Filament\Forms\Concerns\InteractsWithForms;
use net\authorize\api\controller as AnetController;

class SamplePayment extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];

    protected $apiName = '4aqRFzt74C';
    protected $apiKey = '3m493PSzpA378XC9';

    public function render()
    {
        return view('livewire.sample-payment');
    }
    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    public function mount()
    {
        $this->form->fill([
            'billing' => [
                'amount' => 1.5,
                'first_name' => 'Vahn',
                'last_name' => 'Marty',
                'email' => 'vahnmarty@gmail.com',
                'card_number' => '4111111111111111',
                'card_cvv' => '131',
                'card_expiration' => '06/30',
                'address' => 'Zone Meteor',
                'city' => 'Iligan City',
                'state' => 'Florida',
                'zip_code' => '11311'
            ]
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    TextInput::make('billing.amount')
                        ->numeric()
                        ->required()
                        ->default(1),
                    TextInput::make('billing.first_name')
                        ->label('First Name')
                        ->required()
                        ->lazy(),
                    TextInput::make('billing.last_name')
                        ->label('Last Name')
                        ->required()
                        ->lazy(),
                    TextInput::make('billing.email')
                        ->label('Email')
                        ->email()
                        ->columnSpan('full')
                        ->required()
                        ->lazy(),
                    TextInput::make('billing.card_number')
                        ->label('Credit Card Number')
                        ->required()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('{0000}-{0000}-{0000}-{0000}')),
                    TextInput::make('billing.card_cvv')
                        ->maxLength(3)
                        ->minLength(3)
                        ->required()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('{000}'))
                        ->label('CVV'),
                    TextInput::make('billing.card_expiration')
                        ->label('Expiration (MM/YYYY)')
                        ->placeholder('e.g. 06/2030')
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
                        ->required(),
                    
                ])
        ];
    }

    function authorizeCreditCard($data)
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

        $invoice_number = PaymentType::AppFee .'-'. date('s') ;

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
        $amount = $data['amount'];


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

                    Notification::make()
                    ->title("Successfully created transaction with Transaction ID: " . $tresponse->getTransId())
                    ->body($tresponse->getMessages()[0]->getDescription())
                    ->success()
                    ->send();

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


    public function checkout()
    {
        $data = $this->form->getState();

        $this->authorizeCreditCard($data['billing']);
    }
}
