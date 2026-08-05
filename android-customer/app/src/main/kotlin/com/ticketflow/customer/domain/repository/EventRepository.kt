package com.ticketflow.customer.domain.repository

import com.ticketflow.customer.domain.model.Event

interface EventRepository {
    suspend fun getEvents(): List<Event>
}
