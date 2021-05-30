<?php

namespace App\Http\Controllers;

use App\Bookie_rate;
use App\History;
use App\Result;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class AutoResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $time = date('H:i');
        $data = file_get_contents('https://dpboss.net/');

        preg_match_all("/\<div class=\"satta-result\" style=\"border-color: #aa00c0;\"\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>(.*?)<\/div\>/is", $data, $matches);


        // $stripped = strip_tags($matches[10][0]);
        // return $stripped;

        function validiateResult($title, $gameName, $gameIndex, $type)
        {
            $stripped = strip_tags($title[$gameIndex][0]);
            if (strpos($stripped, $gameName) !== false) {
                $data = explode("\n", $stripped);

                $name = $gameName;
                // return $data;
                if (strpos($stripped, 'Loading...') !== false) {
                    print 'Loading' . '' . "\n";
                    sleep(20);
                } else {
                    if ($type == 'open') {
                        $lineSplit = explode("-", $data[3]);
                        // return $lineSplit[0];
                        return updateResult($name, $lineSplit[0], 'open');
                    } elseif ($type == 'close') {
                        $lineSplit = explode("-", $data[3]);
                        return updateResult($name, $lineSplit[2], 'close');
                    } else {
                        print 'nothhing';
                    }
                }
            } else {
            }
        }

        function updateResult($name, $number, $type)
        {
            $digit = $number;
            //get existing result 
            $result = Result::where('game_name', $name)->first();
            if ($type == 'open') {
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
                    Result::where('game_name', $name)->update([
                        'open' => $digit,
                        'jodi' => $final
                    ]);

                    $history = History::where('gameName', $name)
                        ->where('otc', 'open')
                        ->whereNull('resultStatus')
                        ->whereNull('result')
                        ->with('user')
                        ->get();
                    // return $history;

                    foreach ($history as $hist) {

                        //get game rates bye bookie
                        $rates = Bookie_rate::where('bookie_id', $hist->user->bookie_id)->first();
                        $singleRate = $rates->single;
                        $jodiRate = $rates->jodi;
                        $singlePattiRate = $rates->single_patti;
                        $doublePattiiRate = $rates->double_patti;
                        $tripplePattiRate = $rates->tripple_patti;
                        //singlePatti,doublePatti,tripplePatti,single,jodi
                        if ($hist->played_no == $final) {
                            $wonAmt = $singleRate * $hist->points;
                            $resultStatus = 'Won';
                            userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
                        } elseif ($hist->played_no == $digit) {
                            $resultStatus = 'Won';
                            if ($hist->gameType == 'singlePatti') {
                                //singlePatti
                                $wonAmt = $singlePattiRate * $hist->points;
                                userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
                            } elseif ($hist->gameType == 'doublePatti') {
                                $wonAmt = $doublePattiiRate * $hist->points;
                                userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
                            } elseif ($hist->gameType == 'tripplePatti') {
                                $wonAmt = $tripplePattiRate * $hist->points;
                                userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
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
                        ]);
                    }
                } // if open already update
                else {
                    return 'exist';
                }
            } elseif ($type == 'close') {
                if ($result->open == '***') {
                    return 'update open';
                }
                if ($result->close == '***') {

                    $getResult = Result::where('game_name', $name)->first();

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

                    Result::where('game_name', $name)
                        ->update([
                            'close' => $digit,
                            'jodi' => $jodi
                        ]);

                    $history = History::where('gameName', $name)
                        ->where('otc', 'close')
                        ->whereNull('resultStatus')
                        ->whereNull('result')
                        ->with('user')
                        ->get();

                    foreach ($history as $hist) {

                        //get game rates bye bookie
                        $rates = Bookie_rate::where('bookie_id', $hist->user->bookie_id)->first();
                        $singleRate = $rates->single;
                        $jodiRate = $rates->jodi;
                        $singlePattiRate = $rates->single_patti;
                        $doublePattiiRate = $rates->double_patti;
                        $tripplePattiRate = $rates->tripple_patti;
                        //singlePatti,doublePatti,tripplePatti,single,jodi

                        if ($hist->played_no == $final) {
                            if ($hist->gameType == 'jodi') {
                                //jodi for prefix 0
                                if ($hist->played_no == $jodi) {
                                    $wonAmt = $jodiRate * $hist->points;
                                    userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
                                    $resultStatus = 'Won';
                                } else {
                                    $resultStatus = 'Loss';
                                }
                            } else {
                                //single close
                                $wonAmt = $singleRate * $hist->points;
                                $resultStatus = 'Won';
                                userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
                            }
                        } elseif ($hist->played_no == $digit) {
                            if ($hist->gameType == 'singlePatti') {
                                $wonAmt = $singlePattiRate * $hist->points;
                                userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
                            } elseif ($hist->played_no == 'doublePatti') {
                                $wonAmt = $doublePattiiRate * $hist->points;
                                userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
                            } elseif ($hist->played_no == 'tripplePatti') {
                                $wonAmt = $tripplePattiRate * $hist->points;
                                userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
                            } else {
                            }
                            $resultStatus = 'Won';
                        } elseif ($hist->played_no == $jodi) {
                            $wonAmt = $jodiRate * $hist->points;
                            userBalUpdate($wonAmt, $hist->played_no, $hist->gameName, $hist->gameType, $hist->otc, $hist->user_id);
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
                        ]);
                    }
                    return 'update';
                } else {
                    return 'exist';
                }
            }
        }

        function userBalUpdate($wonAmt, $playedNumber, $gameName, $gtype, $otc, $userId)
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
                $history->save();
            }
        }


        // return validiateResult($matches, 'KALYAN', 10, 'open');

        //sridevi open 11:45 close close 12:45
        //time bazar open 13:10 close 14:10

        //sridevi start
        if ($time === '11:45') {
            return validiateResult($matches, 'SRIDEVI', 2, 'open');
        } elseif ($time === '12:45') {
            return validiateResult($matches, 'SRIDEVI', 2, 'close');
        }

        //time start
        elseif ($time === '13:15') {
            return validiateResult($matches, 'TIME BAZAR', 5, 'open');
        } elseif ($time === '14:15') {
            return validiateResult($matches, 'TIME BAZAR', 5, 'close');
        }

        // milan day start
        elseif ($time === '15:20') {
            return validiateResult($matches, 'MILAN DAY', 9, 'open');
        } elseif ($time === '17:20') {
            return validiateResult($matches, 'MILAN DAY', 9, 'close');
        }

        //rajdhani start
        elseif ($time === '15:50') {
            validiateResult($matches, 'RAJDHANI DAY', 10, 'open');
        } elseif ($time === '17:50') {
            validiateResult($matches, 'RAJDHANI DAY', 10, 'close');
        }

        //kalyan start
        elseif ($time === '16:25') {
            validiateResult($matches, 'KALYAN', 11, 'open');
        } elseif ($time === '18:20') {
            validiateResult($matches, 'KALYAN', 11, 'close');
        }

        //sridevi night
        elseif ($time === '19:10') {
            validiateResult($matches, 'SRIDEVI NIGHT', 12, 'open');
        } elseif ($time === '20:10') {
            validiateResult($matches, 'SRIDEVI NIGHT', 12, 'close');
        }

        //milan night
        elseif ($time === '21:10') {
            validiateResult($matches, 'MILAN NIGHT', 16, 'open');
        } elseif ($time === '23:10') {
            validiateResult($matches, 'MILAN NIGHT', 16, 'close');
        }

        //rajdhani night
        elseif ($time === '21:40') {
            validiateResult($matches, 'RAJDHANI NIGHT', 17, 'open');
        } elseif ($time === '23:55') {
            validiateResult($matches, 'RAJDHANI NIGHT', 17, 'close');
        }

        //Main Bazar
        elseif ($time === '21:45') {
            validiateResult($matches, 'MAIN BAZAR', 18, 'open');
        } elseif ($time === '00:15') {
            validiateResult($matches, 'MAIN BAZAR', 18, 'close');
        }

        //kalyan night
        elseif ($time === '21:25') {
            validiateResult($matches, 'KALYAN NIGHT', 22, 'open');
        } elseif ($time === '23:45') {
            validiateResult($matches, 'KALYAN NIGHT', 22, 'close');
        }
    }
}
