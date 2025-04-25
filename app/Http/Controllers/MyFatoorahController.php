<?php

namespace App\Http\Controllers;

use App\Interfaces\BalanceInterface;
use App\Interfaces\PatientInterface;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use MyFatoorah\Library\MyFatoorah;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use Illuminate\Support\Facades\DB;
use Exception;

class MyFatoorahController extends Controller
{

    /**
     * @var array
     */
    public $mfConfig = [];

    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Initiate MyFatoorah Configuration
     */
    protected $balanceService;
    protected $patientService;
    public function __construct(BalanceInterface $balanceService,
    PatientInterface $patientService)
    {
        $this->mfConfig = [
            'apiKey'      => config('myfatoorah.api_key'),
            'isTest'      => config('myfatoorah.test_mode'),
            'countryCode' => config('myfatoorah.country_iso'),
        ];
        $this->balanceService =$balanceService;
        $this->patientService =$patientService;
    }
   

    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Redirect to MyFatoorah Invoice URL
     * Provide the index method with the order id and (payment method id or session id)
     *
     * @return Response
     */
    public function index()
    {
        try {
            //For example: pmid=0 for MyFatoorah invoice or pmid=1 for Knet in test mode
            $paymentId = request('pmid') ?: 2;
            $sessionId = request('sid') ?: null;

            if(request()->filled('oid')){
                $orderId  = request('oid') ;
                $curlData = $this->getPayLoadData($orderId);

                $mfObj   = new MyFatoorahPayment($this->mfConfig);
                $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);
                // dd($payment);
                return redirect()->away($payment['invoiceURL']);

            }elseif(request()->filled('did')){
                $orderId  = request('did') ;
                $curlData = $this->getPayLoadDonationData($orderId);
    
                $mfObj   = new MyFatoorahPayment($this->mfConfig);
                $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);
                // dd($payment);
                return redirect()->away($payment['invoiceURL']);
            }
        } catch (Exception $ex) {
            $exMessage = __('from index myfatoorah.' . $ex->getMessage());
            return response()->json(['IsSuccess' => 'false', 'Message' => $exMessage]);
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to map order data to MyFatoorah
     * You can get the data using the order object in your system
     * 
     * @param int|string $orderId
     * 
     * @return array
     */
    private function getPayLoadData($orderId = null)
    {
        $callbackURL = route('myfatoorah.callback');

        //You can get the data using the order object in your system
        $order = $this->getTestOrderData($orderId);
        
        return [
            'CustomerName'       => $order['name'],
            'InvoiceValue'       => $order['total'],
            'DisplayCurrencyIso' => $order['currency'],
            'CustomerEmail'      => 'test@test.com',
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'MobileCountryCode'  => '+20',
            'CustomerMobile'     => $order['phone'],
            'Language'           => 'en',
            'CustomerReference'  => $orderId,
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION,
            'UserDefinedField'           =>'appointment'       ,
        ];
    }
    private function getPayLoadDonationData($orderId = null)
    {
        $callbackURL = route('myfatoorah.callback');

        //You can get the data using the order object in your system
        $order = $this->getDonationData($orderId);
        
        return [
            'CustomerName'       => $order['name'],
            'InvoiceValue'       => $order['total'],
            'DisplayCurrencyIso' => $order['currency'],
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'MobileCountryCode'  => '+20',
            'CustomerMobile'     => $order['phone'],
            'Language'           => 'en',
            'CustomerReference'  => $orderId,
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION,
            'UserDefinedField'           =>'donation' 
        ];
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get MyFatoorah Payment Information
     * Provide the callback method with the paymentId
     * 
     * @return Response
     */
    public function callback()
    {
        try {
            $paymentId = request('paymentId');

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data  = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

            $message = $this->getTestMessage($data->InvoiceStatus, $data->InvoiceError);

            $response = ['IsSuccess' => true, 'Message' => $message, 'Data' => $data];

            // return $response;
           
            if($data->UserDefinedField == 'appointment'){
                // save the transaction
                if ($data->InvoiceStatus == "Paid") {
                    DB::beginTransaction();
                    $appointment = Appointment::findOrFail($data->CustomerReference)->load(['patient', 'doctor']);
                    $appointment->paid = true;
                    $appointment->amount_paid = (double) $data->InvoiceValue;
                    $appointment->save();
                    // save the Transaction
                    $transaction  = Transaction::create([
                        'patient_id' => $appointment->patient->id,
                        'appointment_id' => $appointment->id,
                        'InvoiceId' => $data->InvoiceId,
                        'InvoiceReference' => $data->InvoiceReference,
                        'InvoiceValue' => (float) $data->InvoiceValue,
                        'Currency' => $data->InvoiceTransactions[0]->PaidCurrency,
                        'CustomerName' => $data->CustomerName,
                        'CustomerMobile' => $data->CustomerMobile,
                        'PaymentGateway' => $data->InvoiceTransactions[0]->PaymentGateway,
                        'PaymentId' => $data->InvoiceTransactions[0]->PaymentId,
                        'CardNumber' => str_repeat("x", strlen($data->InvoiceTransactions[0]->CardNumber) - 4) . substr($data->InvoiceTransactions[0]->CardNumber, -4),
                    ]);
                    $this->balanceService->increase((double) $data->InvoiceValue);
                    DB::commit();
                    // return $response;
                    if ($transaction->appointment->registration_method == 'reception') {
                        return to_route('reception.appointment.show', $transaction->appointment->id)->with('success', 'payment done successfully!');
                    } else {
                        return to_route('hospital.sheet', $transaction->PaymentId);
                    }
                }

            }elseif($data->UserDefinedField == 'donation'){
                // save the transaction
                if ($data->InvoiceStatus == "Paid") {
                    DB::beginTransaction();
                    $donation = Donation::findOrFail($data->CustomerReference);
                   $donation->paid=true;
                   $donation->save();
                    // save the Transaction
                    $transaction  = Transaction::create([
                        'donation_id'=>$donation->id,
                        'InvoiceId' => $data->InvoiceId,
                        'InvoiceReference' => $data->InvoiceReference,
                        'InvoiceValue' => (float) $data->InvoiceValue,
                        'Currency' => $data->InvoiceTransactions[0]->PaidCurrency,
                        'CustomerName' => $data->CustomerName,
                        'CustomerMobile' => $data->CustomerMobile,
                        'PaymentGateway' => $data->InvoiceTransactions[0]->PaymentGateway,
                        'PaymentId' => $data->InvoiceTransactions[0]->PaymentId,
                        'CardNumber' => str_repeat("x", strlen($data->InvoiceTransactions[0]->CardNumber) - 4) . substr($data->InvoiceTransactions[0]->CardNumber, -4),
                    ]);
                    $this->balanceService->increase((double) $data->InvoiceValue);
                    DB::commit();
                    // return $response;
                    if ($transaction->donation->registration_method == 'reception') {
                        return to_route('reception.donations.show', $transaction->donation->id)->with('success', 'payment done successfully!');
                    } else {
                       
                    }
                }
                // return $response;
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            $response  = ['IsSuccess' => 'false', 'Message' => $exMessage];
        }
        return response()->json($response);
    }
    
    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to Display the enabled gateways at your MyFatoorah account to be displayed on the checkout page
     * Provide the checkout method with the order id to display its total amount and currency
     * 
     * @return View
     */
    public function checkout()
    {
        try {
            //You can get the data using the order object in your system
            if (request()->filled('oid')) {
                $orderId = request('oid');
                $order   = $this->getTestOrderData($orderId);

                //You can replace this variable with customer Id in your system
                $customerId = $order['patient_id'];

                //You can use the user defined field if you want to save card
                $userDefinedField = config('myfatoorah.save_card') && $customerId ? "CK-$customerId" : '';

                //Get the enabled gateways at your MyFatoorah acount to be displayed on checkout page
                $mfObj          = new MyFatoorahPaymentEmbedded($this->mfConfig);
                $paymentMethods = $mfObj->getCheckoutGateways($order['total'], $order['currency'], config('myfatoorah.register_apple_pay'));

                if (empty($paymentMethods['all'])) {
                    throw new Exception('noPaymentGateways');
                }

                //Generate MyFatoorah session for embedded payment
                $mfSession = $mfObj->getEmbeddedSession($userDefinedField);

                //Get Environment url
                $isTest = $this->mfConfig['isTest'];
                $vcCode = $this->mfConfig['countryCode'];

                $countries = MyFatoorah::getMFCountries();
                $jsDomain  = ($isTest) ? $countries[$vcCode]['testPortal'] : $countries[$vcCode]['portal'];

                return view('myfatoorah.checkout', compact('mfSession', 'paymentMethods', 'jsDomain', 'userDefinedField'));
            }elseif(request()->filled('did')){
                $orderId = request('did');
                
                $order   = $this->getDonationData($orderId);
               
                //You can replace this variable with customer Id in your system
                $customerId = $order['national_id'];

                //You can use the user defined field if you want to save card
                $userDefinedField = config('myfatoorah.save_card') && $customerId ? "CK-$customerId" : '';

                //Get the enabled gateways at your MyFatoorah acount to be displayed on checkout page
                $mfObj          = new MyFatoorahPaymentEmbedded($this->mfConfig);
                $paymentMethods = $mfObj->getCheckoutGateways($order['total'], $order['currency'], config('myfatoorah.register_apple_pay'));

                if (empty($paymentMethods['all'])) {
                    throw new Exception('noPaymentGateways');
                }

                //Generate MyFatoorah session for embedded payment
                $mfSession = $mfObj->getEmbeddedSession($userDefinedField);

                //Get Environment url
                $isTest = $this->mfConfig['isTest'];
                $vcCode = $this->mfConfig['countryCode'];

                $countries = MyFatoorah::getMFCountries();
                $jsDomain  = ($isTest) ? $countries[$vcCode]['testPortal'] : $countries[$vcCode]['portal'];

                return view('myfatoorah.checkout', compact('mfSession', 'paymentMethods', 'jsDomain', 'userDefinedField'));
            }
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            return view('myfatoorah.error', compact('exMessage'));
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------



    //-----------------------------------------------------------------------------------------------------------------------------------------
    private function getTestOrderData($orderId)
    {
        $appointment = Appointment::findOrFail($orderId);
        if ($appointment->paid) {
           abort(404);
        } else {
            return [
                'name' => $appointment->patient->name,
                'phone' => str_starts_with($appointment->patient->phone, "0") ? ltrim($appointment->patient->phone, '0') : $appointment->patient->phone,
                'patient_id' => $appointment->patient->id,
                'total'    => (float)  $this->patientService->price($appointment->patient,$appointment->doctor,$appointment->doctor->price),
                'currency' => config('app.currency', 'EGP'),
                'type'=>'appointment'
            ];
        }
        
      
    }
    private function getDonationData($orderId){
        $donation =Donation::findOrFail($orderId);
       
        return [
            'name' => $donation->name,
            'phone' => $donation->phone,
            'national_id' => $donation->national_id,
            'total'    => (double) $donation->value,
            'currency' => config('app.currency', 'EGP'),
            'type'=>'donation'

        ];
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------
    private function getTestMessage($status, $error)
    {
        if ($status == 'Paid') {
            return 'Invoice is paid.';
        } else if ($status == 'Failed') {
            return 'Invoice is not paid due to ' . $error;
        } else if ($status == 'Expired') {
            return $error;
        }
    }
    /**
     * Example on how the webhook is working when MyFatoorah try to notify your system about any transaction status update
     */
    public function webhook(Request $request)
    {
        try {
            //Validate webhook_secret_key
            $secretKey = config('myfatoorah.webhook_secret_key');
            if (empty($secretKey)) {
                return response(null, 404);
            }

            //Validate MyFatoorah-Signature
            $mfSignature = $request->header('MyFatoorah-Signature');
            if (empty($mfSignature)) {
                return response(null, 404);
            }

            //Validate input
            $body  = $request->getContent();
            $input = json_decode($body, true);
            if (empty($input['Data']) || empty($input['EventType']) || $input['EventType'] != 1) {
                return response(null, 404);
            }

            //Validate Signature
            if (!MyFatoorah::isSignatureValid($input['Data'], $secretKey, $mfSignature, $input['EventType'])) {
                return response(null, 404);
            }

            //Update Transaction status on your system
            $result = $this->changeTransactionStatus($input['Data']);

            return response()->json($result);
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            return response()->json(['IsSuccess' => false, 'Message' => $exMessage]);
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------
    private function changeTransactionStatus($inputData)
    {
        //1. Check if orderId is valid on your system.
        $orderId = $inputData['CustomerReference'];

        //2. Get MyFatoorah invoice id
        $invoiceId = $inputData['InvoiceId'];

        //3. Check order status at MyFatoorah side
        if ($inputData['TransactionStatus'] == 'SUCCESS') {
            $status = 'Paid';
            $error  = '';
        } else {
            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data  = $mfObj->getPaymentStatus($invoiceId, 'InvoiceId');

            $status = $data->InvoiceStatus;
            $error  = $data->InvoiceError;
        }

        $message = $this->getTestMessage($status, $error);

        //4. Update order transaction status on your system
        return ['IsSuccess' => true, 'Message' => $message, 'Data' => $inputData];
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------
}
