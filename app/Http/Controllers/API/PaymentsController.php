<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|integer',
            'currency' => 'required|string',
            'nickname' => 'required|string',
            'message' => 'string:null',
            'user_id' => 'exists:users,id',
        ]);

        $user = User::FindOrFail($request['user_id']);

        $user->payments()->create([
            'amount' => $request->amount,
            'email' => $request->email,
            'currency' => $request->currency,
            'nickname' => $request->nickname,
            'message' => $request->message
        ]);
    }
}
