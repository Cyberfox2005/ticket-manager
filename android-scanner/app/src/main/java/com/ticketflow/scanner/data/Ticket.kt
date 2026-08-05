package com.ticketflow.scanner.data

import androidx.room.Entity
import androidx.room.PrimaryKey
import kotlinx.serialization.Serializable

@Entity(tableName = "tickets")
@Serializable
data class Ticket(
    @PrimaryKey val id: String,
    val eventName: String,
    val scannedAt: Long,
    val status: String,
    val syncStatus: SyncStatus = SyncStatus.PENDING
)

enum class SyncStatus {
    PENDING, SYNCED, FAILED
}
