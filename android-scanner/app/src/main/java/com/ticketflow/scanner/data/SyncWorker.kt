package com.ticketflow.scanner.data

import android.content.Context
import androidx.hilt.work.HiltWorker
import androidx.work.CoroutineWorker
import androidx.work.WorkerParameters
import com.ticketflow.scanner.domain.SecurityRepository
import dagger.assisted.Assisted
import dagger.assisted.AssistedInject
import kotlinx.coroutines.flow.first

@HiltWorker
class SyncWorker @AssistedInject constructor(
    @Assisted context: Context,
    @Assisted params: WorkerParameters,
    private val ticketDao: TicketDao,
    private val securityRepository: SecurityRepository // Assuming this handles API too
) : CoroutineWorker(context, params) {

    override suspend fun doWork(): Result {
        val pendingTickets = ticketDao.getAllTickets().first().filter { it.status == "VALIDATED_OFFLINE" }

        if (pendingTickets.isEmpty()) return Result.success()

        return try {
            pendingTickets.forEach { ticket ->
                // Actual API call would go here
                // val success = api.syncTicket(ticket)
                // if (success) {
                    ticketDao.insertTicket(ticket.copy(status = "SYNCED"))
                // }
            }
            Result.success()
        } catch (e: Exception) {
            Result.retry()
        }
    }
}
