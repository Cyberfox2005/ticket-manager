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
    val status: String
)
