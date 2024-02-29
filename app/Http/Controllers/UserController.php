<?php

namespace App\Http\Controllers;


use App\Models\Address;
use App\Models\CompanyProfile;
use App\Models\PortfolioMedia;
use App\Models\ProfileRequest;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\PortfolioDetail;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Class UserController - steuert die Benutzeraktionen.
 * @package App\Http\Controllers
 * @version 1.0
 * @since 1.0
 * @author Taha Al-Bukhaiti
 */
class UserController extends Controller
{
    /**
     * Zeigt das Dashboard des Benutzers an.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userDashboard(): View
    {
        $user = Auth::user();
        $portfolios = $this->getUserPortfolios($user);
        $address = $user->address;
        return view('user.index', compact('user', 'portfolios', 'address'));
    }

    /**
     * Meldet den Benutzer aus.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function userLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }

    /**
     * Zeigt das Benutzer-Anmeldeformular an.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userLogin(): View
    {
        return view('user.user_login');
    }

    /**
     * gibt die Portfolios des Benutzers zurück.
     *
     * @param User $user
     * @return mixed
     */
    private function getUserPortfolios(User $user): mixed
    {
        return Portfolio::where('user_id', $user->id)->with('details')->first();
    }

    /**
     * Zeigt das Benutzerprofil an.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userProfile(): View
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first();
        return view('user.user_profile_view', compact('user', 'address'));
    }

    /**
     * Zeigt das Benutzerportfolio an.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userPortfolio(): View
    {
        $user = Auth::user();
        $portfolios = $user->portfolios()->with('details')->first();
        $address = Address::where('user_id', $user->id)->first();
        return view('user.user_portfolio_edit', compact('user', 'portfolios', 'address'));
    }

    /**
     * Zeigt das Formular zum Bearbeiten des Benutzerprofils an.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userProfileEdit(): View
    {
        $user = Auth::user();
        $portfolios = Portfolio::where('user_id', $user->id)->with('details')->first();
        $address = Address::where('user_id', $user->id)->first();
        return view('user.user_profile_edit_view', compact('user', 'portfolios', 'address'));
    }

    /**
     * Speichert die Änderungen am Benutzerportfolio.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function userPortfolioStore(Request $request): RedirectResponse
    {
        $user = Auth::user();
        // Validierung der Eingabe hier einfügen

        DB::transaction(function () use ($user, $request) {
            // Benutzerprofil aktualisieren
            $user->update($request->only(['username', 'phone', 'email']));
            // Foto verarbeiten
            if ($request->hasFile('photo')) {
                $user->photo = $this->uploadPhoto($request->file('photo'));
            }
            $user->save();
            // Adresse aktualisieren oder erstellen
            $user->address()->updateOrCreate([], $request->input('address'));

            // Portfolio verarbeiten
            $portfolioData = $request->input('portfolios');

            // Portfolio Details verarbeiten
            $this->updateOrCreatePortfolioDetails($user, $portfolioData, $portfolioData['details'] ?? []);

            // Hochgeladene Bilder und Dateien verarbeiten
            $this->processMediaUploads($portfolioData, $request);
        });

        return redirect()->back()->with(['message' => 'Portfolio erfolgreich aktualisiert', 'alert-type' => 'success']);
    }

    /**
     * Speichert die Änderungen am Benutzerprofil.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function userProfileStore(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->update($request->only(['username', 'name', 'email', 'phone']));
        // Foto verarbeiten
        if ($request->hasFile('photo')) {
            $user->photo = $this->uploadPhoto($request->file('photo'));
        }
        $user->save();

        return redirect()->back()->with(['message' => 'Benutzerprofil erfolgreich aktualisiert', 'alert-type' => 'success']);
    }

    /**
     * lädt das hochgeladene Bild in den Ordner hoch.
     *
     * @param $file
     * @return string
     */
    private function uploadPhoto($file): string
    {
        $filename = date('YmdHi') . '_' . $file->getClientOriginalName();
        $file->move(public_path('upload/user_images'), $filename);
        return $filename;
    }

