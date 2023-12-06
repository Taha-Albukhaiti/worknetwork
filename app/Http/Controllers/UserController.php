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

        return redirect()->route('user.login');
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

    public function userPortfolio(): View|Application|Factory
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $portfolios = $data->portfolios()->with('details')->get();

        return view('user.user_portfolio_view', compact('data', 'portfolios'));
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
        $portfolios = $data->portfolios()->with('details')->get();

        return view('user.user_profile_edit_view', compact('data', 'portfolios'));
    }

    public function userPortfolioStore(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;
        Log::info('Received Portfolio Data:', [$request->input('portfolios')]);
        Log::info('Request received:', ['data' => $request->all()]);

        DB::transaction(function () use ($id, $request) {
            $portfolio = Portfolio::with('details')->firstOrNew(['user_id' => $id]);

            $portfolio->user_id = $id;
            $portfolio->job_title = $request->input('portfolios.job_title');
            $portfolio->company = $request->input('portfolios.company');
            $portfolio->start_date = $request->input('portfolios.start_date');
            $portfolio->end_date = $request->input('portfolios.end_date');

            $portfolio->save();

            $details = $request->input('portfolios.details');

            for ($i = 0; $i < count($details); $i += 2) {
                $type = $details[$i]['type'] ?? null;
                $content = $details[$i + 1]['content'] ?? null;

                Log::info('Type: ' . $type);
                Log::info('Content: ' . $content);

                if (!is_null($type) && !is_null($content)) {
                    $existingDetail = $portfolio->details()->where('type', $type)->first();

                    if ($existingDetail) {
                        Log::info('Detail vorhanden - Aktualisiere den Inhalt');
                        $existingDetail->content = $content;
                        $existingDetail->save();
                    } else {
                        Log::info('Detail nicht vorhanden - Erstelle ein neues Detail');
                        $portfolio->details()->create([
                            'portfolio_id' => $portfolio->id,
                            'type' => $type,
                            'content' => $content,
                        ]);
                    }
                } else {
                    Log::warning('Type und Content sind beide NULL');
                }
            }
        });

        Log::info('Portfolio und Details erfolgreich gespeichert.');

        $notification = [
            'message' => 'Portfolio erfolgreich aktualisiert',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
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

        $portfolio = Portfolio::with('details')->where('user_id', $id)->first();

        if ($request->has('portfolios')) {
            foreach ($request->portfolios as $portfolioData) {
                $portfolio = $data->portfolios()->find($portfolioData['id']);

                if ($portfolio) {
                    $portfolio->job_title = $portfolioData['job_title'];
                    $portfolio->company = $portfolioData['company'];
                    $portfolio->start_date = $portfolioData['start_date'];
                    $portfolio->end_date = $portfolioData['end_date'];
                    $portfolio->save();
                    // Überprüfen, ob 'details' im Portfolio vorhanden ist

                    if (isset($portfolioData['details']) && is_array($portfolioData['details'])) {
                        Log::info('Details vorhanden im Portfolio');
                        foreach ($portfolioData['details'] as $detailData) {
                            // Überprüfen, ob 'type' und 'content' in $detailData vorhanden sind
                            if (isset($detailData['type']) && isset($detailData['content'])) {
                                $type = $detailData['type'];
                                $content = $detailData['content'];

                                // Finde ein vorhandenes Detail nach Typ
                                $existingDetail = $portfolio->details()->where('type', $type)->first();

                                Log::info('Detailtyp: ' . $type);
                                Log::info('Detailinhalt: ' . $content);

                                if ($existingDetail) {
                                    Log::info('Detail vorhanden - Aktualisiere den Inhalt');
                                    $existingDetail->content = $content;
                                    $existingDetail->save();
                                } else {
                                    Log::info('Detail nicht vorhanden - Erstelle ein neues Detail');
                                    $portfolio->details()->create([
                                        'portfolio_id' => $portfolio->id,
                                        'type' => $type,
                                        'content' => $content
                                    ]);
                                }
                            } else {
                                Log::warning('Details unvollständig - type und content müssen gesetzt sein');
                            }
                        }
                    } else {
                        Log::warning('Details nicht gefunden im Portfolio');
                    }
                }
            }
        }

        if ($request->has('portfolios')) {
            $portfolio->save();
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
