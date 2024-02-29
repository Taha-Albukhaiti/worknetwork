<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CompanyProfile;
use App\Models\Portfolio;
use App\Models\ProfileRequest;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class CompanyController - steuert die Anzeige des Dashboards und die Bearbeitung des Unternehmensprofils.
 * @package App\Http\Controllers
 * @version 1.0
 * @since 1.0
 * @autor Taha Al-Bukhaiti
 */
class CompanyController extends Controller
{
    /**
     * Zeigt das Dashboard des Unternehmens an
     *
     * @return View|Application|Factory
     */
    public function companyDashboard(): View|Application|Factory
    {
        $data = User::find(Auth::user()->id);
        $address = $this->getAddress();
        $companyProfile = CompanyProfile::where('user_id', Auth::user()->id)->first();
        return view('company.index', compact('data', 'address', 'companyProfile'));
    }

    /**
     * Meldet das Unternehmen aus.
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
     * Zeigt das Anmeldeformular für das Unternehmen an.
     *
     * @return View|Application|Factory
     */
    public function companyLogin(): View|Application|Factory
    {
        return view('company.company_login');
    }

    /**
     * Zeigt das Formular zum Ändern des Unternehmenspassworts an.
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
     * Aktualisiert das Unternehmenspasswort.
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

    /**
     * Zeigt das Unternehmensprofil an.
     *
     * @return View|Application|Factory
     */
    public function companyProfile(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $address = $this->getAddress();
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        return view('company.company_profile_view', compact('data', 'address', 'companyProfile'));
    }

    /**
     * Zeigt das Formular zum Bearbeiten des Unternehmensprofils an.
     *
     * @return View|Application|Factory
     */
    public function companyProfileEdit(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        $address = $this->getAddress();
        return view('company.company_profile_edit_view', compact('data', 'address', 'companyProfile'));
    }

    /**
     * Speichert die Änderungen am Unternehmensprofil.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function companyProfileStore(Request $request): RedirectResponse
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
            $file->move(public_path('upload/company_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $this->updateOrCreateAddress($id, $request);
        $this->updateOrCreateCompanyProfile($id, $request);

        $notification = array(
            'message' => 'Company Profile erfolgreich aktualisiert',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Gibt die Adresse des eingeloggten Unternehmens zurück.
     *
     * @return Address
     */
    public function getAddress(): Address
    {

        $id = Auth::user()->id;
        $address = Address::where('user_id', $id)->first();
        // behandelt den Fall, dass das Unternehmen keine Adresse hat
        if (!$address) {
            $address = new Address();
        }
        return $address;
    }

    /**
     * Aktualisiert oder erstellt die Adresse des Unternehmens.
     *
     * @param int $id
     * @param Request $request
     * @return Address
     */
    public function updateOrCreateAddress($id, Request $request): Address
    {
        $addressData = [
            'street' => $request->input('address.street'),
            'street_number' => $request->input('address.street_number'),
            'city' => $request->input('address.city'),
            'state' => $request->input('address.state'),
            'zip' => $request->input('address.zip')
        ];

        $address = Address::updateOrCreate(['user_id' => $id], $addressData);
        $address->save();
        return $address;
    }

    /**
     * Aktualisiert oder erstellt das Unternehmensprofil.
     *
     * @param int $id
     * @param Request $request
     * @return void
     */
    private function updateOrCreateCompanyProfile($id, Request $request): void
    {
        $companyProfileData = [
            'company_website' => $request->input('companyProfile.company_website'),
            'company_description' => $request->input('companyProfile.company_description')
        ];

        $companyProfile = CompanyProfile::updateOrCreate(['user_id' => $id], $companyProfileData);
        $companyProfile->save();
    }

    /**
     * Zeigt die akzeptierten Benutzerprofile an.
     *
     * @return View|Application|Factory
     */
    public function acceptedUsers(): Factory|Application|View
    {
        $profileRequests = ProfileRequest::where('requested_user_id', Auth::user()->id)->where('status', 'accepted')->get();
        $users = User::whereIn('id', $profileRequests->pluck('user_id'))->get();
        return view('company.accepted_users', compact('users'));
    }

    /**
     * Zeigt das Profil eines akzeptierten Benutzers an.
     *
     * @param int $id
     * @return View|Application|Factory
     */
    public function acceptedUserProfileView($id): Factory|Application|View
    {
        $data = User::find($id);
        $portfolios = Portfolio::where('user_id', $id)->first();
        $address = $data->address;
        return view('company.accepted_user_profile_view', compact('data', 'address', 'portfolios'));
    }

}
