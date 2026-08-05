package com.ticketflow.scanner.domain

interface SecurityRepository {
    /**
     * Verifies a signed payload using a public key.
     * Expected format: data|signature (Base64 encoded)
     */
    fun verifySignature(payload: String): Boolean
}
