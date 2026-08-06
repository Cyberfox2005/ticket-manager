<?php

namespace App\Modules\Shared\Infrastructure;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait TenantScoped
{
    public static function bootTenantScoped(): void
    {
        static::creating(function ($model) {
            if (! $model->organization_id) {
                $model->organization_id = static::resolveCurrentOrganizationId();
            }
        });

        static::addGlobalScope('organization', function (Builder $builder) {
            $orgId = static::resolveCurrentOrganizationId();
            if ($orgId) {
                $builder->where('organization_id', $orgId);
            }
        });
    }

    /**
     * Resolve the current organization ID from session (web) or the auth user (API).
     */
    protected static function resolveCurrentOrganizationId(): ?int
    {
        // Web context: organization stored in session
        if (session()->has('current_organization_id')) {
            return (int) session('current_organization_id');
        }

        // API context: derive from authenticated user's first organization
        $user = Auth::user();
        if ($user && method_exists($user, 'organizations')) {
            $org = $user->organizations()->first();
            return $org ? $org->id : null;
        }

        return null;
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }
}
