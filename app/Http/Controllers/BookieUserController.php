<?php

namespace App\Http\Controllers;

use App\Bookie_referal;
use App\History;
use App\Payment;
use App\token;
use App\User;
use Auth;
use Carbon\Carbon;
use DateTime;
use DB;
use Gate;
use Hash;
use Illuminate\Http\Request;


class BookieUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('bookie_id', Auth::id())->paginate(20);

        return $users;
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


        $usersRegister = User::where('bookie_id', Auth::id())->get();

        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,user_name',
            'email' => 'required|unique:users,email',
            'contact' => 'required|unique:users,contact_no',
            'password' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->user_name = $request->username;
        $user->email = $request->email;
        $user->contact_no = $request->contact;
        $user->password = Hash::make($request->password);
        $user->bookie_id = Auth::id();
        $user->role = 'user';
        $user->status = 'active';
        $user->rate_id = 1;
        $user->save();

        return 'created';
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

    public function searchUser(Request $request)
    {

        $users = User::where('user_name', 'LIKE', '%' . $request->key . '%')
            ->where(['bookie_id' => Auth::id(),])
            ->orWhere('contact_no', 'LIKE', '%' . $request->key . '%')
            ->where(['bookie_id' => Auth::id(),])
            ->orderByDesc('id')
            ->paginate(20);

        return $users;
    }

    public function searchUserForAuto(Request $request)
    {

        $users = User::where('user_name', 'LIKE', '%' . $request->key . '%')
            ->where(['bookie_id' => Auth::id(),])
            ->get();

        return $users;
    }

    public function addBal(Request $request)
    {
        $agent = User::where('id', Auth::id())->first();

        if ($agent->points >= $request->addBal) {
            $user = User::where('user_name', $request->userName)
                ->where('bookie_id', Auth::id())->first();
            $availPoints = $user->points;
            $points = $availPoints + $request->addBal;


            $history = new History;
            $history->user_id = $user->id;
            $history->description = 'Buy';
            $history->points = $request->addBal;
            $history->balance = $points;
            $history->resultStatus = 'Success';
            $history->type = 'Credit';
            $history->save();

            $user->points = $points;
            $user->save();

            $agent->points = $agent->points - $request->addBal;
            $agent->save();
            return $user;
        } else {
            return 'Insufficient balance';
        }
    }
    public function userStatus(Request $request)
    {
        $user = User::where('id', $request->id)
            ->where('bookie_id', Auth::id())->update(['status' => $request->type]);

        return $user;
    }



    public function userHistory($id)
    {
        $user = User::where('id', $id)->where('bookie_id', Auth::id())->get();
        if (count($user) > 0) {
            return History::where('user_id', $id)->orderByDesc('id')->paginate(30);
        }
        return 'nothing';
    }

    public function generateReport(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $userName = $request->userName;
        $gameName = $request->gameName;

        // return [$fromDate, $toDate];
        if ($userName == '') {
            $singleOpen = $this->getRe($gameName, $fromDate, $toDate, 'single', 'open');
            $singleClose = $this->getRe($gameName, $fromDate, $toDate, 'single', 'close');
            $jodi = $this->getRe($gameName, $fromDate, $toDate, 'jodi', 'close');
            $singlePattiOpen = $this->getRe($gameName, $fromDate, $toDate, 'singlePatti', 'open');
            $singlePattiClose = $this->getRe($gameName, $fromDate, $toDate, 'singlePatti', 'close');
            $doublePattiOpen = $this->getRe($gameName, $fromDate, $toDate, 'doublePatti', 'open');
            $doublePattiClose = $this->getRe($gameName, $fromDate, $toDate, 'doublePatti', 'close');
            $tripplePattiOpen = $this->getRe($gameName, $fromDate, $toDate, 'tripplePatti', 'open');
            $tripplePattiClose = $this->getRe($gameName, $fromDate, $toDate, 'tripplePatti', 'close');
            return [
                'singleOpen' => $singleOpen,
                'singleClose' => $singleClose,
                'jodi' => $jodi,
                'singlePattiOpen' => $singlePattiOpen,
                'singlePattiClose' => $singlePattiClose,
                'doublePattiOpen' => $doublePattiOpen,
                'doublePattiClose' => $doublePattiClose,
                'tripplePattiOpen' => $tripplePattiOpen,
                'tripplePattiClose' => $tripplePattiClose
            ];
        } else {
            $singleOpen = $this->getReByUser($gameName, $fromDate, $toDate, 'single', 'open', $userName);
            $singleClose = $this->getReByUser($gameName, $fromDate, $toDate, 'single', 'close', $userName);
            $jodi = $this->getReByUser($gameName, $fromDate, $toDate, 'jodi', 'close', $userName);
            $singlePattiOpen = $this->getReByUser($gameName, $fromDate, $toDate, 'singlePatti', 'open', $userName);
            $singlePattiClose = $this->getReByUser($gameName, $fromDate, $toDate, 'singlePatti', 'close', $userName);
            $doublePattiOpen = $this->getReByUser($gameName, $fromDate, $toDate, 'doublePatti', 'open', $userName);
            $doublePattiClose = $this->getReByUser($gameName, $fromDate, $toDate, 'doublePatti', 'close', $userName);
            $tripplePattiOpen = $this->getReByUser($gameName, $fromDate, $toDate, 'tripplePatti', 'open', $userName);
            $tripplePattiClose = $this->getReByUser($gameName, $fromDate, $toDate, 'tripplePatti', 'close', $userName);
            return [
                'singleOpen' => $singleOpen,
                'singleClose' => $singleClose,
                'jodi' => $jodi,
                'singlePattiOpen' => $singlePattiOpen,
                'singlePattiClose' => $singlePattiClose,
                'doublePattiOpen' => $doublePattiOpen,
                'doublePattiClose' => $doublePattiClose,
                'tripplePattiOpen' => $tripplePattiOpen,
                'tripplePattiClose' => $tripplePattiClose
            ];
        }
    }

    function getRe($gameName, $fromDate, $toDate, $gameType, $otc)
    {


        $history = History::join('users', 'histories.user_id', '=', 'users.id')
            ->where('users.bookie_id', Auth::id())
            ->where('histories.gameName', $gameName)
            ->where('histories.gameType', $gameType)
            ->where('histories.otc', $otc)
            ->whereDate('histories.created_at', '>=', $fromDate)
            ->whereDate('histories.created_at', '<=', $toDate)
            // ->whereBetween('histories.created_at', [$fromDate, $toDate])
            ->select('played_no', DB::raw('SUM(histories.points) as amount'), DB::raw('SUM(histories.wonAmt) as wonAmt'))
            ->groupBy('played_no')
            ->orderByRaw('played_no ASC')
            ->get();
        return $history;
    }

    function getReByUser($gameName, $fromDate, $toDate, $gameType, $otc, $userName)
    {
        $history = History::where('user_id', $userName['id'])
            ->where('histories.gameName', $gameName)
            ->where('histories.gameType', $gameType)
            ->where('histories.otc', $otc)
            ->whereDate('histories.created_at', '>=', $fromDate)
            ->whereDate('histories.created_at', '<=', $toDate)
            // ->whereBetween('histories.created_at', [$fromDate, $toDate])
            ->select('played_no', DB::raw('SUM(histories.points) as amount'), DB::raw('SUM(histories.wonAmt) as wonAmt'))
            ->groupBy('played_no')
            ->orderByRaw('played_no ASC')
            ->get();
        return $history;
    }


    public function searchToken(Request $request)
    {

        $users = token::where('token', 'LIKE', '%' . $request->key . '%')
            ->where(['bookie_id' => Auth::id(),])
            ->orWhere('name', 'LIKE', '%' . $request->key . '%')
            ->where(['bookie_id' => Auth::id(),])
            ->orderByDesc('id')
            ->paginate(20);

        return $users;
    }

    public function tokenHistory($id)
    {

        return History::where('token', $id)->orderByDesc('id')->paginate(30);
    }
}
