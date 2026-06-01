<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index()
    {
        return view('workflow.sla');
    }
}
