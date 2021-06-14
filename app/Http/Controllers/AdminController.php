<?php

namespace App\Http\Controllers;

use App\History;
use App\User;
use App\Withdraw;
use Auth;
use DB;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
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

    public function getUsers(Request $request)
    {

        $relevence = $request->relevence;
        if ($relevence === 'all') {
            $users = User::where('user_name', 'LIKE', '%' . $request->key . '%')
                ->where('role', 'user')
                ->orWhere('contact_no', 'LIKE', '%' . $request->key . '%')
                ->where('role', 'user')
                ->paginate(20);

            return $users;
        } elseif ($relevence === 'moderator') {
            $users = User::where('role', 'moderator')
                ->paginate(20);
            return $users;
        }
        $users = User::where('user_name', 'LIKE', '%' . $request->key . '%')
            ->where('role', 'user')
            ->where('status', $relevence)
            ->orWhere('contact_no', 'LIKE', '%' . $request->key . '%')
            ->where('role', 'user')
            ->where('status', $relevence)
            ->paginate(20);

        return $users;
    }

    public function bookieList()
    {
        return User::where('role', 'bookie')->get();
    }

    public function assignBookie(Request $request)
    {
        // return $request->selectBookie;
        History::where('user_id', $request->userId)->delete();
        Withdraw::where('user_id', $request->userId)->delete();

        User::where('id', $request->userId)->update(['bookie_id' => $request->selectBookie, 'status' => 'active']);

        return response('updated', Response::HTTP_ACCEPTED);
    }


    //make bookie code

    public function makeBookie(Request $request)
    {
        if ($request->type === 'bookie') {
            History::where('user_id', $request->id)->delete();
            Withdraw::where('user_id', $request->id)->delete();

            User::where('id', $request->id)->update(['role' => 'bookie', 'bookie_id' => $request->id, 'status' => 'active']);

            return response('updated', Response::HTTP_ACCEPTED);
        } elseif ($request->type === 'moderator') {
            History::where('user_id', $request->id)->delete();
            Withdraw::where('user_id', $request->id)->delete();

            User::where('id', $request->id)->update(['role' => 'moderator', 'bookie_id' => '', 'status' => 'active']);

            return response('updated', Response::HTTP_ACCEPTED);
        }
    }



    public function bookieListIndex(Request $request)
    {
        $relevence = $request->relevence;
        if ($relevence === 'all') {
            $users = User::where('user_name', 'LIKE', '%' . $request->key . '%')
                ->where('role', 'bookie')
                ->orWhere('contact_no', 'LIKE', '%' . $request->key . '%')
                ->where('role', 'bookie')
                ->paginate(20);

            return $users;
        }
        $users = User::where('user_name', 'LIKE', '%' . $request->key . '%')
            ->where('role', 'bookie')
            ->where('status', $relevence)
            ->orWhere('contact_no', 'LIKE', '%' . $request->key . '%')
            ->where('role', 'bookie')
            ->where('status', $relevence)
            ->paginate(20);

        return $users;
    }


    public function searchUser(Request $request)
    {

        $users = User::where('user_name', 'LIKE', '%' . $request->key . '%')
            ->orWhere('contact_no', 'LIKE', '%' . $request->key . '%')
            ->get();

        return $users;
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


        $history = History::where('histories.gameName', $gameName)
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

    public function addBalAdmin(Request $request)
    {
        $admin = User::where('id', Auth::id())->first();
        if ($request->userName == $admin->user_name) {
            $user = User::where('user_name', $request->userName)->first();
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
            return $user;
        }
        if ($admin->points >= $request->addBal) {
            $user = User::where('user_name', $request->userName)->first();
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

            $admin->points = $admin->points - $request->addBal;
            $admin->save();
            return $user;
        } else {
            return 'Insufficient balance';
        }
    }

    public function agentStatus(Request $request)
    {
        $user = User::where('id', $request->id)
            ->update(['status' => $request->type]);

        return $user;
    }

    public function deleteUser(Request $request)
    {
        DB::table('oauth_access_tokens')->where('user_id', $request->userId)->delete();

        History::where('user_id', $request->userId)->delete();

        User::where('id', $request->userId)->delete();

        return 'Deleted';
    }



    public function getWinnerList(Request $request)
    {

        $list = History::join('users', 'histories.user_id', '=', 'users.id')
            ->where('users.user_name', 'LIKE', '%' . $request->key . '%')
            ->where('histories.description', 'LIKE', '%WON%')
            ->whereDate('histories.created_at', '>=', $request->fromDate)
            ->whereDate('histories.created_at', '<=', $request->toDate)
            ->select('users.*', 'histories.*', 'histories.points as wonAmt', 'users.points as userBal', 'users.id as userId')
            ->orderByDesc('histories.id')
            ->paginate(20);

        return $list;
    }


    public function adminHistory($id)
    {

        return History::where('user_id', $id)->orderByDesc('id')->paginate(30);
    }
}
