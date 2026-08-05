package com.ticketflow.customer.ui.tickets

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.ticketflow.customer.domain.model.UserTicket
import com.ticketflow.customer.domain.usecase.GetMyTicketsUseCase
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class TicketsViewModel @Inject constructor(
    private val getMyTicketsUseCase: GetMyTicketsUseCase
) : ViewModel() {

    private val _tickets = MutableStateFlow<List<UserTicket>>(emptyList())
    val tickets: StateFlow<List<UserTicket>> = _tickets

    init {
        loadTickets()
    }

    private fun loadTickets() {
        viewModelScope.launch {
            try {
                _tickets.value = getMyTicketsUseCase()
            } catch (e: Exception) {
                // Handle error
            }
        }
    }
}
