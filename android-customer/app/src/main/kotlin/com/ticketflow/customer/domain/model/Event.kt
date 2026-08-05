package com.ticketflow.customer.domain.model

import kotlinx.serialization.Serializable

@Serializable
data class Event(
    val id: String,
    val title: String,
    val date: String,
    val location: String,
    val price: String,
    val imageUrl: String,
    val category: String
)
