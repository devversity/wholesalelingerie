<ORDER_STATUSES>
    <!-- Ref: Website reference for order -->
    <!-- ID: KhaosControl order code -->
    <!-- INVOICE: KhaosControl invoice code -->
    <!-- Complete: -1 if this shipment/invoice completes the order, 0 if it is a partshipment -->
    <!-- Consignment: shipping reference (if any) -->
    <!-- URN: Company code of the customer for this order -->
    <!-- Tag contents: Order status -->
    <!-- Order statuses:

  cSI_ORDERS_RELEASED = 9;
  cSI_ORDERS_STAGING = 10;
	Both indicate order is taken & ready to be processed.

  cSI_ORDERS_AUTHORISEPAYMENT = 11;
	Awaiting credit card auth / cheque clearance

  cSI_ORDERS_PRINTPICKING = 12;
	Invoice is being picked

  cSI_ORDERS_PACKBOXES = 13;
	Invoice is being packed

  cSI_ORDERS_SHIP = 14;
	Invoice is being shipped / dispatched by courier

  cSI_ORDERS_PRINTINVOICES = 15;
	Invoice is being printed

  cSI_ORDERS_PROCESSING = 20;
	Invoice is being processed

  cSI_ORDERS_ISSUEINVOICES = 16;
	Invoice is complete & awaiting issue

  cSI_ORDERS_FUTURE_DATE = 17;
	Invoice is awaiting release date (eg. cheque clearance)

  cSI_ORDERS_FUTURE_STOCK = 18;
	Invoice is awaiting stock to arrive

  cSI_ORDERS_FUTURE_HOLD = 19;
	Invoice has been put on hold by an operator

  cSI_ORDERS_FUTURE_TERMS = 21;
  	Held due to exceeding financial terms etc.

  cSI_ISSUED_CURRENT = 70;
  cSI_ISSUED_ARCHIVEDQ1 = 71;
	Invoice has been issued and is completed.

         -->

    <ORDER REF="X12" ID="S2334" INVOICE="I3243" COMPLETE="-1" CONSIGNMENT="Z23214SHB" URN="ABCDE">10</ORDER>
    <ORDER REF="69" ID="S2098" INVOICE="" COMPLETE="0" CONSIGNMENT="" URN="121DEF">11</ORDER>
</ORDER_STATUSES>