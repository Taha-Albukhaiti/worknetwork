<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.index');
    }
    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
    public function adminLogin(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('admin.admin_login');
    }
    public function adminProfile(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $id = Auth::user()->id;
        $findUser = User::find($id);
        return view('admin.admin_profile_view', compact('findUser'));
    }
}
