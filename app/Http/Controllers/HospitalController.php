<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Hospital;
use App\Models\Transaction;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

class HospitalController extends Controller
{
    public function index()
    {
        $hospital = Hospital::first();
        return view('admin.hospital', ['hospital' => $hospital]);
    }
    public function update(Request $request)
    {

        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'email' => ['required',],
                'address' => ['required', 'min:8'],
                'phone1' => ['required', 'max:13'],
                'phone2' => ['required', 'max:13'],
                'hotline' => ['required', 'max:7'],

            ]
        );

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $hospital = Hospital::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'address' => $request->address,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'hotline' => $request->hotline,

            ]
        );
        return redirect()->back()->with('success', 'hospital updated successfully!');
    }
    public function sheet($id)
    {

        $transaction = Transaction::where('PaymentId', $id)->first();
        if ($transaction) {
            return view('sheet', compact('transaction'));
        } else {
            abort(404);
        }
    }
    public function exportSheet($id)
    {

        $transaction = Transaction::where('PaymentId', $id)->first();
        if ($transaction) {
            
            $html = View::make('exportSheetTemplate', compact('transaction'))->render();
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
    // balance
    
}
