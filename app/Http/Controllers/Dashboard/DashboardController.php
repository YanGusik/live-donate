<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

    /***
     * @return \Inertia\Response
     */
    public function index() : \Inertia\Response
    {
        return Inertia::render('Dashboard');
    }
}
