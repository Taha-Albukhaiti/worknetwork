<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function companyDashboard()
    {
        return view('company.company_dashboard');
    }
}
