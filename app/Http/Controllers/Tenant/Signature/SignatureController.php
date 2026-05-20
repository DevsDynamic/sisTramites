<?php

namespace App\Http\Controllers\Tenant\Signature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function index()
    {
        return view('tenant.signature.index');
    }
}
