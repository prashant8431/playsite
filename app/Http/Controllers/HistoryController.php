<?php

namespace App\Http\Controllers;

use App\History;
use App\User;
use Auth;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return History::where('user_id', Auth::id())->orderByDesc('id')->paginate(30);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $history = History::find($id);
        $userId = Auth::id();
        $user = User::find($userId);

        $deductedPoints = $history->points;
        $lastBal = $user->points;
        $newBal = $lastBal + $deductedPoints;

        $newHistory = new History;
        $newHistory->user_id = $userId;
        $newHistory->description = 'RECIEVED For Cancellation Of bid on ' . $history->gameName . ' ' . $history->gameType . ' ' . $history->otc . ' ' . $history->played_no . '';
        $newHistory->points = $deductedPoints;
        $newHistory->balance = $newBal;
        $newHistory->playHistory = 'No';
        $newHistory->resultStatus = 'Success';
        $newHistory->result = 'Bid Cancelled by user';
        $newHistory->type = 'Credit';
        $newHistory->save();

        $history->resultStatus = 'Bid Cancelled by user';
        $history->save();
        $user->points = $newBal;
        $user->save();

        return 'cancelled';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
