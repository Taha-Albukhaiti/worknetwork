<?php

namespace App\Http\Controllers;


use App\Models\Address;
use App\Models\CompanyProfile;
use App\Models\PortfolioMedia;
use App\Models\ProfileRequest;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\PortfolioDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function userDashboard(): View
    {
        $user = Auth::user();
        $portfolios = $this->getUserPortfolios($user);
        $address = $user->address;
        return view('user.index', compact('user', 'portfolios', 'address'));
    }

    private function getUserPortfolios(User $user)
    {
        return Portfolio::where('user_id', $user->id)->with('details')->first();
    }

    public function userLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }

    public function userLogin(): View
    {
        return view('user.user_login');
    }

    public function userProfile(): View
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first();
        return view('user.user_profile_view', compact('user', 'address'));
    }

    public function userPortfolio(): View
    {
        $user = Auth::user();
        $portfolios = $user->portfolios()->with('details')->first();
        $address = Address::where('user_id', $user->id)->first();
        return view('user.user_portfolio_edit', compact('user', 'portfolios', 'address'));
    }

    public function userProfileEdit(): View
    {
        $user = Auth::user();
        $portfolios = Portfolio::where('user_id', $user->id)->with('details')->first();
        $address = Address::where('user_id', $user->id)->first();
        return view('user.user_profile_edit_view', compact('user', 'portfolios', 'address'));
    }

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
            $portfolio = $user->portfolios()->first();
            if (!$portfolio) {
                $portfolio = new Portfolio();
                $portfolio->user_id = $user->id;
                $portfolio->save();
            }

            // Portfolio Details verarbeiten
            $this->updateOrCreatePortfolioDetails($portfolio, $portfolioData['details'] ?? []);

            // Hochgeladene Bilder und Dateien verarbeiten
            $this->processMediaUploads($portfolio, $request);
        });

        return redirect()->back()->with(['message' => 'Portfolio erfolgreich aktualisiert', 'alert-type' => 'success']);
    }


    private function uploadPhoto($file)
    {
        $filename = date('YmdHi') . '_' . $file->getClientOriginalName();
        $file->move(public_path('upload/user_images'), $filename);
        return $filename;
    }

    private function updateOrCreatePortfolioDetails($portfolio, $details): void
    {
        foreach ($details as $detail) {
            $detailData = isset($detail['id']) ? ['type' => $detail['type'], 'content' => $detail['content']] : $detail;
            $portfolio->details()->updateOrCreate(['id' => $detail['id'] ?? null], $detailData);#
            $portfolio->save();
        }
    }

    private function processMediaUploads($portfolio, $request): void
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = date('YmdHi') . '_' . $image->getClientOriginalName();
                $image->move(public_path('upload/portfolio_images'), $filename);
                $portfolioMedia = new PortfolioMedia();
                $portfolioMedia->portfolio_id = $portfolio->id;
                $portfolioMedia->type = 'image';
                $portfolioMedia->filename = $filename;
                $portfolioMedia->save();
            }
        } elseif ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = date('YmdHi') . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload/portfolio_files'), $filename);
                $portfolioMedia = new PortfolioMedia();
                $portfolioMedia->portfolio_id = $portfolio->id;
                $portfolioMedia->type = 'file';
                $portfolioMedia->filename = $filename;
                $portfolioMedia->save();
            }
        }


    }

    public function userProfileStore(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->update($request->only(['username', 'name', 'email', 'phone']));
        // Foto verarbeiten
        if ($request->hasFile('photo')) {
            $user->photo = $this->uploadPhoto($request->file('photo'));
        }
        $user->save();
        // Adresse aktualisieren oder erstellen
        $user->address()->updateOrCreate([], $request->input('address'))->save();
        // Portfolio verarbeiten
        $portfolio = $this->updateOrCreatePortfolio($user, $request->input('portfolios'));
        // Portfolio Details verarbeiten
        $this->updateOrCreatePortfolioDetails($portfolio, $request->input('portfolios.details'));

        return redirect()->back()->with(['message' => 'Benutzerprofil erfolgreich aktualisiert', 'alert-type' => 'success']);
    }


    private function updateOrCreatePortfolio($user, $portfolioData)
    {
        unset($portfolioData['details']);
        return $user->portfolios()->updateOrCreate([], $portfolioData);
    }

    public function deletePortfolioDetail($id): \Illuminate\Http\JsonResponse
    {
        PortfolioDetail::findOrFail($id)->delete();
        return response()->json(['message' => 'Portfolio Detail erfolgreich gelöscht'], 200);
    }

    public function userChangePassword(): View
    {
        $user = Auth::user();
        return view('user.user_change_password', compact('user'));
    }

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

    public function showProfileRequests()
    {
        $user = Auth::user();
        $profileRequests = $user->profileRequests()->with('requestedUser.companyProfile', 'requestedUser.address')->get();
        return view('user.profile_requests', compact('profileRequests'));
    }

    public function acceptProfileRequest($id)
    {
        $profileRequest = ProfileRequest::findOrFail($id);
        $profileRequest->update(['status' => 'accepted']);
        // Mail::to($profileRequest->requestedUser->email)->send(mailable: new ProfileRequestAccepted($profileRequest));
        return redirect()->back()->with(['message' => 'Profilanfrage akzeptiert', 'alert-type' => 'success']);
    }

    public function rejectProfileRequest($id)
    {
        $profileRequest = ProfileRequest::findOrFail($id);
        $profileRequest->update(['status' => 'rejected']);
        // Mail::to($profileRequest->requestedUser->email)->send(mailable: new ProfileRequestRejected($profileRequest));
        return redirect()->back()->with(['message' => 'Profilanfrage abgelehnt', 'alert-type' => 'success']);
    }

    public function companyProfileView($id)
    {
        $user = User::findOrFail($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        $address = $user->address;
        return view('company_profile_show', compact('user', 'companyProfile', 'address'));
    }
}
