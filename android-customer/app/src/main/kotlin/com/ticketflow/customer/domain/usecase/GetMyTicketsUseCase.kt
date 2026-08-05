package com.ticketflow.customer.domain.usecase

import com.ticketflow.customer.domain.model.UserTicket
import com.ticketflow.customer.domain.repository.TicketRepository
import javax.inject.Inject

class GetMyTicketsUseCase @Inject constructor(
    private val ticketRepository: TicketRepository
) {
    suspend operator fun invoke(): List<UserTicket> {
        return ticketRepository.getMyTickets()
    }
}
