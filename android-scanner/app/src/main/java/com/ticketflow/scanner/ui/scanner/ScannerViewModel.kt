package com.ticketflow.scanner.ui.scanner

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import androidx.work.Constraints
import androidx.work.NetworkType
import androidx.work.OneTimeWorkRequestBuilder
import androidx.work.WorkManager
import com.ticketflow.scanner.data.SyncWorker
import com.ticketflow.scanner.domain.ValidateTicketUseCase
import dagger.hilt.android.lifecycle.HiltViewModel
import android.app.Application
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class ScannerViewModel @Inject constructor(
    private val validateTicketUseCase: ValidateTicketUseCase,
    private val application: Application
) : ViewModel() {

    private val _uiState = MutableStateFlow<ScannerUiState>(ScannerUiState.Idle)
    val uiState: StateFlow<ScannerUiState> = _uiState

    fun scanBarcode(payload: String) {
        if (_uiState.value is ScannerUiState.Validating) return

        viewModelScope.launch {
            _uiState.value = ScannerUiState.Validating(payload)
            val result = validateTicketUseCase.execute(payload)
            _uiState.value = when (result) {
                ValidateTicketUseCase.ValidationResult.ValidOffline -> {
                    triggerSync()
                    ScannerUiState.Success(payload, isOffline = true)
                }
                ValidateTicketUseCase.ValidationResult.ValidOnline -> ScannerUiState.Success(payload, isOffline = false)
                ValidateTicketUseCase.ValidationResult.Duplicate -> ScannerUiState.Error("Already Validated")
                ValidateTicketUseCase.ValidationResult.Expired -> ScannerUiState.Error("QR Code Expired")
                ValidateTicketUseCase.ValidationResult.Invalid -> ScannerUiState.Error("Invalid Signature")
                ValidateTicketUseCase.ValidationResult.NetworkError -> ScannerUiState.Error("Network Error")
            }
        }
    }

    private fun triggerSync() {
        val constraints = Constraints.Builder()
            .setRequiredNetworkType(NetworkType.CONNECTED)
            .build()

        val syncRequest = OneTimeWorkRequestBuilder<SyncWorker>()
            .setConstraints(constraints)
            .build()

        WorkManager.getInstance(application).enqueue(syncRequest)
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
