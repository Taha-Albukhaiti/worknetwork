<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class HomeController - steuert die Anzeige der Startseite und die Suche nach Benutzern und Unternehmen.
 * @package App\Http\Controllers
 * @version 1.0
 * @since 1.0
 * @author Taha Al-Bukhaiti
 */
class HomeController extends Controller
{
    /**
     * Zeigt die Startseite mit einer Liste von Benutzern und Unternehmensprofilen.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function index(): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $users = User::where('role', 'user')->get();
        $companiesProfile = User::where('role', 'company')->get();
        return view('welcome', compact('users', 'companiesProfile'));
    }

    /**
     * Zeigt die Benutzer an, die dem Suchbegriff nach Jobtitel entsprechen.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function searchUser(Request $request): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $search = $request->get('searchUser');
        $usersQuery = User::where('role', 'user');


        if (!empty($search)) {
            $usersQuery->whereHas('portfolios', function ($query) use ($search) {
                $query->where('job_title', 'like', '%' . $search . '%');
                $query->orWhere('company', 'like', '%' . $search . '%');

            });

            $usersQuery->orWhere('name', 'like', '%' . $search . '%');
            $usersQuery->orWhere('username', 'like', '%' . $search . '%');
            $usersQuery->orWhere('email', 'like', '%' . $search . '%');
            $usersQuery->orWhere('phone', 'like', '%' . $search . '%');
            $address = Address::all();
            $address = $address->where('city', 'like', '%' . $search . '%');
            $usersQuery->orWhereIn('address_id', $address->pluck('id'));

        }

        $users = $usersQuery->get();
        $companiesProfile = User::where('role', 'company')->get();

        return view('welcome', compact('users', 'companiesProfile'));
    }

    /**
     * Zeigt die Unternehmen an, die dem Suchbegriff nach Firmenname entsprechen.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function searchCompany(Request $request): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $search = $request->get('searchCompany');
        $companiesProfile = User::where('role', 'company')->where('name', 'like', '%' . $search . '%')->get();
        $users = User::where('role', 'user')->get();
        return view('welcome', compact('users', 'companiesProfile'));
    }

    /**
     * Sendet eine Profilanforderung an einen Benutzer.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function userProfileRequest($id): RedirectResponse
    {
        $user = $this->findUser($id);
        $requestedUser = $this->findUser(auth()->id());

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

    /**
     * Zeigt das Profil eines Unternehmens an.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function companyProfileView($id): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = $this->findUser($id);
        $address = $this->getAddress($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        return view('company_profile_show', compact('user', 'address', 'companyProfile'));
    }

    /**
     * Gibt die Adresse eines Benutzers zurück.
     *
     * @param $id
     * @return mixed
     */
    public function getAddress($id): mixed
    {
        $address = $this->findUser($id)->address;
        return $address;
    }

    /**
     * Gibt einen Benutzer zurück.
     *
     * @param $id
     * @return mixed
     */
    public function findUser($id): mixed
    {
        return User::find($id);
    }
}
