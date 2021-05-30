<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        return Auth::user();
    }

    public function update(Request $request, $id)
    {
        $user = User::find(Auth::id());

        if ($request->type == 'pass') {
            $user->password = Hash::make($request->password);
        } elseif ($request->type == 'bank') {
            $user->bank_name = $request->bank;
            $user->acc_no = $request->accNo;
            $user->ifsc = $request->ifsc;
            $user->upi = $request->upi;
        }

        $user->save();

        return response($user, Response::HTTP_ACCEPTED);
    }
}
