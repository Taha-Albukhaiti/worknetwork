<?php

namespace App\Http\Controllers;

use App\Mail\ProfileRequestAccepted;
use App\Mail\ProfileRequestRejected;
use App\Models\Address;
use App\Models\CompanyProfile;
use App\Models\PortfolioMedia;
use App\Models\ProfileRequest;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\PortfolioDetail;
use Faker\Provider\Company;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function userDashboard(): View
    {
        $data = $this->getUserData();
        $portfolios = $this->getPortfolios();
        $address = $this->getAddress();
        return view('user.index', compact('data', 'portfolios', 'address'));
    }

    private function getPortfolios()
    {
        $id = Auth::user()->id;
        return Portfolio::where('user_id', $id)->with('details')->first();
    }

    public function userLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }


    public function userLogin(): View|Application|Factory
    {
        return view('user.user_login');
    }

    private function getUserData(): User
    {
        $id = Auth::user()->id;
        return User::find($id);
    }


    public function userProfile(): View|Application|Factory
    {
        $data = $this->getUserData();
        $address = $this->getAddress();
        return view('user.user_profile_view', compact('data', 'address'));
    }

    public function getAddress()
    {
        $id = Auth::user()->id;
        return Address::where('user_id', $id)->first();
    }

    public function userPortfolio(): View|Application|Factory
    {
        $data = $this->getUserData();
        $portfolios = $data->portfolios()->with('details')->get()->first();
        $address = $this->getAddress();
        return view('user.user_portfolio_edit', compact('data', 'portfolios', 'address'));
    }

    public function userProfileEdit(): View|Application|Factory
    {
        $data = $this->getUserData();
        $portfolios = Portfolio::where('user_id', $data->id)->with('details')->first();
        $address = $this->getAddress();
        return view('user.user_profile_edit_view', compact('data', 'portfolios', 'address'));
    }

    public function userPortfolioStore(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;

        DB::transaction(function () use ($id, $request) {
            $data = User::find($id);
            $data->username = $request->input('username');
            $data->phone = $request->input('phone');
            $data->email = $request->input('email');

            if ($request->file('photo')) {
                $file = $request->file('photo');
                @unlink(public_path('upload/user_images/' . $data->photo));
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/user_images'), $filename);
                $data->photo = $filename;
            }

            $data->save();
            $this->updateOrCreateAddress($id, $request);

            $portfolioData = [
                'job_title' => $request->input('portfolios.job_title'),
                'company' => $request->input('portfolios.company'),
                'start_date' => $request->input('portfolios.start_date'),
                'end_date' => $request->input('portfolios.end_date'),
            ];

            // Find existing portfolio or create a new one
            $portfolio = Portfolio::updateOrCreate(['user_id' => $id], $portfolioData);

            // Save or update portfolio details
            $details = $request->input('portfolios.details');
            foreach ($details as $detail) {
                if (isset($detail['id'])) {
                    // If detail has an ID, update it
                    $portfolioDetail = PortfolioDetail::find($detail['id']);
                    if ($portfolioDetail) {
                        $portfolioDetail->type = $detail['type'];
                        $portfolioDetail->content = $detail['content'];
                        $portfolioDetail->save();
                    }
                } else {
                    // If detail does not have an ID, create it
                    $portfolioDetail = new PortfolioDetail();
                    $portfolioDetail->portfolio_id = $portfolio->id;
                    $portfolioDetail->type = $detail['type'];
                    $portfolioDetail->content = $detail['content'];
                    $portfolioDetail->save();
                }
            }

            // Hochgeladene Bilder verarbeiten und speichern
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
            }

            // Hochgeladene Dateien verarbeiten und speichern
            if ($request->hasFile('files')) {
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
        });

        $notification = [
            'message' => 'Portfolio erfolgreich aktualisiert',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function userProfileStore(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        // Die Adresse Klasse ist mit user verknüpft und hat als Fremdschlüssel die user_id
        $this->updateOrCreateAddress($id, $request);


        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $filename);
            $data['photo'] = $filename;
        }

        $portfolio = Portfolio::with('details')->where('user_id', $id)->first();
        // Save or update portfolio details if they exist with out duplicating

        if ($portfolio) {
            $portfolio->job_title = $request->input('portfolios.job_title');
            $portfolio->company = $request->input('portfolios.company');
            $portfolio->start_date = $request->input('portfolios.start_date');
            $portfolio->end_date = $request->input('portfolios.end_date');
            $portfolio->save();

            $details = $request->input('portfolios.details');
            foreach ($details as $detail) {
                if (isset($detail['id'])) {
                    $portfolioDetail = PortfolioDetail::find($detail['id']);
                    if ($portfolioDetail) {
                        $portfolioDetail->type = $detail['type'];
                        $portfolioDetail->content = $detail['content'];
                        $portfolioDetail->save();
                    }
                } else {
                    $portfolioDetail = new PortfolioDetail();
                    $portfolioDetail->portfolio_id = $portfolio->id;
                    $portfolioDetail->type = $detail['type'];
                    $portfolioDetail->content = $detail['content'];
                    $portfolioDetail->save();
                }
            }
        } else {
            $portfolio = new Portfolio();
            $portfolio->user_id = $id;
            $portfolio->job_title = $request->input('portfolios.job_title');
            $portfolio->company = $request->input('portfolios.company');
            $portfolio->start_date = $request->input('portfolios.start_date');
            $portfolio->end_date = $request->input('portfolios.end_date');
        }

        $portfolio->save();
        $data->save();

        $notification = [
            'message' => 'Benutzerprofil erfolgreich aktualisiert',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function deletePortfolioDetail($id): \Illuminate\Http\JsonResponse
    {
        $portfolioDetail = PortfolioDetail::find($id);
        if ($portfolioDetail) {
            $portfolioDetail->delete();
            return response()->json(['message' => 'Portfolio Detail erfolgreich gelöscht'], 200);
        }
        return response()->json(['message' => 'Portfolio Detail nicht gefunden'], 404);

    }

    public function userChangePassword(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('user.user_change_password', compact('data'));
    }

    public function userUpdatePassword(Request $request): RedirectResponse
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
     * @param $id
     * @param Request $request
     * @return void
     */
    public function updateOrCreateAddress($id, Request $request): void
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
        $profileRequest->status = 'accepted';
        $profileRequest->save();

        // Mail::to($profileRequest->requestedUser->email)->send(mailable: new ProfileRequestAccepted($profileRequest));

        $notification = array(
            'message' => 'Profilanfrage akzeptiert',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function rejectProfileRequest($id)
    {
        $profileRequest = ProfileRequest::findOrFail($id);
        $profileRequest->status = 'rejected';
        $profileRequest->save();

        //Mail::to($profileRequest->requestedUser->email)->send(mailable: new ProfileRequestRejected($profileRequest));

        $notification = array(
            'message' => 'Profilanfrage abgelehnt',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function companyProfileView($id)
    {
        $data = User::find($id);
        $companyProfile = CompanyProfile::where('user_id', $id)->first();
        $address = $data->address;
        return \view('company_profile_show', compact('data', 'companyProfile', 'address'));
    }

}
