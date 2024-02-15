<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\PortfolioDetail;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function userDashboard(): View
    {
        return view('user.index');
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

    public function userProfile(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('user.user_profile_view', compact('data'));
    }

    public function userPortfolio(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $portfolios = $data->portfolios()->with('details')->get();

        return view('user.user_portfolio_view', compact('data', 'portfolios'));
    }


    public function userProfileEdit(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        // die Portfolio-Daten des Users und die Portfolio-Details werden geladen
        $portfolios = Portfolio::where('user_id', $id)->with('details')->first();

        return view('user.user_profile_edit_view', compact('data', 'portfolios'));
    }

    public function userPortfolioStore(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;

        DB::transaction(function () use ($id, $request) {
            $portfolio = Portfolio::firstOrNew(['user_id' => $id]);
            $portfolio->user_id = $id;
            $portfolio->job_title = $request->input('portfolios.job_title');
            $portfolio->company = $request->input('portfolios.company');
            $portfolio->start_date = $request->input('portfolios.start_date');
            $portfolio->end_date = $request->input('portfolios.end_date');
            $portfolio->save();

            if ($portfolio->id) {
                $details = $request->input('portfolios.details');

                foreach ($details as $detail) {
                    $portfolioDetail = new PortfolioDetail();
                    $portfolioDetail->portfolio_id = $portfolio->id;
                    $portfolioDetail->type = $detail['type'];
                    $portfolioDetail->content = $detail['content'];
                    $portfolioDetail->save();
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

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $filename);
            $data['photo'] = $filename;
        }

        $portfolio = Portfolio::with('details')->where('user_id', $id)->first();

        if ($request->has('portfolios')) {
            $portfolio->job_title = $request->portfolios['job_title'];
            $portfolio->company = $request->portfolios['company'];
            $portfolio->start_date = $request->portfolios['start_date'];
            $portfolio->end_date = $request->portfolios['end_date'];
            $portfolio->details()->delete();

            if (isset($request->portfolios['details']) && is_array($request->portfolios['details'])) {
                foreach ($request->portfolios['details'] as $detail) {
                    if (isset($detail['type']) && isset($detail['content'])) {
                        $portfolioDetail = new PortfolioDetail();
                        $portfolioDetail->portfolio_id = $portfolio->id;
                        $portfolioDetail->type = $detail['type'];
                        $portfolioDetail->content = $detail['content'];
                        $portfolioDetail->save();
                    } else {
                        Log::info("--------------- Kein Type und Content ---------------");
                    }
                }
            }
        }

        $portfolio->save();
        $data->save();

        $notification = [
            'message' => 'Benutzerprofil erfolgreich aktualisiert',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
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
}
