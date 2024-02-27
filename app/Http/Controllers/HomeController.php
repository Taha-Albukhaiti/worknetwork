<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Portfolio;
use App\Models\User;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{


    public function index()
    {
        $users = User::where('role', 'user')->get();
        $companiesProfile = User::where('role', 'company')->get();
        return view('welcome', compact('users', 'companiesProfile'));
    }

    /**
     * Zeige die User, die dem Suchbegriff entsprechen nach Jobtitel. Jobtitel wird in der Tabelle "portfolios" gespeichert.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function searchUser(Request $request)
    {
        $search = $request->get('search');
        $usersQuery = User::where('role', 'user');

        if (!empty($search)) {
            $usersQuery->whereHas('portfolios', function ($query) use ($search) {
                $query->where('job_title', 'like', '%' . $search . '%');
            });
        }

        $users = $usersQuery->get();
        $companiesProfile = User::where('role', 'company')->get();

        return view('welcome', compact('users', 'companiesProfile'));
    }

    /**
     * Zeige die Unternehmen, die dem Suchbegriff entsprechen nach Firmenname. Firmenname wird in der Tabelle "company_profiles" gespeichert.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function searchCompany(Request $request)
    {
        $search = $request->get('search');
        // get the company profiles where the company name (the User Name) is like the search term
        $companiesProfile= User::where('role', 'company')->where('name', 'like', '%' . $search . '%')->get();
        $users = User::where('role', 'user')->get();

        return view('welcome', compact('users', 'companiesProfile'));
    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userProfileRequest($id)
    {
        $user = User::find($id);
        $requestedUser = User::find(auth()->id());

        if ($user && $requestedUser) {
            $profileRequest = $user->profileRequests()->where('requested_user_id', $requestedUser->id)->first();

            if (!$profileRequest) {
                $user->profileRequests()->create([
                    'user_id' => $user->id,
                    'requested_user_id' => $requestedUser->id,
                    'status' => 'pending'
                ]);
            }
        }

        $notification = array(
            'message' => 'Profile request sent',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function companyProfileView($id)
    {
        $data = User::find($id);
        $address = $this->getAddress($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        return view('company_profile_show', compact('data', 'address', 'companyProfile'));
    }

    public function getAddress($id)
    {
        $address = User::find($id)->address;
        return $address;
    }

}
