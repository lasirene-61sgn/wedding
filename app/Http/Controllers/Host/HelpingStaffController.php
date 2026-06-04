<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpingStaffController extends Controller
{
    public function index()
    {
        return view('host.helping-staff.index');
    }
}
