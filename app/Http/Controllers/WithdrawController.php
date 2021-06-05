<?php

namespace App\Http\Controllers;

use App\History;
use App\User;
use App\Withdraw;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Withdraw::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
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
     * @param  \Illuminate\Http\RWithdrawequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->points < 2000) { //min 2000
            return 'low_bal';
        }
        if ($request->amt < 2000) {
            return 'min_bal';
        }
        if ($request->amt > $user->points) {
            return 'insuff_ball';
        }
        $withdraw = new Withdraw;
        $withdraw->user_id = $user->id;
        $withdraw->amount = $request->amt;
        $withdraw->status = 'Requested';
        $withdraw->save();

        return response('success', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Withdraw  $withdraw
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
     * @param  \App\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $withdraw = Withdraw::where('id', $id)->with(['user' => function ($query) {
            $query->where('bookie_id', Auth::id());
        }])->first();


        if ($withdraw) {
            if ($request->type == 'approve') {
                $newBal = $withdraw->user->points - $withdraw->amount;
                User::where('id', $withdraw->user->id)->where('bookie_id', Auth::id())
                    ->update(['points' => $newBal]);
                // $withdraw->user->points = $newBal;
                $withdraw->reference = $request->reference;
                $withdraw->remarks = 'Approved By admin';
                $withdraw->status = 'Approved';
                $withdraw->save();

                $history = new History;
                $history->user_id = $withdraw->user->id;
                $history->description = 'Withdrawn by user';
                $history->points = $withdraw->amount;
                $history->balance = $withdraw->user->points;
                $history->playHistory = 'No';
                $history->resultStatus = 'Success';
                $history->type = 'Debit';
                $history->save();
                return $withdraw;
            } elseif ($request->type == 'cancel') {

                $withdraw->remarks = 'Cancelled by admin';
                $withdraw->reference = 'Insufficient balence';
                $withdraw->status = 'Cancelled';
                $withdraw->save();
                return $withdraw;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cancelByUser(Request $request)
    {
        Withdraw::where('id', $request->id)->where('user_id', Auth::id())->update(['status' => 'Cancelled', 'remarks' => 'Cancelled by user']);

        return 'cancelled';
    }


    public function withdrawList()
    {
        // return Withdraw::with(['user' => function ($query) {
        //     $query->where('bookie_id', Auth::id());
        // }])->orderByDesc('id')->get();

        return Withdraw::join('users', 'withdraws.user_id', '=', 'users.id')
            ->where('users.bookie_id', Auth::id())
            ->select('users.*', 'withdraws.*', 'users.id as userId', 'withdraws.status as wstatus')
            ->orderByDesc('withdraws.id')->get();
    }
}
