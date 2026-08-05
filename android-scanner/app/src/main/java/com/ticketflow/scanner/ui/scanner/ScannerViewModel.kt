package com.ticketflow.scanner.ui.scanner

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.ticketflow.scanner.domain.ValidateTicketUseCase
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class ScannerViewModel @Inject constructor(
    private val validateTicketUseCase: ValidateTicketUseCase
) : ViewModel() {

    private val _uiState = MutableStateFlow<ScannerUiState>(ScannerUiState.Idle)
    val uiState: StateFlow<ScannerUiState> = _uiState

    fun scanBarcode(payload: String) {
        if (_uiState.value is ScannerUiState.Validating) return

        viewModelScope.launch {
            _uiState.value = ScannerUiState.Validating(payload)
            val result = validateTicketUseCase.execute(payload)
            _uiState.value = when (result) {
                ValidateTicketUseCase.ValidationResult.ValidOffline -> ScannerUiState.Success(payload, isOffline = true)
                ValidateTicketUseCase.ValidationResult.ValidOnline -> ScannerUiState.Success(payload, isOffline = false)
                ValidateTicketUseCase.ValidationResult.Invalid -> ScannerUiState.Error("Invalid Ticket")
                ValidateTicketUseCase.ValidationResult.NetworkError -> ScannerUiState.Error("Network Error")
            }
        }
    }

    fun resetStatus() {
        _uiState.value = ScannerUiState.Idle
    }
}

sealed class ScannerUiState {
    object Idle : ScannerUiState()
    data class Validating(val ticketId: String) : ScannerUiState()
    data class Success(val ticketId: String, val isOffline: Boolean) : ScannerUiState()
    data class Error(val message: String) : ScannerUiState()
}
