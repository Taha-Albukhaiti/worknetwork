<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Zeigt das Registrierungsformular.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Verarbeitet eine eingehende Registrierungsanfrage.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'registration_type' => ['required', 'in:user,company'], // Hinzugefügtes Validierungsfeld für den Registrierungstyp
        ]);

        $temp = '';
        // Rollen basierend auf dem Registrierungstyp zuweisen
        if ($request->registration_type === 'user') {
            $temp = 'user';
        } else {
            $temp = 'company';
        }

        // Benutzer erstellen
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $temp,
            'status' => 'active',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
