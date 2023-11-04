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

class UserController extends Controller
{
    /**
     * Zeigt das Admin-Dashboard.
     *
     * @return View
     */
    public function userDashboard(): View
    {
        return view('user.index');
    }

    /**
     * Meldet den User aus.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function userLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/user/login');
    }

    /**
     * Zeigt das User-Anmeldeformular.
     *
     * @return View|\Illuminate\Contracts\Foundation\Application|Factory
     */
    public function userLogin(): View|Application|Factory
    {
        return view('user.user_login');
    }

    /**
     * Zeigt das User-Profil.
     *
     * @return View|Application|Factory
     */
    public function userProfile(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('user.user_profile_view', compact('data'));
    }

    /**
     * Zeigt das User-Profilbearbeitungsformular.
     *
     * @return View|Application|Factory
     */
    public function userProfileEdit(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('user.user_profile_edit_view', compact('data'));
    }

    /**
     * Speichert die Änderungen am User-Profil.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function userProfileStore(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'User Profile erfolgreich aktualisiert',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Zeigt das Formular zum Ändern des User-Passworts.
     *
     * @return View|Application|Factory
     */
    public function userChangePassword(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('user.user_change_password', compact('data'));
    }

    /**
     * Aktualisiert das User-Passwort.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function userUpdatePassword(Request $request): RedirectResponse
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
