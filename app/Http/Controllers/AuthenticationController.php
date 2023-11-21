<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function appLogin()
    {
        return view('app_login');
    }
}
