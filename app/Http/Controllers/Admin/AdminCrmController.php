<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCrmController extends Controller
{
    public function index()
    {
        $crms = \App\Models\Crm::latest()->get();
        return view('admin.crm.index', compact('crms'));
    }
}
