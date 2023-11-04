<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Zeigt das Admin-Dashboard.
     *
     * @return View
     */
    public function adminDashboard(): View
    {
        return view('admin.index');
    }

    /**
     * Meldet den Admin aus.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    /**
     * Zeigt das Admin-Anmeldeformular.
     *
     * @return View|Application|Factory
     */
    public function adminLogin(): View|Application|Factory
    {
        return view('admin.admin_login');
    }

    /**
     * Zeigt das Admin-Profil.
     *
     * @return View|Application|Factory
     */
    public function adminProfile(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('admin.admin_profile_view', compact('data'));
    }

    /**
     * Zeigt das Admin-Profilbearbeitungsformular.
     *
     * @return View|Application|Factory
     */
    public function adminProfileEdit(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('admin.admin_profile_edit_view', compact('data'));
    }

    /**
     * Speichert die Änderungen am Admin-Profil.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function adminProfileStore(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile erfolgreich aktualisiert',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Zeigt das Formular zum Ändern des Admin-Passworts.
     *
     * @return View|Application|Factory
     */
    public function adminChangePassword(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('admin.admin_change_password', compact('data'));
    }

    /**
     * Aktualisiert das Admin-Passwort.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function adminUpdatePassword(Request $request): RedirectResponse
    {
        $validateData = $request->validate([
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
