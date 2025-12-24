<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('livreur.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('livreur')->attempt($credentials, $remember)) {
            // Vérifier la validation du compte
            $livreur = Auth::guard('livreur')->user();
            if (! $livreur->valide) {
                Auth::guard('livreur')->logout();
                return back()->withErrors([
                    'email' => "Votre compte n'est pas encore validé. Veuillez vérifier votre e-mail et cliquer sur 'Oui' pour activer votre compte.",
                ])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('livreur.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Identifiants invalides.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('livreur')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('livreur.login');
    }
}
