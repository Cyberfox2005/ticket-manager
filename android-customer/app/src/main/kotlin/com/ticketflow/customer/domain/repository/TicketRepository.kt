package com.ticketflow.customer.domain.repository

import com.ticketflow.customer.domain.model.UserTicket

interface TicketRepository {
    suspend fun getMyTickets(): List<UserTicket>
}
