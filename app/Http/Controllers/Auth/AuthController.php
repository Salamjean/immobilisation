<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        $demoUsers = User::where('actif', true)->orderBy('role')->get();
        return view('auth.login', compact('demoUsers'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            AuditLog::logAction('CONNEXION_UTILISATEUR', 'SECURITE', $user->email);
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function switchUser($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user);
        AuditLog::logAction('CONNEXION_DEMO_SWITCH', 'SECURITE', $user->email);
        return $this->redirectBasedOnRole($user)->with('success', "Connecté avec le profil : [{$user->name}] - ({$user->role_libelle})");
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::logAction('DECONNEXION_UTILISATEUR', 'SECURITE', Auth::user()->email);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    public function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_GESTIONNAIRE => redirect()->route('gestionnaire.dashboard'),
            User::ROLE_RESPONSABLE_LOGISTIQUE => redirect()->route('logistique.dashboard'),
            User::ROLE_AGENT_INVENTAIRE => redirect()->route('agent.mobile.index'),
            User::ROLE_AUDITEUR, User::ROLE_CONTROLEUR, User::ROLE_CONSULTANT => redirect()->route('auditeur.dashboard'),
            User::ROLE_UTILISATEUR_PROGRAMME => redirect()->route('programme.dashboard'),
            User::ROLE_LECTEUR => redirect()->route('gestionnaire.dashboard'),
            default => redirect()->route('gestionnaire.dashboard'),
        };
    }
}
