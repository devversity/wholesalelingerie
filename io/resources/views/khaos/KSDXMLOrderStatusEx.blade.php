<STATUS_LIST>
	<!-- COMMENT: Order Status order identification:
		SORDER_CODE = Khaos Control Sales Order Code
		ASSOCIATED_REF = Website Order Number / Reference for the sales order Both will be returned, match on whichever is most appropriate.
	-->
	<ORDER_STATUS SORDER_CODE="SO1234" ASSOCIATED_REF="SO_WEBREF">
		<!-- COMMENT:  Company Name. Additional attributes for this tag include:
			COMPANY_CODE = Khaos Control Customer Code (URN)
			OTHER_REF = Website Code / Reference for customer
		-->
		<COMPANY_NAME COMPANY_CODE="CU1234" OTHER_REF="CU_WEBREF">Test company</COMPANY_NAME>
		<!-- COMMENT: Order types:
			1 = Sales Order
			2 = Credit Note
		-->
		<ORDER_TYPE TYPE_ID="S">Sales Order</ORDER_TYPE>
		<!-- COMMENT: Courier Name -->
		<COURIER>Royal Mail</COURIER>
		<!-- COMMENT: Currency Code: GBP = Sterling EUR = Euros USD = Dollars  All financial amounts for the Sales Order will be in the currency specified. -->
		<CURRENCY_CODE>GBP</CURRENCY_CODE>
		<!-- COMMENT: Date Sales Order created. Format - DD/MM/YYYY -->
		<DATE_CREATED>01/01/2009</DATE_CREATED>
		<!-- COMMENT: Date when Sales Order is due to be Delivered. Format - DD/MM/YYYY -->
		<DELIVERY_DATE>02/01/2009</DELIVERY_DATE>
		<!-- COMMENT: The ITEMS_TOTAL and DELIVERY_TOTAL tags should be combined to provide Total Gross Order Value. -->
		<!-- COMMENT: Total Value of items on the Sales Order. NET_VALUE attribute provides Net Items Total. ITEMS_TOTAL tag provides Gross Items Total (inc. VAT, where applicable) -->
		<ITEMS_TOTAL NET_VALUE="100.00">117.50</ITEMS_TOTAL>
		<!-- COMMENT: Total Delivery Amount for Sales Order. NET_VALUE attribute provides Net Delivery Total. ITEMS_TOTAL tag provides Gross Delivery Total (inc. VAT, where applicable) -->
		<DELIVERY_TOTAL NET_VALUE="10.00">10.16</DELIVERY_TOTAL>
		<!-- COMMENT: Displays whether or not all of the items on the order have been issued
			0 = Order Not Complete (i.e. item(s) still to be sent to customer (issued)
			-1 = Order Complete In Khaos Control when Quantity Assigned = Quantity Sold and all invoices for the order have been Issued, it will be marked as complete.
		-->
		<COMPLETE>0</COMPLETE>
		<!-- COMMENT: Number of items ordered. -->
		<QTY_SOLD/>
		<!-- COMMENT: Number of items assigned to the Sales Order in Khaos Control -->
		<QTY_ASSIGNED/>
		<!-- included only for cancelled order -->
		<CANCELLED>
			<CANCELLED_DATE_TIME>2011-06-06</CANCELLED_DATE_TIME>
			<REASON>why?</REASON>
			<USER_NAME>user</USER_NAME>
		</CANCELLED>
		<!-- COMMENT: List of Invoices associated with the Sales Order and their specific details. -->
		<INVOICES>
			<!-- COMMENT: Invoice Attributes returned include:
				INVOICE_CODE = Khaos Control Invoice Code
				INVOICE_DATE = Last Date Invoice was Printed / Edited. Format - DD/MM/YYYY
				SHIP_DATE = Date invoice was Shipped (using the Sales Invoice Manager Confirm Shipment function) in Khaos Control. Format - DD/MM/YYYY
				ISSUE_DATE = Date Invoice was Issued, if applicable. Format - DD/MM/YYYY
				INVOICE_STAGE_ID = Internal Khaos Control ID for current Invoice Manager stage
				INVOICE_STAGE_DESC = Currenct Invoice Manager stage Invoice Stages:
					ORDERS_RELEASED = 9;
					ORDERS_STAGING = 10; Both stages indicate that an order has been taken and is ready to be processed.
					ORDERS_AUTHORISEPAYMENT = 11; Awaiting credit card authorisation / cheque clearance
					PRINTPICKING = 12; Invoice is being picked
					ORDERS_PACKBOXES = 13; Invoice is being packed. Only used when an invoice is being despatched in multiple boxes that require individual consignment references.
					ORDERS_SHIP = 14; Invoice is being shipped / despatched by courier
					ORDERS_PRINTINVOICES = 15; Invoice is being printed
					ORDERS_ISSUEINVOICES = 16; Invoice is complete & awaiting issue
					ORDERS_FUTURE_DATE = 17; Delivery Date of the invoice is too far in future to be released for pick, pack and despatch yet
					ORDERS_FUTURE_STOCK = 18; Invoice is awaiting arrival of stock, before it can be fulfilled
					ORDERS_FUTURE_HOLD = 19; Invoice has been put on Manual Hold by a user
					ORDERS_PROCESSING = 20; Invoice is being processed. Only used when items on invoice are being personalised
					ORDERS_FUTURE_TERMS = 21; Invoice has been held due to customer's account exceeding financial terms
					ISSUED_CURRENT = 70;
					ISSUED_ARCHIVEDQ1 = 71; Both stages indicate that the Invoice has been issued and is complete
				COURIER = Courier Name for invoice. Potentially this could be different from the Courier for the Sales Order
				CONSIGNMENT_REF = Tracking reference for invoice from Courier, if applicable
				TRACKING_URL = Tracking URL for courier's website. Can be used with the Consignment Reference to provide tracking link on website and in website generated emails to customers. This is pulled from the URL set up against the Courier in the [ System Date | Couriers ] screen in Khaos Control
				QTY_SOLD = Number of items ordered
				QTY_SENT = Number of items despatched. Potentially could be less than QTY_SOLD
				MOVED = 0 / -1  0 = Invoice stage has not changed since last update -1 = Invoice stage has changed since last update. All new Sales Orders will default to -1
			-->
			<INVOICE INVOICE_CODE="INV1234" INVOICE_DATE="03/01/2009" SHIP_DATE="04/01/2009" ISSUE_DATE="05/01/2009" INVOICE_STAGE_ID="19" INVOICE_STAGE_DESC="MANUALHOLD" COURIER="Royal Mail" CONSIGNMENT_REF="ABC12345" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABC12345" QTY_SOLD="100.50" QTY_SENT="90.50" MOVED="-1">
				<!-- COMMENT: When an Invoice has been packed into a specific box(s) / package(s), the following attributes will be returned:
					SHIPPED = 0 / -1 0 = Not shipped -1 = Shipped (using the Sales Invoice Manager Confirm Shipment function)
					SHIP_DATE = Date invoice was Shipped (using the Sales Invoice Manager Confirm Shipment function) in Khaos Control. Format - DD/MM/YYYY
					COURIER = Courier Name for invoice. Potentially this could be different from the Courier for the Sales Order
					CONSIGNMENT_REF = Tracking reference for invoice from Courier, if applicable
					TRACKING_URL = Tracking URL for courier's website. Can be used with the Consignment Reference to provide tracking link on website and in website generated emails to customers. This is pulled from the URL set up against the Courier in the [ System Date | Couriers ] screen in Khaos Control
				-->
				<BOX SHIPPED="-1" SHIP_DATE="02/01/2009" COURIER="Royal Mail" CONSIGNMENT_REF="ABCD12234" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABCD12234">
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </BOX>
				<BOX SHIPPED="-1" SHIP_DATE="02/01/2009" COURIER="Royal Mail" CONSIGNMENT_REF="ABCD12234" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABCD12234">
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </BOX>
            <INVOICE_ITEMS>
               <INVOICE_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <INVOICE_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </INVOICE_ITEMS>
			</INVOICE>
			<INVOICE INVOICE_CODE="INV1234" INVOICE_DATE="03/01/2009" SHIP_DATE="04/01/2009" ISSUE_DATE="05/01/2009" INVOICE_STAGE_ID="19" INVOICE_STAGE_DESC="MANUALHOLD" COURIER="Royal Mail" CONSIGNMENT_REF="ABC12345" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABC12345" QTY_SOLD="100.50" QTY_SENT="90.50" MOVED="-1">         
				<BOX SHIPPED="-1" SHIP_DATE="02/01/2009" COURIER="Royal Mail" CONSIGNMENT_REF="ABCD12234" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABCD12234">
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>            
            </BOX>
				<BOX SHIPPED="-1" SHIP_DATE="02/01/2009" COURIER="Royal Mail" CONSIGNMENT_REF="ABCD12234" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABCD12234">
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>            
            </BOX>
            <INVOICE_ITEMS>
               <INVOICE_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <INVOICE_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </INVOICE_ITEMS>
			</INVOICE>
		</INVOICES>
	</ORDER_STATUS>
	<ORDER_STATUS SORDER_CODE="SO1234" ASSOCIATED_REF="SO_WEBREF">
		<COMPANY_NAME COMPANY_CODE="CU1234" OTHER_REF="CU_WEBREF">Test company</COMPANY_NAME>
		<ORDER_TYPE TYPE_ID="S">Sales Order</ORDER_TYPE>
		<COURIER>Royal Mail</COURIER>
		<CURRENCY_CODE>GBP</CURRENCY_CODE>
		<DATE_CREATED>01/01/2009</DATE_CREATED>
		<DELIVERY_DATE>02/01/2009</DELIVERY_DATE>
		<ITEMS_TOTAL NET_VALUE="100.00">117.50</ITEMS_TOTAL>
		<DELIVERY_TOTAL NET_VALUE="10.00">10.16</DELIVERY_TOTAL>
		<COMPLETE>0</COMPLETE>
		<QTY_SOLD/>
		<QTY_ASSIGNED/>
		<CANCELLED>
			<CANCELLED_DATE_TIME>2011-06-06</CANCELLED_DATE_TIME>
			<REASON>why?</REASON>
			<USER_NAME>user</USER_NAME>
		</CANCELLED>
		<INVOICES>
			<INVOICE INVOICE_CODE="INV1234" INVOICE_DATE="03/01/2009" SHIP_DATE="04/01/2009" ISSUE_DATE="05/01/2009" INVOICE_STAGE_ID="19" INVOICE_STAGE_DESC="MANUALHOLD" COURIER="Royal Mail" CONSIGNMENT_REF="ABC12345" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABC12345" QTY_SOLD="100.50" QTY_SENT="90.50" MOVED="-1">
				<BOX SHIPPED="-1" SHIP_DATE="02/01/2009" COURIER="Royal Mail" CONSIGNMENT_REF="ABCD12234" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABCD12234">
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </BOX>
				<BOX SHIPPED="-1" SHIP_DATE="02/01/2009" COURIER="Royal Mail" CONSIGNMENT_REF="ABCD12234" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABCD12234">
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </BOX>
            <INVOICE_ITEMS>
               <INVOICE_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <INVOICE_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </INVOICE_ITEMS>
			</INVOICE>
			<INVOICE INVOICE_CODE="INV1234" INVOICE_DATE="03/01/2009" SHIP_DATE="04/01/2009" ISSUE_DATE="05/01/2009" INVOICE_STAGE_ID="19" INVOICE_STAGE_DESC="MANUALHOLD" COURIER="Royal Mail" CONSIGNMENT_REF="ABC12345" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABC12345" QTY_SOLD="100.50" QTY_SENT="90.50" MOVED="-1">
				<BOX SHIPPED="-1" SHIP_DATE="02/01/2009" COURIER="Royal Mail" CONSIGNMENT_REF="ABCD12234" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABCD12234">
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </BOX>
				<BOX SHIPPED="-1" SHIP_DATE="02/01/2009" COURIER="Royal Mail" CONSIGNMENT_REF="ABCD12234" TRACKING_URL="http://www.royalmailtracking.com/trackmyparcel/number=ABCD12234">
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
               <BOX_ITEM STOCK_CODE="ABC" QTY_SENT="123.45"/>
            </BOX>
			</INVOICE>
		</INVOICES>
	</ORDER_STATUS>
</STATUS_LIST>