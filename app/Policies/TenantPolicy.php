<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    /**
     * Determine if the user can update the tenant (family) settings.
     * Only the owner of the tenant can update it.
     */
    public function update(User $user, Tenant $tenant): bool
    {
        return $user->isOwner() && $user->tenant_id === $tenant->id;
    }

    /**
     * Determine if the user can view the tenant (family) settings.
     * Only the owner can view family settings.
     */
    public function view(User $user, Tenant $tenant): bool
    {
        return $user->isOwner() && $user->tenant_id === $tenant->id;
    }
}
