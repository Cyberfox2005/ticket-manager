package com.ticketflow.customer.domain.usecase

import com.ticketflow.customer.domain.model.Event
import com.ticketflow.customer.domain.repository.EventRepository
import javax.inject.Inject

class GetEventsUseCase @Inject constructor(
    private val eventRepository: EventRepository
) {
    suspend operator fun invoke(): List<Event> {
        return eventRepository.getEvents()
    }
}
