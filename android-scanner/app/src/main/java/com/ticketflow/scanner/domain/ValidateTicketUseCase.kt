package com.ticketflow.scanner.domain

import com.ticketflow.scanner.data.Ticket
import com.ticketflow.scanner.data.TicketDao
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import javax.inject.Inject

class ValidateTicketUseCase @Inject constructor(
    private val securityRepository: SecurityRepository,
    private val ticketDao: TicketDao
) {
    sealed class ValidationResult {
        object ValidOffline : ValidationResult()
        object ValidOnline : ValidationResult()
        object Invalid : ValidationResult()
        object Duplicate : ValidationResult()
        object Expired : ValidationResult()
        object NetworkError : ValidationResult()
    }

    suspend fun execute(payload: String): ValidationResult = withContext(Dispatchers.IO) {
        // 1. Duplicate Detection (Fast check in Room)
        val alreadyScanned = ticketDao.getTicketById(payload) != null
        if (alreadyScanned) {
            return@withContext ValidationResult.Duplicate
        }

        // 2. Offline Crypto-Validation (Enterprise Engine)
        val isOfflineValid = securityRepository.verifySignature(payload)

        if (isOfflineValid) {
            // Enterprise logic: Validate Payload constraints (EventID, Timestamp)
            // format: eventId|ticketId|timestamp|signature
            val parts = payload.split("|")
            if (parts.size >= 3) {
                val timestamp = parts[2].toLongOrNull() ?: 0
                val currentTime = System.currentTimeMillis()

                // Anti-fraud: Dynamic QR rotation check (e.g., 5 min window for offline)
                if (currentTime - timestamp > 300_000) {
                    return@withContext ValidationResult.Expired
                }
            }

            // Save to local DB for sync
            ticketDao.insertTicket(
                Ticket(
                    id = payload,
                    eventName = "Secure Event",
                    scannedAt = System.currentTimeMillis(),
                    status = "VALIDATED_OFFLINE"
                )
            )
            return@withContext ValidationResult.ValidOffline
        }

        // 2. Online Sync (Placeholder for actual API call)
        // If not offline valid, we might still check if it's a valid ID in the database/server
        // for cases where signature isn't present or we have a local cache of valid IDs.
        // For this task, we'll simulate an online check if offline fails or as a fallback.

        return@withContext try {
            // Simulate network call
            val isOnlineValid = simulateOnlineValidation(payload)
            if (isOnlineValid) {
                ticketDao.insertTicket(
                    Ticket(
                        id = payload,
                        eventName = "Summer Festival 2026",
                        scannedAt = System.currentTimeMillis(),
                        status = "ONLINE_VALIDATED"
                    )
                )
                ValidationResult.ValidOnline
            } else {
                ValidationResult.Invalid
            }
        } catch (e: Exception) {
            ValidationResult.NetworkError
        }
    }

    private suspend fun simulateOnlineValidation(payload: String): Boolean {
        kotlinx.coroutines.delay(1000)
        return !payload.contains("invalid")
    }
}
