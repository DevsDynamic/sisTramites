<?php

namespace App\Http\Controllers\Tenant\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index()
    {
        return view('tenant.workflow.sla');
    }
}
