package com.ticketflow.scanner.data

import androidx.room.Database
import androidx.room.RoomDatabase

@Database(entities = [Ticket::class], version = 1)
abstract class AppDatabase : RoomDatabase() {
    abstract fun ticketDao(): TicketDao
}
