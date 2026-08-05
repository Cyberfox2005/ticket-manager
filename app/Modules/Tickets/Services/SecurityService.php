<?php

namespace App\Modules\Tickets\Services;

class SecurityService
{
    /**
     * Enterprise Ticket Engine: Sign ticket payload for Offline Validation
     */
    public function signPayload(array $data): string
    {
        $payload = json_encode($data);
        $privateKey = config('app.ticket_private_key'); // Should be a valid RSA private key

        if (!$privateKey) {
            // Fallback to HMAC for development if RSA key isn't set
            return hash_hmac('sha256', $payload, config('app.key'));
        }

        openssl_sign($payload, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($payload . '.' . base64_encode($signature));
    }

    /**
     * Dynamic QR: Generate time-based token (TOTP-style)
     */
    public function generateDynamicToken(string $baseHash): string
    {
        $timeStep = 30; // 30 seconds rotation
        $counter = floor(time() / $timeStep);

        return hash_hmac('sha256', $baseHash . $counter, config('app.key'));
    }

    /**
     * Offline Validation Logic (to be used by Scanner App or Backend)
     */
    public function verifySignature(string $signedPayload): ?array
    {
        $parts = explode('.', $signedPayload);
        if (count($parts) !== 2) return null;

        $payload = $parts[0];
        $signature = base64_decode($parts[1]);
        $publicKey = config('app.ticket_public_key');

        if (!$publicKey) {
            // Dev Fallback check
            $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));
            return $expectedSignature === $parts[1] ? json_decode($payload, true) : null;
        }

        $result = openssl_verify($payload, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        return $result === 1 ? json_decode($payload, true) : null;
    }
}
