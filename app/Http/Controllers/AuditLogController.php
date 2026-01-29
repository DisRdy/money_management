<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuditLogController extends Controller
{
    /**
     * Display audit logs with filtering (Owner only)
     */
    public function index(Request $request)
    {
        // Authorization check
        Gate::authorize('view-audit-logs');

        $user = Auth::user();

        // Base query with tenant isolation
        $query = AuditLog::forTenant($user->tenant_id)
            ->with(['user', 'targetUser'])
            ->latest('created_at');

        // Filter by action
        if ($request->filled('action') && in_array($request->action, ['created', 'updated', 'deleted'])) {
            $query->where('action', $request->action);
        }

        // Filter by actor (user who performed the action)
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by target user (owner of the record)
        if ($request->filled('target_user_id')) {
            $query->where('target_user_id', $request->target_user_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Paginate results
        $logs = $query->paginate(25)->withQueryString();

        // Get family members for filter dropdowns
        $users = User::where('tenant_id', $user->tenant_id)
            ->orderBy('name')
            ->get();

        return view('audit-logs.index', compact('logs', 'users'));
    }

    /**
     * Show details of a single audit log entry
     */
    public function show(AuditLog $auditLog)
    {
        Gate::authorize('view-audit-logs');

        $user = Auth::user();

        // Tenant isolation check
        if ($auditLog->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        $auditLog->load(['user', 'targetUser']);

        return view('audit-logs.show', compact('auditLog'));
    }
}
