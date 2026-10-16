<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Constantes des 9 Rôles du TDR UCP-FM
    public const ROLE_ADMIN = 'admin';
    public const ROLE_GESTIONNAIRE = 'gestionnaire';
    public const ROLE_RESPONSABLE_LOGISTIQUE = 'responsable_logistique';
    public const ROLE_UTILISATEUR_PROGRAMME = 'utilisateur_programme';
    public const ROLE_AGENT_INVENTAIRE = 'agent_inventaire';
    public const ROLE_CONTROLEUR = 'controleur';
    public const ROLE_AUDITEUR = 'auditeur';
    public const ROLE_CONSULTANT = 'consultant';
    public const ROLE_LECTEUR = 'lecteur';

    public const ROLES = [
        self::ROLE_ADMIN => 'Administrateur',
        self::ROLE_GESTIONNAIRE => 'Gestionnaire des immobilisations',
        self::ROLE_RESPONSABLE_LOGISTIQUE => 'Responsable logistique',
        self::ROLE_UTILISATEUR_PROGRAMME => 'Utilisateur programme',
        self::ROLE_AGENT_INVENTAIRE => 'Agent inventaire',
        self::ROLE_CONTROLEUR => 'Contrôleur',
        self::ROLE_AUDITEUR => 'Auditeur',
        self::ROLE_CONSULTANT => 'Consultant',
        self::ROLE_LECTEUR => 'Lecteur',
    ];

    protected $fillable = [
        'name',
        'prenoms',
        'email',
        'photo',
        'password',
        'role',
        'structure_beneficiaire_id',
        'programme_id',
        'telephone',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    public function structure()
    {
        return $this->belongsTo(StructureBeneficiaire::class, 'structure_beneficiaire_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function getNomCompletAttribute(): string
    {
        return trim(($this->name ?? '') . ' ' . ($this->prenoms ?? ''));
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }

    public function getRoleLibelleAttribute(): string
    {
        return self::ROLES[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role));
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'bg-rose-100 text-rose-800 border-rose-300',
            self::ROLE_GESTIONNAIRE => 'bg-brand-100 text-brand-900 border-brand-300',
            self::ROLE_RESPONSABLE_LOGISTIQUE => 'bg-amber-100 text-amber-900 border-amber-300',
            self::ROLE_UTILISATEUR_PROGRAMME => 'bg-indigo-100 text-indigo-900 border-indigo-300',
            self::ROLE_AGENT_INVENTAIRE => 'bg-purple-100 text-purple-900 border-purple-300',
            self::ROLE_CONTROLEUR => 'bg-cyan-100 text-cyan-900 border-cyan-300',
            self::ROLE_AUDITEUR => 'bg-emerald-100 text-emerald-900 border-emerald-300',
            self::ROLE_CONSULTANT => 'bg-slate-100 text-slate-800 border-slate-300',
            self::ROLE_LECTEUR => 'bg-gray-100 text-gray-700 border-gray-300',
            default => 'bg-slate-100 text-slate-700 border-slate-300',
        };
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isGestionnaire(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_GESTIONNAIRE, self::ROLE_RESPONSABLE_LOGISTIQUE]);
    }

    public function isAgentInventaire(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_GESTIONNAIRE, self::ROLE_AGENT_INVENTAIRE]);
    }

    public function isAuditeurOrControleur(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_AUDITEUR, self::ROLE_CONTROLEUR, self::ROLE_CONSULTANT]);
    }

    public function isReadOnly(): bool
    {
        return in_array($this->role, [self::ROLE_CONSULTANT, self::ROLE_LECTEUR]);
    }
}
