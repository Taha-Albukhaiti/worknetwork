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

class CompanyController extends Controller
{
    public function companyDashboard(): View
    {
        $data = User::find(Auth::user()->id);
        $address = $this->getAddress();
        $companyProfile = CompanyProfile::where('user_id', Auth::user()->id)->first();
        return view('company.index', compact('data', 'address', 'companyProfile'));
    }

    public function companyLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/company/login');
    }

    public function companyLogin(): View|Application|Factory
    {
        return view('company.company_login');
    }

    public function companyChangePassword(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('company.company_change_password', compact('data'));
    }

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


    public function companyProfile(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $address = $this->getAddress();
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        return view('company.company_profile_view', compact('data', 'address', 'companyProfile'));
    }

    public function companyProfileEdit(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        $address = $this->getAddress();
        return view('company.company_profile_edit_view', compact('data', 'address', 'companyProfile'));
    }

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

    public function getAddress()
    {
        $id = Auth::user()->id;
        return Address::where('user_id', $id)->first();
    }

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

    private function updateOrCreateCompanyProfile($id, Request $request): void
    {
        $companyProfileData = [
            'company_website' => $request->input('companyProfile.company_website'),
            'company_description' => $request->input('companyProfile.company_description')
        ];

        $companyProfile = CompanyProfile::updateOrCreate(['user_id' => $id], $companyProfileData);
        $companyProfile->save();
    }

    public function acceptedUsers()
    {
        $profileRequests = ProfileRequest::where('requested_user_id', Auth::user()->id)->where('status', 'accepted')->get();
        $users = User::whereIn('id', $profileRequests->pluck('user_id'))->get();
        return view('company.accepted_users', compact('users'));
    }

    public function acceptedUserProfileView($id)
    {
        $data = User::find($id);
        $portfolios = Portfolio::where('user_id', $id)->first();
        $address = $data->address;
        return view('company.accepted_user_profile_view', compact('data', 'address', 'portfolios'));
    }

}
