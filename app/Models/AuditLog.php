<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'auditable_type',
        'auditable_id',
        'code_reference',
        'donnees_anciennes',
        'donnees_nouvelles',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'donnees_anciennes' => 'array',
        'donnees_nouvelles' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper pour logger rapidement une action
     */
    public static function logAction(string $action, string $module, ?string $codeRef = null, ?array $old = null, ?array $new = null, $model = null)
    {
        $user = auth()->user();
        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user ? ($user->name . ' (' . $user->email . ')') : 'Système',
            'action' => $action,
            'module' => $module,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model?->id,
            'code_reference' => $codeRef,
            'donnees_anciennes' => $old,
            'donnees_nouvelles' => $new,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'CLI / Background',
            'created_at' => now(),
        ]);
    }
}
