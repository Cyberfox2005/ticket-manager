package com.ticketflow.customer.ui.tickets

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import coil.compose.AsyncImage

data class UserTicket(
    val id: String,
    val eventTitle: String,
    val eventDate: String,
    val venue: String,
    val seat: String,
    val qrCodeUrl: String, // Mock QR code generator URL
    val eventImageUrl: String
)

val myTickets = listOf(
    UserTicket("T-123", "Summer Music Festival", "Aug 15, 2026", "Central Park, NY", "Section A, Row 5", "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=T-123-VALID", "https://images.unsplash.com/photo-1459749411177-042180ceea72?q=80&w=1000"),
    UserTicket("T-456", "Tech Conference 2026", "Sep 10, 2026", "Convention Center, SF", "General Admission", "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=T-456-VALID", "https://images.unsplash.com/photo-1540575861501-7ad0582371f3?q=80&w=1000")
)

@Composable
fun TicketsScreen() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(MaterialTheme.colorScheme.background)
            .padding(20.dp)
    ) {
        Text(
            text = "My Tickets",
            style = MaterialTheme.typography.headlineLarge,
            fontWeight = FontWeight.Bold,
            modifier = Modifier.padding(bottom = 24.dp)
        )

        LazyColumn(verticalArrangement = Arrangement.spacedBy(20.dp)) {
            items(myTickets) { ticket ->
                TicketCard(ticket)
            }
        }
    }
}

@Composable
fun TicketCard(ticket: UserTicket) {
    Card(
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(24.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f))
    ) {
        Column {
            Box(modifier = Modifier.height(140.dp).fillMaxWidth()) {
                AsyncImage(
                    model = ticket.eventImageUrl,
                    contentDescription = null,
                    contentScale = ContentScale.Crop,
                    modifier = Modifier.fillMaxSize()
                )
                Surface(
                    modifier = Modifier.padding(12.dp).align(Alignment.TopEnd),
                    color = Color.Black.copy(alpha = 0.6f),
                    shape = RoundedCornerShape(8.dp)
                ) {
                    Text(
                        ticket.id,
                        color = Color.White,
                        modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp),
                        style = MaterialTheme.typography.labelSmall
                    )
                }
            }

            Column(modifier = Modifier.padding(20.dp)) {
                Text(ticket.eventTitle, style = MaterialTheme.typography.titleLarge, fontWeight = FontWeight.Bold)
                Text("${ticket.eventDate} • ${ticket.venue}", style = MaterialTheme.typography.bodyMedium, color = MaterialTheme.colorScheme.onSurfaceVariant)
                Text(ticket.seat, style = MaterialTheme.typography.bodySmall, fontWeight = FontWeight.Bold, color = MaterialTheme.colorScheme.primary)

                Spacer(modifier = Modifier.height(24.dp))

                // QR Code Section
                Box(
                    modifier = Modifier
                        .fillMaxWidth()
                        .clip(RoundedCornerShape(16.dp))
                        .background(Color.White)
                        .padding(20.dp),
                    contentAlignment = Alignment.Center
                ) {
                    AsyncImage(
                        model = ticket.qrCodeUrl,
                        contentDescription = "Ticket QR Code",
                        modifier = Modifier.size(200.dp)
                    )
                }

                Spacer(modifier = Modifier.height(12.dp))
                Text(
                    "Show this QR code at the entrance",
                    modifier = Modifier.fillMaxWidth(),
                    style = MaterialTheme.typography.bodySmall,
                    color = MaterialTheme.colorScheme.onSurfaceVariant,
                    textAlign = androidx.compose.ui.text.style.TextAlign.Center
                )
            }
        }
    }
}
