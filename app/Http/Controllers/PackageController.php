<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Package::all();
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
            $package = new Package();
            $package->name = $request->name;
            $package->description = $request->descri;
            $package->no_of_users = $request->no_users;
            $package->amount = $request->amt;
            $package->validity = $request->validity;
            $package->status = 'active';
            $package->save();
        } elseif ($request->type == 'edit') {
            $package = Package::where('id', $request->packId)
                ->update([
                    'name' => $request->name,
                    'description' => $request->descri,
                    'no_of_users' => $request->no_users,
                    'amount' => $request->amt,
                    'validity' => $request->validity,
                    'status' => $request->status
                ]);
        }


        return response($package, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        //
    }
}
