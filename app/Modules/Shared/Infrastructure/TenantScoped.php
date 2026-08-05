<?php

namespace App\Modules\Shared\Infrastructure;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait TenantScoped
{
    public static function bootTenantScoped()
    {
        static::creating(function ($model) {
            if (!$model->organization_id && session()->has('current_organization_id')) {
                $model->organization_id = session('current_organization_id');
            }
        });

        static::addGlobalScope('organization', function (Builder $builder) {
            if (session()->has('current_organization_id')) {
                $builder->where('organization_id', session('current_organization_id'));
            }
        });
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }
}
