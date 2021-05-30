<?php

namespace App\Http\Controllers;

use App\Bookie_referal;
use App\Http\Requests\RegisterRequest;
use App\Payment;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request['name'], 'password' => $request['password']]) || Auth::attempt(['user_name' => $request['name'], 'password' => $request['password']]) || Auth::attempt(['contact_no' => $request['name'], 'password' => $request['password']])) {
            $user = Auth::user();

            $token = $user->createToken('admin')->accessToken;

            return [
                'token' => $token,
            ];
        }

        return response([
            'error' => 'Invalid Credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function register(RegisterRequest $request)
    {
        $bookie = Bookie_referal::where('referal_code', $request->bookie_id)->first();
        if (!isset($bookie)) {
            return 'wrong';
        }


        $user = User::create([
            'name' => $request->input('name'),
            'user_name' => $request->input('user_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'contact_no' => $request->input('contact_no'),
            'bookie_id' => $bookie->user_id,
            'role' => 'user',
            'status' => 'active',
            'points' => 20,
            'rate_id' => 1,
        ]);

        return response($user, Response::HTTP_CREATED);
    }

    public function checkAvailabel(Request $request)
    {
        $user = User::where('user_name', $request->input('val'))->orWhere('email', $request->input('val'))->orWhere('contact_no', $request->input('val'))->count();
        // return response($user, Response::HTTP_CREATED);
        if ($user > 0) {
            return [
                'msg' => 'exist'
            ];
        }

        return [
            'msg' => 'not_exist'
        ];
    }
}
