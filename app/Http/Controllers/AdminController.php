<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Portfolio;
use App\Models\PortfolioDetail;
use App\Models\PortfolioMedia;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class AdminController - steuert die Anzeige des Admin-Dashboards und die Verwaltung von Benutzern und Unternehmen.
 * @package App\Http\Controllers
 * @version 1.0
 * @since 1.0
 * @autor Taha Al-Bukhaiti
 */
class AdminController extends Controller
{
    /**
     * Anzahl der Benutzer pro Seite.
     *
     * @var int
     */
    public static $usersPerPage = 3;

    /**
     * Zeigt das Admin-Dashboard und liefert die Informationen über die Anzahl der Benutzer, Unternehmen und Profile und ihre Daten.
     *
     * @return View
     */
    public function adminDashboard(): View
    {
        $users = User::where('role', 'user')->latest()->take(self::$usersPerPage)->get();
        $companies = User::where('role', 'company')->latest()->take(self::$usersPerPage)->get();
        return view('admin.index', compact('users', 'companies'));
    }

    /**
     * Durchsucht Benutzer nach dem angegebenen Suchbegriff.
     *
     * @param Request $request
     * @return View|Application|Factory
     */
    public function adminSearchUser(Request $request): View|Application|Factory
    {
        $search = $request->get('searchUser');
        $usersQuery = User::where('role', 'user');

        if (!empty($search)) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        $users = $usersQuery->latest()->take(self::$usersPerPage)->get();
        $companies = User::where('role', 'company')->latest()->take(self::$usersPerPage)->get();
        return view('admin.index', compact('users', 'companies'));
    }

    /**
     * Durchsucht Unternehmen nach dem angegebenen Suchbegriff.
     *
     * @param Request $request
     * @return View|Application|Factory
     */
    public function adminSearchCompany(Request $request): View|Application|Factory
    {
        $search = $request->get('searchCompany');
        $companiesQuery = User::where('role', 'company');

        if (!empty($search)) {
            $companiesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        $companies = $companiesQuery->latest()->take(4)->get();
        $users = User::where('role', 'user')->latest()->take(self::$usersPerPage)->get();
        return view('admin.index', compact('users', 'companies'));
    }

    /**
     * Lädt weitere Benutzer für das Admin-Dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMoreUsers(Request $request): JsonResponse
    {
        $skip = $request->skip;
        // bei take() wird die Anzahl der Benutzer pro Seite festgelegt, falls es nur noch weniger im datenbank gibt als die Anzahl der Benutzer pro Seite, dann werden die restlichen Benutzer geladen
        $users = User::where('role', 'user')->latest()->skip($skip)->take(self::$usersPerPage)->get();
        return response()->json(['users' => $users]);
    }

    /**
     * Lädt weitere Unternehmen für das Admin-Dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMoreCompanies(Request $request): JsonResponse
    {
        $skip = $request->skip;
        $companies = User::where('role', 'company')->latest()->skip($skip)->take(self::$usersPerPage)->get();
        return response()->json(['companies' => $companies]);
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
        $data = $this->getUser($this->getAuthUserId());
        return view('admin.admin_profile_view', compact('data'));
    }

    /**
     * Zeigt das Admin-Profilbearbeitungsformular.
     *
     * @return View|Application|Factory
     */
    public function adminProfileEdit(): View|Application|Factory
    {
        $data = $this->getUser($this->getAuthUserId());
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
        $data = $this->getUser($this->getAuthUserId());
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
        $data = User::find($this->getAuthUserId());
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

    /**
     * Zeigt die Liste der Unternehmen und ihre CompanyProfile.
     *
     * @return View|Application|Factory
     */
    public function adminCompanies(): View|Application|Factory
    {
        $companies = User::where('role', 'company')->get();
        $company_profiles = CompanyProfile::all();
        return view('admin.companies', compact('companies', 'company_profiles'));
    }

    /**
     * Zeigt das Profil eines Benutzers.
     *
     * @param $id
     * @return View|Application|Factory
     */
    public function adminUserShow($id): View|Application|Factory
    {
        $data = User::find($id);
        $portfolios = Portfolio::where('user_id', $id)->first();
        $address = $data->address;
        return view('admin.user.user_show', compact('data', 'portfolios', 'address'));
    }

    /**
     * Zeigt das Profil eines Unternehmens.
     *
     * @param $id
     * @return View|Application|Factory
     */
    public function adminCompanyShow($id): View|Application|Factory
    {
        $data = $this->getUser($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        $address = $data->address;
        return view('admin.company.company_show', compact('data', 'companyProfile', 'address'));
    }

    /**
     * Löscht einen Benutzer.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function adminUserDelete($id): RedirectResponse
    {
        $user = $this->getUser($id);
        $userAddress = $user->address;
        $userPortfolio = Portfolio::where('user_id', $id)->first();

        if ($userPortfolio) {
            $userPortfolioDetails = PortfolioDetail::where('portfolio_id', $userPortfolio->id)->get();
            $userPortfolioMedia = PortfolioMedia::where('portfolio_id', $userPortfolio->id)->get();

            if ($userPortfolioMedia) {
                foreach ($userPortfolioMedia as $userPortfolioMedium) {
                    @unlink(public_path('upload/portfolio_media/' . $userPortfolioMedium->image));
                    $userPortfolioMedium->delete();
                }
            }

            if ($userPortfolioDetails) {
                foreach ($userPortfolioDetails as $userPortfolioDetail) {
                    @unlink(public_path('upload/portfolio_details/' . $userPortfolioDetail->image));
                    $userPortfolioDetail->delete();
                }
            }

            @unlink(public_path('upload/portfolio_images/' . $userPortfolio->image));
            $userPortfolio->delete();
        }

        if ($userAddress) {
            $userAddress->delete();
        }

        $user->delete();

        $notification = [
            'message' => 'Benutzer erfolgreich gelöscht',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }


    /**
     * Löscht ein Unternehmen.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function adminCompanyDelete($id): RedirectResponse
    {
        $user = $this->getUser($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        $companyAddress = $user->address;
        if ($companyProfile) {
            @unlink(public_path('upload/company_images/' . $companyProfile->image));
            $companyProfile->delete();
        }
        if ($companyAddress) {
            $companyAddress->delete();
        }
        $user->delete();
        $notification = array(
            'message' => 'Unternehmen erfolgreich gelöscht',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Gibt den Benutzer zurück.
     *
     * @param $id
     * @return mixed
     */
    public function getUser($id): mixed
    {
        return User::find($id);
    }

    /**
     * Gibt die ID des eingeloggten Benutzers zurück.
     *
     * @return mixed
     */
    public function getAuthUserId()
    {
        return Auth::user()->id;
    }
}
