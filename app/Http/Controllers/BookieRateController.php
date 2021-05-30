<?php

namespace App\Http\Controllers;

use App\Bookie_rate;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookieRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Bookie_rate::all();
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
        if ($request->type == 'new') {
            $rate = new Bookie_rate;
            $rate->single = $request->single;
            $rate->jodi = $request->jodi;
            $rate->single_patti = $request->singlePatti;
            $rate->double_patti = $request->doublePatti;
            $rate->tripple_patti = $request->tripplePatti;
            $rate->save();
            return $rate;
        } elseif ($request->type == 'edit') {
            $rate = Bookie_rate::where('id', $request->rateId)->update([
                'single' => $request->single,
                'jodi' => $request->jodi,
                'single_patti' => $request->singlePatti,
                'double_patti' => $request->doublePatti,
                'tripple_patti' => $request->tripplePatti,
            ]);
            return $rate;
        } else {
            return 'Something went wrong';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bookie_rate  $bookie_rate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Bookie_rate::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bookie_rate  $bookie_rate
     * @return \Illuminate\Http\Response
     */
    public function edit(Bookie_rate $bookie_rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bookie_rate  $bookie_rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bookie_rate  $bookie_rate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Bookie_rate::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function getBookieRates()
    {
        $user  = Auth::user();
        return Bookie_rate::where('id', $user->rate_id)->first();
        // return Bookie_rate::with('bookie')->where('bookie_id', $user->bookie_id)->first();
    }

    public function getBookie()
    {
        $user  = Auth::user();
        return User::where('id', $user->bookie_id)->first();
    }
}
