<?php

namespace App\Http\Controllers;

use App\Game;
use App\History;
use App\token;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class PlayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $user = Auth::user();

        if ($user->points < 10) {
            return response('Insufficient Balance');
        }
        date_default_timezone_set('Asia/Kolkata');

        $cTime = date('H:i');
        $day = date('l');


        $game = Game::find($request->gameId);

        $runTime = json_decode($game->runningTime, true);
        foreach ($runTime as $key => $runItem) {
            if ($day == $runItem['day']) {
                $count = $key;
            }
        }



        $openTime = $runTime[$count]['bidOpenTime'];
        $closeTime = $runTime[$count]['bidCloseTime'];

        if ($cTime >= $closeTime) {
            return response(['Error', 'Oops!.. You are late...']);
        } elseif ($cTime >= $openTime && $request->type == 'jodi') {
            return response(['Error', 'Oops!.. You are late...']);
        } elseif ($cTime >= $openTime && $request->otc == 'open') {
            return response(['Error', 'Oops!.. You are late...']);
        } else {

            if ($request->user === 'bookie') {

                $tkn = $this->unique_code(6);

                $token = new token;
                $token->bookie_id = $user->id;
                $token->token = $tkn;
                $token->status = 'open';
                $token->name = $request->name;
                $token->save();

                foreach ($request->playArray as $key => $item) {
                    if ($item['amt'] < 10) {
                        return response(['Error', 'Min bid is 10 rs']);
                    }
                    if ($user->points < $item['amt']) {
                        return response(['Error', 'Insufficient Balance..']);
                    }
                    if ($cTime >= $openTime && $item['playType'] == 'jodi') {
                        return response(['Error', 'Oops!.. You are late...']);
                    } elseif ($cTime >= $openTime && $item['ocType'] == 'open') {
                        return response(['Error', 'Oops!.. You are late...']);
                    }
                    $balance = $user->points - $item['amt'];
                    // User::where('id', $user->id)->update(['points' => $balance]);
                    $user->points = $balance;
                    $user->save();
                    $history = new History;
                    $history->user_id = $user->id;
                    $history->token = $tkn;
                    $history->gameName = $request->gameName;
                    $history->gameType = $item['playType'];
                    $history->otc = $item['ocType'];
                    $history->played_no = $item['number'];
                    $history->playHistory = 'Yes';
                    $history->type = 'Debit';
                    $history->balance = $balance;
                    $history->points = $item['amt'];
                    $history->save();
                }
                return response(['Success', 'Your Token is : ' . $tkn], Response::HTTP_ACCEPTED);
            } else {
                foreach ($request->playArray as $key => $item) {
                    if ($item['amount'] < 10) {
                        return response(['Error', 'Min bid is 10 rs']);
                    }
                    if ($user->points < $item['amount']) {
                        return response(['Error', 'Insufficient Balance..']);
                    }
                    $balance = $user->points - $item['amount'];
                    // User::where('id', $user->id)->update(['points' => $balance]);
                    $user->points = $balance;
                    $user->save();
                    $history = new History;
                    $history->user_id = $user->id;
                    $history->gameName = $request->gameName;
                    $history->gameType = $request->type;
                    $history->otc = $request->otc;
                    $history->played_no = $item['digit'];
                    $history->playHistory = 'Yes';
                    $history->type = 'Debit';
                    $history->balance = $balance;
                    $history->points = $item['amount'];
                    $history->save();
                }

                return response(['Success', 'Game submitted..'], Response::HTTP_ACCEPTED);
            }
        }
    }


    function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
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
        //
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
