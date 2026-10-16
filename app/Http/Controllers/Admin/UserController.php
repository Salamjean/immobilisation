<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Programme;
use App\Models\StructureBeneficiaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['structure', 'programme']);

        // Recherche textuelle (nom, prénoms, email, téléphone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtre par statut actif
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif == '1');
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = User::ROLES;
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();

        // Statistiques rapides
        $stats = [
            'total' => User::count(),
            'actifs' => User::where('actif', true)->count(),
            'inactifs' => User::where('actif', false)->count(),
            'admin' => User::where('role', User::ROLE_ADMIN)->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'structures', 'programmes', 'stats'));
    }

    public function create()
    {
        $roles = User::ROLES;
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();

        return view('admin.users.create', compact('roles', 'structures', 'programmes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenoms' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'role' => 'required|string|in:' . implode(',', array_keys(User::ROLES)),
            'structure_beneficiaire_id' => 'nullable|exists:structures_beneficiaires,id',
            'programme_id' => 'nullable|exists:programmes,id',
            'telephone' => 'nullable|string|max:50',
            'actif' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('avatars', 'public');
            $validated['photo'] = $path;
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['actif'] = $request->has('actif') ? (bool) $request->actif : true;

        $user = User::create($validated);

        AuditLog::logAction('CREATION_UTILISATEUR', 'SECURITE', auth()->user()?->email ?? 'admin', null, $user->toArray(), $user);

        return redirect()->route('admin.users.index')->with('success', "Le compte de [{$user->nom_complet}] a été créé avec succès.");
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = User::ROLES;
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();

        return view('admin.users.edit', compact('user', 'roles', 'structures', 'programmes'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenoms' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'role' => 'required|string|in:' . implode(',', array_keys(User::ROLES)),
            'structure_beneficiaire_id' => 'nullable|exists:structures_beneficiaires,id',
            'programme_id' => 'nullable|exists:programmes,id',
            'telephone' => 'nullable|string|max:50',
            'actif' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('avatars', 'public');
            $validated['photo'] = $path;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['actif'] = $request->has('actif') ? (bool) $request->actif : false;

        $old = $user->toArray();
        $user->update($validated);

        AuditLog::logAction('MODIFICATION_UTILISATEUR', 'SECURITE', auth()->user()?->email ?? 'admin', $old, $user->toArray(), $user);

        return redirect()->route('admin.users.index')->with('success', "Le compte de [{$user->name}] a été mis à jour.");
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Protection pour ne pas désactiver son propre compte admin connecté
        if ($user->id === auth()->id()) {
            return back()->with('error', "Vous ne pouvez pas désactiver votre propre compte administrateur.");
        }

        $user->actif = !$user->actif;
        $user->save();

        $action = $user->actif ? 'ACTIVATION_COMPTE' : 'DESACTIVATION_COMPTE';
        AuditLog::logAction($action, 'SECURITE', auth()->user()?->email ?? 'admin', null, ['id' => $user->id, 'actif' => $user->actif], $user);

        $statusMsg = $user->actif ? 'activé' : 'désactivé';
        return back()->with('success', "Le compte de [{$user->name}] a été {$statusMsg}.");
    }
}
