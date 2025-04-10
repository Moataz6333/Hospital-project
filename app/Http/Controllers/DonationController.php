<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Donation;
use App\Services\DonationService;
use App\Interfaces\PaymentInterface;
use App\Models\Transaction;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $paymentService;
    public function __construct(PaymentInterface $paymentService ) {
        $this->paymentService = $paymentService;
    }
    public function index()
    {
        return view('reciption.donations.index',['donations'=>Donation::all()->reverse()]);
    }

    
    public function create()
    {
        return view('reciption.donations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'phone' => ['required', 'max:13'],
                'national_id' => ['required', 'max:50'],
                'value' => ['required'],
                'currency' => ['required', Rule::in(['EGP', 'KWD'])],
                'payment_method' => ['required', Rule::in(['cash', 'online'])],
                'paid' => 'nullable',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->payment_method == 'cash') {
            $donation = Donation::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'value' => abs((float) $request->value),
                'currency' => $request->currency,
                'payment_method' => 'cash',
                'registration_method' => 'reception',
                'paid'=>key_exists("paid", $request->all()) ? true :false
            ]);
            return redirect()->back()->with('success','donation done successfully');
        } elseif ($request->payment_method == 'online') {
            $donationService =new DonationService();
            $donation =$donationService->registerOnline($validator->validated(),'reception');
            return $this->paymentService->donate($donation);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $donation =Donation::findOrFail($id);
        return view('reciption.donations.show',compact('donation'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $donation =Donation::findOrFail($id);
        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'phone' => ['required', 'max:13'],
                'national_id' => ['required', 'max:50'],
                'value' => ['required'],
                'currency' => ['required', Rule::in(['EGP', 'KWD'])],
                'payment_method' => ['required', Rule::in(['cash', 'online'])],
                'paid' => 'nullable',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->payment_method == 'cash') {
            $donation->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'value' => abs((float) $request->value),
                'currency' => $request->currency,
                'payment_method' => 'cash',
                'registration_method' => 'reception',
                'paid'=>key_exists("paid", $request->all()) ? true :false
            ]);
            return redirect()->back()->with('success','donation updated successfully');
        } elseif ($request->payment_method == 'online') {
            $donation->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'value' => abs((float) $request->value),
                'currency' => $request->currency,
                'payment_method' => 'online',
                'registration_method' => 'reception',
            ]);
            return redirect()->back()->with('success','donation updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $donation =Donation::findOrFail($id);
        $donation->delete();
        return to_route('donations.index');
    }
    public function sheet($id)
    {
        $transaction = Transaction::where('PaymentId', $id)->first();
        if ($transaction) {
            return view('reciption.donations.sheet', ['donation'=>$transaction->donation]);
        } else {
            abort(404);
        }
    }
    public function export($id)
    {
        $transaction = Transaction::where('PaymentId', $id)->first();
        $donation =$transaction->donation;
        if ($transaction) {
            
            $html = View::make('reciption.donations.export', compact('transaction','donation'))->render();
            // return view('exportSheetTemplate',compact('transaction'));
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'dejavusans', // Arabic font
                'default_font_size' => 12,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
    
            $mpdf->WriteHTML($html);
            return response($mpdf->Output("invoice $transaction->PaymentId.pdf", 'D'))
            ->header('Content-Type', 'application/pdf');

        } else {
            abort(404);
        }
    }
}
