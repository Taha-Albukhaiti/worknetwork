<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Zeigt das Company-Dashboard.
     *
     * @return View
     */
    public function companyDashboard(): View
    {
        return view('company.index');
    }

    /**
     * Meldet den Company aus.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function companyLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/company/login');
    }

    /**
     * Zeigt das Company-Anmeldeformular.
     *
     * @return View|\Illuminate\Contracts\Foundation\Application|Factory
     */
    public function companyLogin(): View|Application|Factory
    {
        return view('company.company_login');
    }

    /**
     * Zeigt das Company-Profil.
     *
     * @return View|Application|Factory
     */
    public function companyProfile(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('company.company_profile_view', compact('data'));
    }

    /**
     * Zeigt das company-Profilbearbeitungsformular.
     *
     * @return View|Application|Factory
     */
    public function companyProfileEdit(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('company.company_profile_edit_view', compact('data'));
    }

    /**
     * Speichert die Änderungen am company-Profil.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function companyProfileStore(Request $request): RedirectResponse
    {
        $data = User::find(Auth::user()->id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/company_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Company Profile erfolgreich aktualisiert',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Zeigt das Formular zum Ändern des Company-Passworts.
     *
     * @return View|Application|Factory
     */
    public function companyChangePassword(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('company.company_change_password', compact('data'));
    }

    /**
     * Aktualisiert das Company-Passwort.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function companyUpdatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {
            $notification = array(
                'message' => 'Das alte Passwort stimmt nicht überein',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Passwort erfolgreich geändert',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
}
