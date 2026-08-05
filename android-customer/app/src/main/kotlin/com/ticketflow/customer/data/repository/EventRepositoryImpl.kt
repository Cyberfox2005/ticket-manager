package com.ticketflow.customer.data.repository

import com.ticketflow.customer.domain.model.Event
import com.ticketflow.customer.domain.repository.EventRepository
import io.ktor.client.*
import io.ktor.client.call.*
import io.ktor.client.request.*
import javax.inject.Inject

class EventRepositoryImpl @Inject constructor(
    private val httpClient: HttpClient
) : EventRepository {
    override suspend fun getEvents(): List<Event> {
        // Example implementation with Ktor.
        // In a real scenario, you'd have a base URL.
        // return httpClient.get("https://api.ticketflow.com/events").body()

        // Mocking for now to match sample data
        return listOf(
            Event("1", "Summer Music Festival", "Aug 15, 2026", "Central Park, NY", "$45", "https://images.unsplash.com/photo-1459749411177-042180ceea72?q=80&w=1000", "Music"),
            Event("2", "Tech Conference 2026", "Sep 10, 2026", "Convention Center, SF", "$299", "https://images.unsplash.com/photo-1540575861501-7ad0582371f3?q=80&w=1000", "Tech"),
            Event("3", "Broadway: The Lion King", "Oct 05, 2026", "Minskoff Theatre, NY", "$85", "https://images.unsplash.com/photo-1503095396549-807039a3068e?q=80&w=1000", "Theater")
        )
    }
}
