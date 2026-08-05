package com.ticketflow.customer.domain.model

import kotlinx.serialization.Serializable

@Serializable
data class UserTicket(
    val id: String,
    val eventTitle: String,
    val eventDate: String,
    val venue: String,
    val seat: String,
    val qrCodeUrl: String,
    val eventImageUrl: String
)
