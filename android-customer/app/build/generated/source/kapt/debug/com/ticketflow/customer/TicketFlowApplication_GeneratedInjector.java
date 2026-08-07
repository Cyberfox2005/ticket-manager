package com.ticketflow.customer;

import dagger.hilt.InstallIn;
import dagger.hilt.codegen.OriginatingElement;
import dagger.hilt.components.SingletonComponent;
import dagger.hilt.internal.GeneratedEntryPoint;

@OriginatingElement(
    topLevelClass = TicketFlowApplication.class
)
@GeneratedEntryPoint
@InstallIn(SingletonComponent.class)
public interface TicketFlowApplication_GeneratedInjector {
  void injectTicketFlowApplication(TicketFlowApplication ticketFlowApplication);
}
