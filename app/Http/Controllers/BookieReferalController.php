<?php

namespace App\Http\Controllers;

use App\Bookie_referal;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookieReferalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Bookie_referal::where('user_id', Auth::id())->first();
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

        $update =  Bookie_referal::where('user_id', Auth::id())->update([
            'referal_code' => $request->code,
            'status' => 'active'
        ]);

        if ($update) {
            return $update;
        } else {
            $referal = new Bookie_referal;
            $referal->user_id = Auth::id();
            $referal->referal_code = $request->code;
            $referal->status = 'active';
            $referal->save();
            return $referal;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bookie_referal  $bookie_referal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Bookie_referal::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bookie_referal  $bookie_referal
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
     * @param  \App\Bookie_referal  $bookie_referal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bookie_referal  $bookie_referal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Bookie_referal::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
