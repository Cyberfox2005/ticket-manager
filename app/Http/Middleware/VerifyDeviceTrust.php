<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class VerifyDeviceTrust
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $deviceId = $request->header('X-Device-Id');

        if (!$user || !$deviceId) {
            return $next($request);
        }

        // Check if device is trusted for this user
        $isTrusted = DB::table('trusted_devices')
            ->where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->exists();

        if (!$isTrusted) {
            return response()->json([
                'message' => 'Device not recognized. Please complete 2FA on this device.',
                'code' => 'DEVICE_NOT_TRUSTED'
            ], 403);
        }

        return $next($request);
    }
}
