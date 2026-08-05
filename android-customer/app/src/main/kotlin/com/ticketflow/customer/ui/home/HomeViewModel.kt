package com.ticketflow.customer.ui.home

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.ticketflow.customer.domain.model.Event
import com.ticketflow.customer.domain.usecase.GetEventsUseCase
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class HomeViewModel @Inject constructor(
    private val getEventsUseCase: GetEventsUseCase
) : ViewModel() {

    private val _events = MutableStateFlow<List<Event>>(emptyList())
    val events: StateFlow<List<Event>> = _events

    init {
        loadEvents()
    }

    private fun loadEvents() {
        viewModelScope.launch {
            try {
                _events.value = getEventsUseCase()
            } catch (e: Exception) {
                // Handle error
            }
        }
    }
}
