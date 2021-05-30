<?php

namespace App\Http\Controllers;

use App\Bookie_rate;
use App\History;
use App\Http\Requests\ResultRequest;
use App\Result;
use App\User;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Result::with('game')->get();
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
        Gate::authorize('admin');

        $digit = $request->number;
        //get existing result 
        $result = Result::where('game_name', $request->gameName)->first();
        if ($request->playType == 'open') {
            if ($result->open == '***') {
                $singleAnk = $digit[0] + $digit[1] + $digit[2];

                if ($singleAnk >= 10) {
                    if ($singleAnk >= 20) {
                        $final = $singleAnk - 20;
                    } else {
                        $final = $singleAnk - 10;
                    }
                } else {
                    $final = $singleAnk;
                }
                Result::where('game_name', $request->gameName)->update([
                    'open' => $request->number,
                    'jodi' => $final
                ]);

                $history = History::where('gameName', $request->gameName)
                    ->where('otc', 'open')
                    ->whereNull('resultStatus')
                    ->whereNull('result')
                    ->with('user')
                    ->get();
                // return $history;

                foreach ($history as $hist) {

                    //get game rates bye bookie
                    $rates = Bookie_rate::where('id', $hist->user->rate_id)->first();
                    $singleRate = $rates->single;
                    $jodiRate = $rates->jodi;
                    $singlePattiRate = $rates->single_patti;
                    $doublePattiiRate = $rates->double_patti;
                    $tripplePattiRate = $rates->tripple_patti;
                    $wonAmt = 0;
                    //singlePatti,doublePatti,tripplePatti,single,jodi
                    if ($hist->played_no == $final) {
                        $wonAmt = $singleRate * $hist->points;
                        $resultStatus = 'Won';
                        $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                    } elseif ($hist->played_no == $digit) {
                        $resultStatus = 'Won';
                        if ($hist->gameType == 'singlePatti') {
                            //singlePatti
                            $wonAmt = $singlePattiRate * $hist->points;
                            $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                        } elseif ($hist->gameType == 'doublePatti') {
                            $wonAmt = $doublePattiiRate * $hist->points;
                            $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                        } elseif ($hist->gameType == 'tripplePatti') {
                            $wonAmt = $tripplePattiRate * $hist->points;
                            $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                        } else {
                        }
                    } else {
                        $resultStatus = 'Loss';
                    }
                    if ($hist->gameType == 'single') {
                        $winNum = $final;
                    } else {
                        $winNum = $digit;
                    }


                    History::where('id', $hist->id)->update([
                        'result' => $winNum,
                        'resultstatus' => $resultStatus,
                        'wonAmt' => $wonAmt,
                    ]);
                }
            } // if open already update
            else {
                return 'exist';
            }
        } elseif ($request->playType == 'close') {
            if ($result->open == '***') {
                return 'update open';
            }
            if ($result->close == '***') {

                $getResult = Result::where('game_name', $request->gameName)->first();

                $singleAnk = $digit[0] + $digit[1] + $digit[2];

                if ($singleAnk >= 10) {
                    if ($singleAnk >= 20) {
                        $final = $singleAnk - 20;
                    } else {
                        $final = $singleAnk - 10;
                    }
                } else {
                    $final = $singleAnk;
                }

                //get jodi
                $jodi = $getResult->jodi . $final;

                Result::where('game_name', $request->gameName)
                    ->update([
                        'close' => $request->number,
                        'jodi' => $jodi
                    ]);

                $history = History::where('gameName', $request->gameName)
                    ->where('otc', 'close')
                    ->whereNull('resultStatus')
                    ->whereNull('result')
                    ->get();

                foreach ($history as $hist) {

                    //get game rates bye bookie
                    $rates = Bookie_rate::where('id', $hist->user->rate_id)->first();
                    $singleRate = $rates->single;
                    $jodiRate = $rates->jodi;
                    $singlePattiRate = $rates->single_patti;
                    $doublePattiiRate = $rates->double_patti;
                    $tripplePattiRate = $rates->tripple_patti;
                    $wonAmt = 0;
                    //singlePatti,doublePatti,tripplePatti,single,jodi

                    if ($hist->played_no == $final) {
                        if ($hist->gameType == 'jodi') {
                            //jodi for prefix 0
                            if ($hist->played_no == $jodi) {
                                $wonAmt = $jodiRate * $hist->points;
                                $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                                $resultStatus = 'Won';
                            } else {
                                $resultStatus = 'Loss';
                            }
                        } else {
                            //single close
                            $wonAmt = $singleRate * $hist->points;
                            $resultStatus = 'Won';
                            $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                        }
                    } elseif ($hist->played_no == $digit) {
                        if ($hist->gameType == 'singlePatti') {
                            $wonAmt = $singlePattiRate * $hist->points;
                            $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                        } elseif ($hist->played_no == 'doublePatti') {
                            $wonAmt = $doublePattiiRate * $hist->points;
                            $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                        } elseif ($hist->played_no == 'tripplePatti') {
                            $wonAmt = $tripplePattiRate * $hist->points;
                            $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                        } else {
                        }
                        $resultStatus = 'Won';
                    } elseif ($hist->played_no == $jodi) {
                        $wonAmt = $jodiRate * $hist->points;
                        $this->userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id, $hist->token);
                        $resultStatus = 'Won';
                    } else {
                        $resultStatus = 'Loss';
                    }
                    if ($hist->gameType == 'single') {
                        $winNum = $final;
                    } elseif ($hist->gameType == 'jodi') {
                        $winNum = $jodi;
                    } else {
                        $winNum = $digit;
                    }

                    History::where('id', $hist->id)->update([
                        'result' => $winNum,
                        'resultstatus' => $resultStatus,
                        'wonAmt' => $wonAmt,
                    ]);
                }
                return 'update';
            } else {
                return 'exist';
            }
        }
    }

    function userBalUpdate($wonAmt, $playedNumber, $gameName, $gtype, $otc, $userId, $token)
    {
        $user = User::find($userId);
        if (isset($user)) {
            $userBal = $user->points;

            $newBal = $userBal + $wonAmt;
            User::where('id', $userId)->update(['points' => $newBal]);

            $history = new History();
            $history->user_id = $userId;
            $history->description = 'WON ' . $gameName . ' ' . $gtype . ' ' . $otc . ' ' . $playedNumber . '';
            $history->points = $wonAmt;
            $history->balance = $newBal;
            $history->type = 'Credit';
            $history->result = $playedNumber;
            $history->resultStatus = 'Success';
            $history->token = $token;
            $history->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Result::with('game')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Result  $result
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
     * @param  \App\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('admin');
        $result = Result::find($id);

        if ($request->type == 'open') {
            $result->open = $request->open;
        } elseif ($request->type == 'close') {
            $result->close = $request->close;
        } else {
            return response('something is wrong', Response::HTTP_EXPECTATION_FAILED);
        }

        return response($result, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Result::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function reset()
    {
        Result::whereNotNull('id')->update([
            'open' => '***',
            'close' => '***',
            'jodi' => '**'
        ]);
        return 'reseted';
    }

    public function cancelGame(Request $request)
    {
        if ($request->type == 'cancel') {
            $userHistory = History::where('gameName', $request->gameName)
                ->whereNull('resultStatus')->whereNull('result')->get();
            foreach ($userHistory as $histItem) {
                $playedPoints = $histItem->points;
                $user = User::find($histItem->user_id);
                $userBal = $user->points;
                $updateBal = $userBal + $playedPoints;
                $user->points = $updateBal;
                $user->save();

                $history = new History;
                $history->user_id = $histItem->user_id;
                $history->description = 'RECIEVED for cancellation of ' . $histItem->gameName . ' ' . $histItem->gameType . ' ' . $histItem->otc . '';
                $history->points = $playedPoints;
                $history->balance = $updateBal;
                $history->type = 'Credit';
                $history->result = 'Game cancelled';
                $history->resultStatus = 'Success';
                $history->save();

                $histItem->resultStatus = 'Game cancelled';
                $histItem->save();
            }
            return 'cancelled';
        } elseif ($request->type == 'wrong') {
            $userHistory = History::where('gameName', $request->gameName)
                ->whereRaw('Date(created_at) = CURDATE()')
                ->where('resultStatus', 'Won')
                ->orWhere('gameName', $request->gameName)
                ->whereRaw('Date(created_at) = CURDATE()')
                ->where('resultStatus', 'Loss')
                ->get();
            return $userHistory;
        }
    }
}
