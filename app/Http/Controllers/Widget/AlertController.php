<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /***
     * @param string $token
     * @return \Inertia\Response
     */
    public function index(string $token)
    {
        $user = User::whereAlertToken($token)->firstOrFail();
        return \Inertia\Inertia::render('Alert',
            [
                'user' => $user->toArray(),
                'token' => $token
            ]);
    }
}
