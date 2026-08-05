package com.ticketflow.customer.di

import com.ticketflow.customer.data.repository.EventRepositoryImpl
import com.ticketflow.customer.data.repository.TicketRepositoryImpl
import com.ticketflow.customer.domain.repository.EventRepository
import com.ticketflow.customer.domain.repository.TicketRepository
import dagger.Binds
import dagger.Module
import dagger.hilt.InstallIn
import dagger.hilt.components.SingletonComponent
import javax.inject.Singleton

@Module
@InstallIn(SingletonComponent::class)
abstract class RepositoryModule {

    @Binds
    @Singleton
    abstract fun bindEventRepository(
        eventRepositoryImpl: EventRepositoryImpl
    ): EventRepository

    @Binds
    @Singleton
    abstract fun bindTicketRepository(
        ticketRepositoryImpl: TicketRepositoryImpl
    ): TicketRepository
}
