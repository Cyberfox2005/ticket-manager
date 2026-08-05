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
        object NetworkError : ValidationResult()
    }

    suspend fun execute(payload: String): ValidationResult = withContext(Dispatchers.IO) {
        // 1. Offline Crypto-Validation
        val isOfflineValid = securityRepository.verifySignature(payload)

        if (isOfflineValid) {
            // Save to local DB for later sync
            val ticketId = payload.split("|").firstOrNull() ?: payload
            ticketDao.insertTicket(
                Ticket(
                    id = ticketId,
                    eventName = "Summer Festival 2026",
                    scannedAt = System.currentTimeMillis(),
                    status = "OFFLINE_VALIDATED"
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
