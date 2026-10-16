<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->filled('code_reference')) {
            $query->where('code_reference', 'like', "%{$request->code_reference}%");
        }

        $logs = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('gestionnaire.audit.index', compact('logs'));
    }
}
