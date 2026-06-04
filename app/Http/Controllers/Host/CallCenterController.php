<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallCenterController extends Controller
{
    public function index()
    {
        return view('host.call-center.index');
    }
}
