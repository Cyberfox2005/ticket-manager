package com.ticketflow.scanner.di

import com.ticketflow.scanner.data.SecurityRepositoryImpl
import com.ticketflow.scanner.domain.SecurityRepository
import dagger.Binds
import dagger.Module
import dagger.hilt.InstallIn
import dagger.hilt.components.SingletonComponent
import javax.inject.Singleton

@Module
@InstallIn(SingletonComponent::class)
abstract class SecurityModule {

    @Binds
    @Singleton
    abstract fun bindSecurityRepository(
        securityRepositoryImpl: SecurityRepositoryImpl
    ): SecurityRepository
}
