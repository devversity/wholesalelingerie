<!--KhaosControl Standard XML Import version 1.35 -->
<SALES_ORDERS>
	<SALES_ORDER>
		<CUSTOMER_DETAIL>
			<IS_NEW_CUSTOMER>0</IS_NEW_CUSTOMER>
			<!--Comment: Customer code-->
			<!--Limit: 10 characters-->
			<COMPANY_CODE/>
			<OTHER_REF></OTHER_REF>
			<OTHER_REF_TYPE/>
			<WEB_USER/>
			<COMPANY_CLASS>Trade</COMPANY_CLASS>
			<COMPANY_TYPE></COMPANY_TYPE>
			<!-- If company name is left blank, KhaosControl will construct a name based on the contact details -->
			<!--Limit: 50 characters-->
			<COMPANY_NAME>{{$order->billing_address->company}}</COMPANY_NAME>
			<!--Comment: website URL -->
			<WEB_SITE/>
			<!--Comment: Company Source Code. -->
			<SOURCE_CODE></SOURCE_CODE>
			<MAILING_STATUS/>
			<OPTIN_NEWSLETTER/>
			<!-- VAT no. -->
			<!--Limit: 20 characters-->
			<TAX_REFERENCE/>
			<!-- User defined properties -->
			<UDA>
				<ADDITIONAL NAME="Ip Address">{{$order->ip_address}}</ADDITIONAL>
                <ADDITIONAL NAME="Shipping Method">{{$order->shipping_method}}</ADDITIONAL>
                <ADDITIONAL NAME="Currency Code">{{$order->currency_code}}</ADDITIONAL>
			</UDA>
			<GIFT_AID/>
			<VAT_RELIEF_QUALIFIED/>
			<!-- In addresses, the ADDRESS1, TOWN, POSTCODE, and contact (TITLE/FORENAME/SURNAME) fields should be set -->
			<!-- Other fields are optional but are recommended, particularly COUNTRY_CODE -->
			<ADDRESSES>
				<INVADDR>
					<!--Limit: 35 characters-->
					<IADDRESS1>{{substr($order->billing_address->addr1, 0, 35)}}</IADDRESS1>
					<!--Limit: 35 characters-->
					<IADDRESS2>{{substr($order->billing_address->addr2, 0, 100)}}</IADDRESS2>
					<IADDRESS3>{{substr($order->billing_address->addr3, 0, 100)}}</IADDRESS3>
					<!--Limit: 35 characters-->
					<ITOWN>{{substr($order->billing_address->city, 0, 35)}}</ITOWN>
					<!--Limit: 35 characters-->
					<ICOUNTY>{{substr($order->billing_address->region, 0, 35)}}</ICOUNTY>
					<!--Limit: 10 characters-->
					<IPOSTCODE>{{substr($order->billing_address->postcode, 0, 10)}}</IPOSTCODE>
					<!--Comment: If both Country fields are blank the system default will be assumed ie. GBR, United Kingdom. Country Code is used if available. Country Name is only used if Code is blank. -->
					<ICOUNTRY_CODE>{{$order->billing_address->country}}</ICOUNTRY_CODE>
					<ICOUNTRY_NAME/>
					<!--Limit: 20 characters-->
					<ITITLE></ITITLE>
					<!--Limit: 50 characters-->
					<IFORENAME>{{substr($order->billing_address->customer_firstname, 0, 50)}}</IFORENAME>
					<!--Limit: 50 characters-->
					<ISURNAME>{{!empty($order->billing_address->customer_surname) ? substr($order->billing_address->customer_surname, 0, 50) : "."}}</ISURNAME>
					<!--Limit: 30 characters-->
					<ITEL>{{substr($order->billing_address->telephone, 0, 30)}}</ITEL>
					<!--Limit: 30 characters-->
					<IFAX/>
					<!--Limit: 30 characters-->
					<IMOBILE/>
					<!--Limit: 50 characters-->
					<IEMAIL>{{substr($order->billing_address->email, 0, 50)}}</IEMAIL>
					<!--Comment: contact.email_subscriber: Tickbox (-1 or 0) -->
					<IEMAIL_SUBSCRIBER/>
					<!-- Date of birth: yyyy-mm-dd -->
					<IDOB/>
					<!-- Organisation -->
					<IORGANISATION/>
					<!-- Contact Preferred Communication Method -->
					<IPREFERRED_COMMUNICATION_METHOD/>
				</INVADDR>
				<!--Comment: If empty delivery address details then the system will use invoice address as above-->
				<!--Limit: as above-->
				<DELADDR>
					<DADDRESS1>{{substr($order->shipping_address->addr1, 0, 35)}}</DADDRESS1>
					<DADDRESS2>{{substr($order->shipping_address->addr2, 0, 100)}}</DADDRESS2>
					<DADDRESS3>{{substr($order->shipping_address->addr3, 0, 100)}}</DADDRESS3>
                    <DTOWN>{{$order->shipping_address->city}}</DTOWN>
                    <DCOUNTY>{{$order->shipping_address->region}}</DCOUNTY>
					<DPOSTCODE>{{$order->shipping_address->postcode}}</DPOSTCODE>
					<!--Comment: If both Country fields are blank the system default will be assumed ie. GBR, United Kingdom. Country Code is used if available. Country Name is only used if Code is blank. -->
					<DCOUNTRY_CODE>{{$order->shipping_address->country}}</DCOUNTRY_CODE>
					<DCOUNTRY_NAME/>
					<DTITLE></DTITLE>
					<DFORENAME>{{$order->shipping_address->customer_firstname}}</DFORENAME>
					<DSURNAME>{{$order->shipping_address->customer_surname}}</DSURNAME>
					<DTEL>{{$order->shipping_address->telephone}}</DTEL>
					<DFAX/>
					<DMOBILE/>
					<DEMAIL>{{$order->shipping_address->email}}</DEMAIL>
					<DEMAIL_SUBSCRIBER/>
					<DDOB/>
					<DORGANISATION/>
					<DPREFERRED_COMMUNICATION_METHOD/>
				</DELADDR>
			</ADDRESSES>
		</CUSTOMER_DETAIL>
		<PAYMENTS>
            @foreach ($order->payment as $payment)
			<PAYMENT_DETAIL>
				<!--Payments for orders are not in KhaosControl system currency, they are assumed to be of the same currency as the order.-->
				<PAYMENT_AMOUNT>{{is_null($payment->amount_paid) ? $order->grand_total : $payment->amount_paid}}</PAYMENT_AMOUNT>
				<!--Comment:
					0 = Cash,
					1 = Cheque,
					2 = Credit Card,
					3 = Account,
					4 = Voucher
				-->
                <PAYMENT_TYPE>{{$payment->payment_type_id}}</PAYMENT_TYPE>
				<!--Comment: Card Types include Visa, American, Amex -->
				<CARD_TYPE/>
				<!--Comment: Card number can also be used as voucher reference or cheque reference-->
				<CARD_NUMBER/>
				<CARD_START/>
				<CARD_EXPIRE/>
				<CARD_ISSUE/>
				<CARD_CV2/>
				<CARD_NAME/>
				<!--Comment: The Following fields can be used when payment (of type 2 only) has already been taken or pre-authed. -->
				<!--Comment: If the AUTH_CODE is not empty the payment will be treated as PAID unless PREAUTH is TRUE. -->
				<!--Comment: To process an authorisation following a preauth transaction, all information used must match that used in the preauth transaction.-->
				<!--Comment: -1 = True, 0 = False-->
				<PREAUTH/>
				<!--Comment: AUTH_CODE, e.g. SagePay TxAuthNo (for SagePay) -->
				<AUTH_CODE>{{$payment->auth_code}}</AUTH_CODE>
				<!--Comment: TRANSACTION_ID, e.g. SagePay VPSTxID (for SagePay) or OrderID (HSBC XML/API) -->
                <TRANSACTION_ID>{{$payment->last_trans_id}}</TRANSACTION_ID>
				<!--Comment: PSP_CODE, e.g. Used internally during Actinic process only -->
				<PSP_CODE/>
				<!--Comment: PREAUTH_REF, Reference used when Online transaction was made. ie. the SagePay VendorTxCode. Needed to complete a preauth, or refund transaction through KhaosControl. -->
				<PREAUTH_REF/>
				<!--Comment: SECURITY_REF, SagePay SecurityKey Encryption reference. Needed to complete a preauth, or refund transaction through KhaosControl. -->
				<SECURITY_REF/>
				<!--Comment: SECURITY_COMMENT, (NOT Optional) For SagePay Deferred and Released! Address / Security checking result, ("ALL MATCHED" expected!) -->
				<!--Comment: SECURITY_COMMENT, (Optional) SagePay AVSCV2, (Address and CV2 matched / Address match only / CV2 match only etc. -->
				<SECURITY_COMMENT/>
				<!--Comment: ACCOUNT_NUMBER, (Optional) Card transaction account number in KhaosControl to charge card against, required if multiple accounts are set up in Credit Card Integration Settings -->
				<ACCOUNT_NUMBER/>
				<!--Comment: ACCOUNT_NAME, (Optional) Name of the bank account in Khaos Control to post payment line against -->
				<ACCOUNT_NAME/>
				<!--Comment: CREDIT_SCORE, (Optional) Transaction screening / data checking score (ie Realex realscore) which may help with the identification of possibly high-risk transactions -->
				<CREDIT_SCORE/>
				<!--Comment: Payment transaction timestamp. Date format: yyyy-mm-dd or yyyy-mm-dd hh:mm:ss, other date formats SHOULD NOT BE USED -->
				<TRANSACTION_TIMESTAMP>{{$payment->created_at}}</TRANSACTION_TIMESTAMP>
				<!-- To successfully import a preauth the following fields are needed (see PSP Required Fields document for additional information):
					From SagePay:
						-Preauth must be -1
						-AuthCode, Transaction_ID, Preauth_Ref and Security_Ref must be non-blank and contain the details returned by SagePay from the preauth
						-Security_Comment should be filled in with AAV results if possible (not required)
					From HSBC XML/API:
						-Preauth must be -1
						-Transaction_ID should have the order ID from the original preauth transaction - the other fields are not required for HSBC
				-->
				<CHARGE_AMOUNT>{{is_null($payment->amount_paid) ? $order->grand_total : $payment->amount_paid}}</CHARGE_AMOUNT>
			</PAYMENT_DETAIL>
            @endforeach
		</PAYMENTS>
		<ORDER_HEADER>
			<!--Comment: Date format: yyyy-mm-dd or yyyy-mm-dd hh:mm:ss, other date formats SHOULD NOT BE USED -->
			<!--Comment: LOCKED attribute is optional, locks the delivery date when -1. To be used on the systems which have Delivery Date locking enabled -->
			<ORDER_DATE>{{$order->order_date}}</ORDER_DATE>
			<DELIVERY_DATE>{{$order->order_date}}</DELIVERY_DATE>
			<!--Comment: Used to check against order total calculated in Khaos Control-->
			<ORDER_AMOUNT>{{$order->grid->grand_total}}</ORDER_AMOUNT>
			<!--Comment: If ORDER_CURRENCY_CODE is blank the currency will default to the Khaos Control System currency (normally GBP)-->
			<!--Please specify the 3 character Currency Code, associated with the currency in Khaos Control (System Data/Currencies) ie. GBP / USD / EUR etc.-->
			<ORDER_CURRENCY_CODE>{{$order->grid->order_currency_code}}</ORDER_CURRENCY_CODE>
			<!--allows Sales Orders to be placed against specific sites-->
			<SITE>Kevco Wholesale</SITE>
			<ASSOCIATED_REF>{{(!empty($site_information->order_ref) ? $site_information->order_ref : '').str_pad($order->increment_id, 5, '0', STR_PAD_LEFT)}}</ASSOCIATED_REF>
			<ORDER_NOTE/>
			<INVOICE_NOTE/>
            <DELIVERY_NET>{{$order->shipping_amount}}</DELIVERY_NET>
            <DELIVERY_TAX>{{$order->shipping_tax}}</DELIVERY_TAX>
			<DELIVERY_GRS/>
			<COURIER_CODE/>
			<COURIER_DESC/>
			<PO_NUMBER>{{substr($order->po_number, 0, 30)}}</PO_NUMBER>
			<KEYCODE_CODE/>
			<BRAND></BRAND>
			<SALES_SOURCE>Kevco Website</SALES_SOURCE>
			<COURIER_NOTE></COURIER_NOTE>
			<INV_PRIORITY>{{$invoice_priority}}</INV_PRIORITY>
			<GIFT_AID/>
			<MANUAL_RECEIVED>0</MANUAL_RECEIVED>
			<REQUIRED_BY_DATE/>
			<CLIENT_NAME/>
			<WEBSITE_NAME>{{!empty($site_information) ? $site_information->name : 'Kevco Wholesale'}}</WEBSITE_NAME>
			<CHANNEL_ID/>
			<SHIP_DATE>{{$order->order_date}}</SHIP_DATE>
			<CONSIGNMENT_REF></CONSIGNMENT_REF>
		</ORDER_HEADER>
		<ORDER_ITEMS>
            @foreach ($order->items as $item)
                @if($item->product_type != "configurable")
                <ORDER_ITEM>
                    <STOCK_CODE>{{$item->sku}}</STOCK_CODE>
                    <MAPPING_TYPE>{{$order->mapping_type_id}}</MAPPING_TYPE>
                    <STOCK_DESC></STOCK_DESC>
                    <ORDER_QTY>{{$item->quantity}}</ORDER_QTY>
                    <PRICE_NET>{{isset($price_override[$item->sku]) ? round($price_override[$item->sku], 2) : $item->price}}</PRICE_NET>
                    <KSD_DISCOUNT>{{$item->discount_amount}}</KSD_DISCOUNT>
                    <TAX_RATE/>
                    <SITE/>
                </ORDER_ITEM>
                @endif
            @endforeach
		</ORDER_ITEMS>
	</SALES_ORDER>
</SALES_ORDERS>
