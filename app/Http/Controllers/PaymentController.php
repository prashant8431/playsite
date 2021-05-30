<?php

namespace App\Http\Controllers;

use App\Package;
use App\Payment;
use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Payment::with('user')->with('package')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $package = Package::find($request->packId);
        $exitPayment = Payment::where('user_id', $request->bookieId)->get();
        if (count($exitPayment) > 0) {
            if ($request->deposite > 0) {
                $updateArr = [
                    'package_id' => $package->id,
                    'amount' => $package->amount,
                    'deposite' => $request->deposite,
                ];
            } else {
                $updateArr = [
                    'package_id' => $package->id,
                    'amount' => $package->amount,
                ];
            }
            Payment::where('user_id', $request->bookieId)->update($updateArr);
            return 'update';
        }

        $payment = new Payment;
        $payment->user_id = $request->bookieId;
        $payment->package_id = $request->packId;
        $payment->amount = $package->amount;
        $payment->deposite = $request->deposite;
        $payment->paid_date = date('Y-m-d');
        $payment->save();
        return 'added';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->type == 'paid') {
            Payment::where('id', $id)->update([
                'status' => 'paid',
                'paid_date' => date('Y-m-d')
            ]);
            User::where('id', $request->bookie_id)->update([
                'status' => 'active',
            ]);
            return 'updates';
        } elseif ($request->type == 'unpaid') {
            Payment::where('id', $id)->update([
                'status' => 'unpaid',
            ]);
            User::where('id', $request->bookie_id)->update([
                'status' => 'suspend',
            ]);
            return 'updates';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function autoPaymentCheck()
    {
        // $date = date("Y-m-d", strtotime('+5 days', strtotime('2021-03-01')));
        // return $date;
        $curDate = date('Y-m-d');
        $payment = Payment::with('package')->get();
        // return $payment;
        foreach ($payment as $userPay) {
            $dueDate = date("Y-m-d", strtotime('+' . $userPay->package->validity . ' days', strtotime($userPay->paid_date)));
            if ($curDate >= $dueDate) {
                Payment::where('id', $userPay->id)->update([
                    'status' => 'unpaid',
                ]);
                User::where('id', $userPay->user_id)->update([
                    'status' => 'suspend',
                ]);
                return 'suspend';
            }
        }
    }
}