    /**
     * Verarbeitet die hochgeladenen Bilder und Dateien.
     *
     * @param $portfolio
     * @param $request
     */
    private function processMediaUploads($portfolio, $request): void
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = date('YmdHi') . '_' . $image->getClientOriginalName();
                $image->move(public_path('upload/portfolio_images'), $filename);
                $portfolioMedia = new PortfolioMedia();
                $portfolioMedia->portfolio_id = $portfolio['id'];
                $portfolioMedia->type = 'image';
                $portfolioMedia->filename = $filename;
                $portfolioMedia->save();

            }
        } elseif ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = date('YmdHi') . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload/portfolio_files'), $filename);
                $portfolioMedia = new PortfolioMedia();
                $portfolioMedia->portfolio_id = $portfolio['id'];
                $portfolioMedia->type = 'file';
                $portfolioMedia->filename = $filename;
                $portfolioMedia->save();
            }
        }


    }


    /**
     * aktualisiert oder erstellt das Portfolio-Detail des Benutzers.
     *
     * @param $portfolio
     * @param $details
     * @return void
     */
    private function updateOrCreatePortfolioDetails($user, $portfolio, $details): void
    {
        unset($portfolio['details']);
        $portfolio = Portfolio::updateOrCreate(
            $portfolio,
            ['user_id' => $user->id]
        );

        foreach ($details as $detail) {
            if (isset($detail['id'])) {
                PortfolioDetail::find($detail['id'])->update($detail);
            } else {
                $portfolio->details()->create($detail);
            }
        }
    }

    /**
     * Löscht ein Portfolio-Detail.
     *
     * @param $id
     * @return
     */
    public function deletePortfolioDetail($id)
    {
        PortfolioDetail::find($id)->delete();
        return redirect()->back()->with(['message' => 'Portfolio erfolgreich aktualisiert', 'alert-type' => 'success']);
    }


    /**
     * Zeigt das Formular zum Ändern des Benutzerpassworts an.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userChangePassword(): View
    {
        $user = Auth::user();
        return view('user.user_change_password', compact('user'));
    }

    /**
     * Aktualisiert das Benutzerpasswort.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function userUpdatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->with(['message' => 'Das alte Passwort stimmt nicht überein', 'alert-type' => 'error']);
        }

        auth()->user()->update(['password' => Hash::make($request->new_password)]);

        return back()->with(['message' => 'Passwort erfolgreich geändert', 'alert-type' => 'success']);
    }

    /**
     * Zeigt die Profilanfragen des Benutzers an.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showProfileRequests(): View
    {
        $user = Auth::user();
        $profileRequests = $user->profileRequests()->with('requestedUser.companyProfile', 'requestedUser.address')->get();
        return view('user.profile_requests', compact('profileRequests'));
    }

    /**
     * Akzeptiert eine Profilanfrage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function acceptProfileRequest($id): RedirectResponse
    {
        $profileRequest = ProfileRequest::findOrFail($id);
        $profileRequest->update(['status' => 'accepted']);
        // Mail::to($profileRequest->requestedUser->email)->send(mailable: new ProfileRequestAccepted($profileRequest));
        return redirect()->back()->with(['message' => 'Profilanfrage akzeptiert', 'alert-type' => 'success']);
    }

    /**
     * Lehnt eine Profilanfrage ab.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function rejectProfileRequest($id): RedirectResponse
    {
        $profileRequest = ProfileRequest::findOrFail($id);
        $profileRequest->update(['status' => 'rejected']);
        // Mail::to($profileRequest->requestedUser->email)->send(mailable: new ProfileRequestRejected($profileRequest));
        return redirect()->back()->with(['message' => 'Profilanfrage abgelehnt', 'alert-type' => 'success']);
    }

    /**
     * Zeigt das Profil eines Unternehmens an.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function companyProfileView($id): View
    {
        $user = User::findOrFail($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        $address = $user->address;
        return view('company_profile_show', compact('user', 'companyProfile', 'address'));
    }
}
