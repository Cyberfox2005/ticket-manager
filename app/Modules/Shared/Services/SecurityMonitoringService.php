<?php

namespace App\Modules\Shared\Services;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class SecurityMonitoringService
{
    public function checkIpRestriction(): bool
    {
        $ip = Request::ip();

        $restriction = DB::table('ip_restrictions')
            ->where('ip_address', $ip)
            ->first();

        if ($restriction && $restriction->type === 'block') {
            return false;
        }

        // Logic for "Allow-only" mode could go here
        return true;
    }

    public function reportAnomaly(string $type, array $metadata): void
    {
        DB::table('security_events')->insert([
            'type' => $type,
            'metadata' => json_encode($metadata),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Trigger alert via Notification Engine (Phase 5)
    }
}
