<!--KhaosControl Standard XML Import version 1.35 -->
<SALES_ORDERS>
	<SALES_ORDER>
		<CUSTOMER_DETAIL>
			<!--Comment: -1 = True, 0 = False-->
			<!--Comment: If false, update customer details from values (matching on COMPANY_CODE) instead of creating new-->
			<!--If true, then a new customer is always created - no deduping is done -->
			<!-- Recommended setting for <IS_NEW_CUSTOMER> tag is 0 as this lets Khaos Control check if customer already exists -->
			<!-- THIS FIELD IS COMPULSORY -->
			<IS_NEW_CUSTOMER/>
			<!--Comment: Customer code-->
			<!--Limit: 10 characters-->
			<COMPANY_CODE>KCS001</COMPANY_CODE>
			<!--Comment: Customer other ref-->
			<!--Limit: 30 characters-->
			<OTHER_REF/>
			<!--Comment: Customer other type (for KCS use only, please leave blank) -->
			<OTHER_REF_TYPE/>
			<!--Comment: Web site user name-->
			<!--Limit: 50 characters-->
			<WEB_USER/>
			<!--Comment: Specify a company class from KhaosControl, Only pre-existing classes will be mapped onto the customer record. Case Sensitive. -->
			<COMPANY_CLASS>Internet</COMPANY_CLASS>
			<!--Comment: Specify a company type from KhaosControl, Only pre-existing types will be mapped onto the customer record. Case Sensitive. -->
			<COMPANY_TYPE>Others</COMPANY_TYPE>
			<!-- If company name is left blank, KhaosControl will construct a name based on the contact details -->
			<!--Limit: 50 characters-->
			<COMPANY_NAME>Khaos Control Solutions Ltd</COMPANY_NAME>
			<!--Comment: website URL -->
			<WEB_SITE/>
			<!--Comment: Company Source Code. -->
			<SOURCE_CODE>KC001</SOURCE_CODE>
			<!--Comment: Current defaults are (codes/meanings may vary from system to system):
				1 - Our use and any 3rd parties
				2 - Our use and selected 3rd parties
				3 - Our use and no 3rd parties
				4 - Our use - catalogues & order correspondence only
				5 - Our use - order correspondence only
				6 - Do not use
				7 - Gone aways
				8 - Deceased
			-->
			<MAILING_STATUS/>
			<!--Comment: DO NOT USE for GDPR compliance-->
			<OPTIN_NEWSLETTER/>
			<!-- VAT no. -->
			<!--Limit: 20 characters-->
			<TAX_REFERENCE/>
			<!-- User defined properties -->
			<UDA>
				<ADDITIONAL NAME="Security Question">Mother's Maiden Name</ADDITIONAL>
				<ADDITIONAL NAME="Favourite Colour">Salmon Pink</ADDITIONAL>
			</UDA>
			<!-- Project 63 only - Gift Aid. Boolean field (-1, 0) This will apply to new customers only -->
			<GIFT_AID/>
			<VAT_RELIEF_QUALIFIED/>
			<!-- In addresses, the ADDRESS1, TOWN, POSTCODE, and contact (TITLE/FORENAME/SURNAME) fields should be set -->
			<!-- Other fields are optional but are recommended, particularly COUNTRY_CODE -->
			<ADDRESSES>
				<INVADDR>
					<IADDRESS_ID OTHER_REF="ABC">ABC</IADDRESS_ID>               
					<!--Limit: 35 characters-->
					<IADDRESS1>Century House</IADDRESS1>
					<!--Limit: 35 characters-->
					<IADDRESS2>84 Commercial Road</IADDRESS2>
					<IADDRESS3/>
					<!--Limit: 35 characters-->
					<ITOWN>Grantham</ITOWN>
					<!--Limit: 35 characters-->
					<ICOUNTY>Lincolnshire</ICOUNTY>
					<!--Limit: 10 characters-->
					<IPOSTCODE>NG31 6DB</IPOSTCODE>
					<!--Comment: If both Country fields are blank the system default will be assumed ie. GBR, United Kingdom. Country Code is used if available. Country Name is only used if Code is blank. -->
					<ICOUNTRY_CODE/>
					<ICOUNTRY_NAME/>
					<ICONTACT_ID OTHER_REF="ABC">ABC</ICONTACT_ID>
					<!--Limit: 20 characters-->
					<ITITLE>Mr</ITITLE>
					<!--Limit: 50 characters-->
					<IFORENAME>Mike</IFORENAME>
					<!--Limit: 50 characters-->
					<ISURNAME>Cockfield</ISURNAME>
					<!--Limit: 30 characters-->
					<ITEL>01476 562447</ITEL>
					<!--Limit: 30 characters-->
					<IFAX/>
					<!--Limit: 30 characters-->
					<IMOBILE/>
					<!--Limit: 50 characters-->
					<IEMAIL>info@khaoscontrol.com</IEMAIL>
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
					<DADDRESS_ID OTHER_REF="ABC">ABC</DADDRESS_ID>
					<DADDRESS1/>
					<DADDRESS2/>
					<DADDRESS3/>
					<DTOWN/>
					<DCOUNTY/>
					<DPOSTCODE>NG31 6DB</DPOSTCODE>
					<!--Comment: If both Country fields are blank the system default will be assumed ie. GBR, United Kingdom. Country Code is used if available. Country Name is only used if Code is blank. -->
					<DCOUNTRY_CODE/>
					<DCOUNTRY_NAME/>
					<DCONTACT_ID OTHER_REF="ABC">ABC</DCONTACT_ID>
					<DTITLE/>
					<DFORENAME/>
					<DSURNAME/>
					<DTEL/>
					<DFAX/>
					<DMOBILE/>
					<DEMAIL/>
					<!--Comment: contact.email_subscriber: Tickbox -->
					<DEMAIL_SUBSCRIBER/>
					<!-- Date of birth: yyyy-mm-dd -->
					<DDOB/>
					<!-- Organisation -->
					<DORGANISATION/>
					<!-- Contact Preferred Communication Method -->
					<DPREFERRED_COMMUNICATION_METHOD/>
				</DELADDR>
			</ADDRESSES>
			<CUSTOM>
				<CUSTOM_PROPERTY NAME="ORDER_WEIGHT">1000</CUSTOM_PROPERTY>
				<CUSTOM_PROPERTY NAME="INFLATE_PRICES">-1</CUSTOM_PROPERTY>
			</CUSTOM>
		</CUSTOMER_DETAIL>
		<PAYMENTS>
			<PAYMENT_DETAIL>
				<!--Payments for orders are not in KhaosControl system currency, they are assumed to be of the same currency as the order.-->
				<PAYMENT_AMOUNT>56.99</PAYMENT_AMOUNT>
				<!--Comment:
					0 = Cash,
					1 = Cheque,
					2 = Credit Card,
					3 = Account,
					4 = Voucher
				-->
				<PAYMENT_TYPE/>
				<!--Comment: Card Types include Visa, American, Amex -->
				<CARD_TYPE>Visa</CARD_TYPE>
				<!--Comment: Card number can also be used as voucher reference or cheque reference-->
				<CARD_NUMBER/>
				<CARD_START/>
				<CARD_EXPIRE/>
				<CARD_ISSUE/>
				<CARD_CV2/>
				<CARD_NAME>Mr Mike Cockfield</CARD_NAME>
				<!--Comment: The Following fields can be used when payment (of type 2 only) has already been taken or pre-authed. -->
				<!--Comment: If the AUTH_CODE is not empty the payment will be treated as PAID unless PREAUTH is TRUE. -->
				<!--Comment: To process an authorisation following a preauth transaction, all information used must match that used in the preauth transaction.-->
				<!--Comment: -1 = True, 0 = False-->
				<PREAUTH/>
				<!--Comment: AUTH_CODE, e.g. SagePay TxAuthNo (for SagePay) -->
				<AUTH_CODE/>
				<!--Comment: TRANSACTION_ID, e.g. SagePay VPSTxID (for SagePay) or OrderID (HSBC XML/API) -->
				<TRANSACTION_ID/>
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
				<ACCOUNT_NUMBER>1</ACCOUNT_NUMBER>
				<!--Comment: ACCOUNT_NAME, (Optional) Name of the bank account in Khaos Control to post payment line against -->
				<ACCOUNT_NAME>Business Current</ACCOUNT_NAME>
				<!--Comment: CREDIT_SCORE, (Optional) Transaction screening / data checking score (ie Realex realscore) which may help with the identification of possibly high-risk transactions -->
				<CREDIT_SCORE/>
				<!--Comment: Payment transaction timestamp. Date format: yyyy-mm-dd or yyyy-mm-dd hh:mm:ss, other date formats SHOULD NOT BE USED -->
				<TRANSACTION_TIMESTAMP>2004-01-21 10:11:22</TRANSACTION_TIMESTAMP>
				<!-- To successfully import a preauth the following fields are needed (see PSP Required Fields document for additional information):
					From SagePay:
						-Preauth must be -1
						-AuthCode, Transaction_ID, Preauth_Ref and Security_Ref must be non-blank and contain the details returned by SagePay from the preauth
						-Security_Comment should be filled in with AAV results if possible (not required)
					From HSBC XML/API:
						-Preauth must be -1
						-Transaction_ID should have the order ID from the original preauth transaction - the other fields are not required for HSBC
				-->
				<CHARGE_AMOUNT>2.99</CHARGE_AMOUNT>
			</PAYMENT_DETAIL>
			<PAYMENT_DETAIL>
				<PAYMENT_AMOUNT>56.99</PAYMENT_AMOUNT>
				<PAYMENT_TYPE/>
				<CARD_TYPE>Visa</CARD_TYPE>
				<CARD_NUMBER/>
				<CARD_START/>
				<CARD_EXPIRE/>
				<CARD_ISSUE/>
				<CARD_CV2/>
				<CARD_NAME>Mr Mike Cockfield</CARD_NAME>
				<PREAUTH/>
				<AUTH_CODE/>
				<TRANSACTION_ID/>
				<PSP_CODE/>
				<PREAUTH_REF/>
				<SECURITY_REF/>
				<SECURITY_COMMENT/>
				<ACCOUNT_NUMBER>1</ACCOUNT_NUMBER>
				<ACCOUNT_NAME>Business Current</ACCOUNT_NAME>
				<CREDIT_SCORE>Score</CREDIT_SCORE>
				<TRANSACTION_TIMESTAMP/>
				<CHARGE_AMOUNT/>
			</PAYMENT_DETAIL>
		</PAYMENTS>
		<ORDER_HEADER>
			<INTERNAL_REF TYPE_ID="ABC" TYPE_DESC="ABC" VTYPE_ID="ABC" INVSTATUS_ID="0_">ABC</INTERNAL_REF>
			<!--Comment: FORCE_SORDER_CODE IS FOR INTERNAL USE ONLY - DO NOT PASS THIS TAG VIA INTEGRATION-->
			<FORCE_SORDER_CODE>ABC123</FORCE_SORDER_CODE>
			<!--Comment: Date format: yyyy-mm-dd or yyyy-mm-dd hh:mm:ss, other date formats SHOULD NOT BE USED -->
			<!--Comment: LOCKED attribute is optional, locks the delivery date when -1. To be used on the systems which have Delivery Date locking enabled -->
			<ORDER_DATE>2004-01-20</ORDER_DATE>
			<DELIVERY_DATE LOCKED="-1">2004-01-21 10:11:22</DELIVERY_DATE>
			<!--Comment: Used to check against order total calculated in Khaos Control-->
			<ORDER_AMOUNT>56.99</ORDER_AMOUNT>
			<!--Comment: If ORDER_CURRENCY_CODE is blank the currency will default to the Khaos Control System currency (normally GBP)-->
			<!--Please specify the 3 character Currency Code, associated with the currency in Khaos Control (System Data/Currencies) ie. GBP / USD / EUR etc.-->
			<ORDER_CURRENCY_CODE>GBP</ORDER_CURRENCY_CODE>
			<!--allows Sales Orders to be placed against specific sites-->
			<SITE>Main Site</SITE>
			<!--Comment: Usually the web generated order reference. -->
			<!-- THIS FIELD IS COMPULSORY -->
			<!-- This should be unique across all orders: it is used to make sure the same order is not imported twice -->
			<!--Limit: 50 characters-->
			<ASSOCIATED_REF>DK07151555O</ASSOCIATED_REF>
			<!--Comment: Could be used to record comments from customers or to describe special delivery instructions, this will appear on KhaosControl Delivery Note Documentation.-->
			<!--Limit: 2000 characters-->
			<!--Agent against the order-->
			<AGENT>Test Agent</AGENT>
			<ORDER_NOTE/>
			<!--Comment: Could be used to record comments from customers or to describe special delivery instructions, this will appear on KhaosControl Invoice Documentation.-->
			<!--Limit: 2000 characters-->
			<INVOICE_NOTE/>
			<!--Comment: Use net & tax (VAT) OR grs. If grs is used amount is assumed to be VATable-->
			<!--Specify -1 to disable delivery -->
			<!--Any other value for delivery (inc. zero) is saved against the order as the delivery charge -->
			<!--If the tags are blank/missing then the system will calculate its own delivery charges -->
			<DELIVERY_NET/>
			<DELIVERY_TAX/>
			<DELIVERY_GRS/>
			<!--Comment: Specify either courier code OR courier description. Code is preferred (more likely to be unique) -->
			<COURIER_CODE/>
			<COURIER_DESC/>
			<!--Comment: Purchase Order number from customer. Often used for customers paying on account. -->
			<PO_NUMBER>XXYZ</PO_NUMBER>
			<!--Comment: Specify a promotion code from KhaosControl, Only pre-existing codes will be mapped onto the order.-->
			<KEYCODE_CODE/>
			<!--Comment: Specify a brand name from KhaosControl, Only pre-existing names will be mapped onto the order. -->
			<BRAND>Website A</BRAND>
			<!--Comment: Specify a source code from KhaosControl, Only pre-existing codes will be mapped onto the order. Case Sensitive. -->
			<SALES_SOURCE>INTERNET</SALES_SOURCE>
			<!--Comment: Courier note is actually recorded against the DELIVERY address, overwriting any notes already present -->
			<!--Limit: 255 characters-->
			<COURIER_NOTE>Green door</COURIER_NOTE>
			<!--Comment: Select a priority to record against the invoice (set up in KhaosControl / SystemData) -->
			<!--Comment: Invoice priority matches on description only at the moment, not 'code' or 'mark' -->
			<INV_PRIORITY/>
			<!-- Project 63 only - Gift Aid. Boolean field (-1, 0). This will override the customers default. -->
			<GIFT_AID/>
			<!--Commant: Manual Payments set against SOrder 0 - OFF, -1 - ON, when not included by default 0 -->
			<MANUAL_RECEIVED>0</MANUAL_RECEIVED>
			<!--Comment: Reqired by date -->
			<REQUIRED_BY_DATE/>
			<!-- This is not the customer, this is only used on fulfilment systems that have multiple clients that they hold stock for and fulfil orders for -->
			<CLIENT_NAME/>
			<!-- (Optional) Custom header properties - only set after discussion with Khaos Control Solutions-->
			<CUSTOM>
				<CUSTOM_PROPERTY NAME="ORDER_WEIGHT">1000</CUSTOM_PROPERTY>
				<CUSTOM_PROPERTY NAME="INFLATE_PRICES">-1</CUSTOM_PROPERTY>
			</CUSTOM>
			<!-- (Optional) CRM Comm Logs -->
			<COMM_LOGS>
				<COMM_LOG_ENTRY>
					<CONTACT_TYPE>Application</CONTACT_TYPE>
					<DATE>2004-01-21 10:11:22</DATE>
					<NEXT_DATE>2004-01-21 10:11:22</NEXT_DATE>
					<DESCRIPTION>Comm log description</DESCRIPTION>
					<RESPONSE>Comm log response</RESPONSE>
				</COMM_LOG_ENTRY>
				<COMM_LOG_ENTRY>
					<CONTACT_TYPE>Application</CONTACT_TYPE>
					<DATE>2004-01-21 10:11:22</DATE>
					<NEXT_DATE>2004-01-21 10:11:22</NEXT_DATE>
					<DESCRIPTION>Comm log description 2</DESCRIPTION>
					<RESPONSE>Comm log response 2</RESPONSE>
				</COMM_LOG_ENTRY>
			</COMM_LOGS>
			<!-- (Optional) Website name. When set, the Order Item Description will be pulled from WebCategories for the Website. See dev 002620 -->
			<WEBSITE_NAME>FrenchSite</WEBSITE_NAME>
			<!-- (Optional) Channel ID. When set and not zero it is assumed to be a channel listing import, item's IMPORT_REF is treated as LISTING_CODE. See dev 006210 -->
			<CHANNEL_ID>CH0</CHANNEL_ID>
			<SHIP_DATE>2004-01-20</SHIP_DATE>
			<CONSIGNMENT_REF>AAA001</CONSIGNMENT_REF>
		</ORDER_HEADER>
		<ORDER_ITEMS>
			<ORDER_ITEM>
				<!-- This field contains a stock code or other_ref value - see MAPPING_TYPE field -->
				<!-- THIS FIELD IS COMPULSORY -->
				<STOCK_CODE>HL0453</STOCK_CODE>
				<!--Comment:
					-1 = Automatic matching. Attempts to match the following fields in order:
						STOCK_CODE tag to Stock Code,
						STOCK_CODE tag to Other Reference,
						STOCK_CODE tag to Stock Barcodes,
						STOCK_CODE tag to Channel Stock Code (Web Categories),
						STOCK_DESC tag to Stock Description.
					1 = Above stock code relates to the Khaos Control "stock code" value,
					2 = Stock code relates to the Khaos Control "other ref" value,
					3 = External mapping file used for stock code resolution,
					4 = Match contents of STOCK_DESC tag to Stock Description (not recommended!)
					5 = Match contents of STOCK_CODE tag to Stock Barcodes
					6 = Match contents of STOCK_CODE tag to Channel Stock Code (Web Categories)
					7 = Match contents of STOCK_CODE tag to Catalogue Ref (not included in auto-matching)
				-->
				<!-- THIS FIELD IS COMPULSORY -->
				<MAPPING_TYPE/>
				<!-- If no description specified, the system will use the default description against the stock item. -->
				<!-- Recommended to be specified so description on printouts matches website description -->
				<STOCK_DESC>LARGE DISPLAY CABINET</STOCK_DESC>
				<!--Comment: Can be used as a comment or note against each order line-->
				<EXTENDED_DESC>Please fit with brass handles.</EXTENDED_DESC>
				<ORDER_QTY/>
				<!--Comment: Use either net OR grs. If both are used only net will be actioned-->
				<!--We recommend use of PRICE_NET when the line is non-Vatable and PRICE_GRS when it is, as any other use may cause rounding issues; see "Khaos Control Sales Order Calc v1.0.xls" for details -->
				<PRICE_NET>12.34</PRICE_NET>
				<PRICE_GRS>56.99</PRICE_GRS>
				<!--Comment:
					1 - Standard (e.g, 15%),
					2 - Zero,
					4 - Reduced (e.g, 12%),
					>4 user defined
				-->
				<!--Comment: Optional. If omitted the system will use the applicable tax rate stored against the stock item within Khaos Control -->
				<!--Comment: NOTE: Do not include a tax rate directly (eg 17.5), you must use the codes mentioned above (1, 2, ...) -->
				<TAX_RATE/>
				<!--Comment: Optional fields that are used in mapping Size, Colour, Style situations-->
				<OPTION_REF1>OAK</OPTION_REF1>
				<OPTION_REF2>PANELED</OPTION_REF2>
				<OPTION_REF3/>
				<!-- (Optional) Custom item properties - only set after discussion with Khaos Control Solutions (KCS Note: number is grid FieldIndex) -->
				<CUSTOM>
					<CUSTOM_PROPERTY NAME="56">ALPHA1000</CUSTOM_PROPERTY>
					<CUSTOM_PROPERTY NAME="57">12345</CUSTOM_PROPERTY>
				</CUSTOM>
				<!--Comment: Specify a Free Item Reason from KhaosControl, Only pre-existing reasons will be mapped onto the order. Case insensitive. -->
				<FREEITEM_REASON/>
				<!--Comment: Import ref is an external SOrder item reference (not stock item ref!) -->
				<IMPORT_REF/>
				<!--WebItem_Ref added as part of 010526-->
				<WEBITEM_REF/>
				<!--Comment: For importing related pack header and pack item products and keep them together on the sales order -->
				<!--Comment: Each child item of a pack should have the same 3 digits before the . and 002 after -->
				<!--Comment: Examples: 001.001 (pack 1 parent), 001.002 (pack 1 child 1), 001.002 (pack 1 child 2), 001.002 (pack 1 child 3); 002.001 (pack 2 parent), 002.002 (pack 2 child 1) -->
				<PACK_SORT_ORDER>001.001</PACK_SORT_ORDER>
				<!--allows assigning Stock to Sales Orders Item from specific sites-->
				<SITE>Main Site</SITE>
			</ORDER_ITEM>
			<ORDER_ITEM>
				<STOCK_CODE>HL0454</STOCK_CODE>
				<MAPPING_TYPE/>
				<STOCK_DESC>LARGE DISPLAY CABINET</STOCK_DESC>
				<ORDER_QTY/>
				<PRICE_NET>12.34</PRICE_NET>
				<KSD_DISCOUNT>5.00</KSD_DISCOUNT>
				<TAX_RATE/>
				<SITE/>
			</ORDER_ITEM>
		</ORDER_ITEMS>
	</SALES_ORDER>
	<SALES_ORDER>
		<CUSTOMER_DETAIL>
			<IS_NEW_CUSTOMER/>
			<COMPANY_CODE>KCS001</COMPANY_CODE>
			<OTHER_REF/>
			<WEB_USER/>
			<COMPANY_CLASS>Internet</COMPANY_CLASS>
			<COMPANY_TYPE>Others</COMPANY_TYPE>
			<COMPANY_NAME>Keystone Software Development Ltd</COMPANY_NAME>
			<WEB_SITE/>
			<SOURCE_CODE>KC001</SOURCE_CODE>
			<MAILING_STATUS/>
			<OPTIN_NEWSLETTER>-1</OPTIN_NEWSLETTER>
			<TAX_REFERENCE/>
			<UDA>
				<ADDITIONAL NAME="Security Question">Mother's Maiden Name</ADDITIONAL>
				<ADDITIONAL NAME="Favourite Colour">Salmon Pink</ADDITIONAL>
			</UDA>
			<GIFT_AID/>
			<VAT_RELIEF_QUALIFIED/>
			<ADDRESSES>
				<INVADDR>
					<IADDRESS_ID OTHER_REF="ABC">ABC</IADDRESS_ID>               
					<IADDRESS1>Century House</IADDRESS1>
					<IADDRESS2>84 Commercial Road</IADDRESS2>
					<IADDRESS3/>
					<ITOWN>Grantham</ITOWN>
					<ICOUNTY>Lincolnshire</ICOUNTY>
					<IPOSTCODE>NG31 6DB</IPOSTCODE>
					<ICOUNTRY_CODE/>
					<ICOUNTRY_NAME/>
					<ICONTACT_ID OTHER_REF="ABC">ABC</ICONTACT_ID>
					<ITITLE>Mr</ITITLE>
					<IFORENAME>Mike</IFORENAME>
					<ISURNAME>Cockfield</ISURNAME>
					<ITEL>01476 562447</ITEL>
					<IFAX/>
					<IMOBILE/>
					<IEMAIL>info@khaoscontrol.com</IEMAIL>
					<IEMAIL_SUBSCRIBER/>
					<IDOB/>
					<IORGANISATION/>
					<IPREFERRED_COMMUNICATION_METHOD/>
				</INVADDR>
				<DELADDR>
					<DADDRESS_ID OTHER_REF="ABC">ABC</DADDRESS_ID>               
					<DADDRESS1/>
					<DADDRESS2/>
					<DADDRESS3/>
					<DTOWN/>
					<DCOUNTY/>
					<DPOSTCODE>NG31 6DB</DPOSTCODE>
					<DCOUNTRY_CODE/>
					<DCOUNTRY_NAME/>
					<DCONTACT_ID OTHER_REF="ABC">ABC</DCONTACT_ID>               
					<DTITLE/>
					<DFORENAME/>
					<DSURNAME/>
					<DTEL/>
					<DFAX/>
					<DMOBILE/>
					<DEMAIL/>
					<DEMAIL_SUBSCRIBER/>
					<DDOB/>
					<DORGANISATION/>
					<DPREFERRED_COMMUNICATION_METHOD/>
				</DELADDR>
			</ADDRESSES>
			<CUSTOM>
				<CUSTOM_PROPERTY NAME="ORDER_WEIGHT">1000</CUSTOM_PROPERTY>
				<CUSTOM_PROPERTY NAME="INFLATE_PRICES">-1</CUSTOM_PROPERTY>
			</CUSTOM>
		</CUSTOMER_DETAIL>
		<PAYMENTS>
			<PAYMENT_DETAIL>
				<PAYMENT_AMOUNT>56.99</PAYMENT_AMOUNT>
				<PAYMENT_TYPE/>
				<CARD_TYPE>Visa</CARD_TYPE>
				<CARD_NUMBER>49123123123123</CARD_NUMBER>
				<CARD_START>0104</CARD_START>
				<CARD_EXPIRE>1106</CARD_EXPIRE>
				<CARD_ISSUE>01</CARD_ISSUE>
				<CARD_CV2>111</CARD_CV2>
				<CARD_NAME>Mr Mike Cockfield</CARD_NAME>
				<PREAUTH>0</PREAUTH>
				<AUTH_CODE/>
				<TRANSACTION_ID/>
				<PREAUTH_REF/>
				<SECURITY_REF/>
				<SECURITY_COMMENT/>
				<ACCOUNT_NUMBER>1</ACCOUNT_NUMBER>
				<ACCOUNT_NAME>Business Current</ACCOUNT_NAME>
				<CHARGE_AMOUNT>2.99</CHARGE_AMOUNT>
			</PAYMENT_DETAIL>
			<PAYMENT_DETAIL>
				<PAYMENT_AMOUNT>56.99</PAYMENT_AMOUNT>
				<PAYMENT_TYPE/>
				<CARD_TYPE>Visa</CARD_TYPE>
				<CARD_NUMBER>49123123123123</CARD_NUMBER>
				<CARD_START>0104</CARD_START>
				<CARD_EXPIRE>1106</CARD_EXPIRE>
				<CARD_ISSUE>01</CARD_ISSUE>
				<CARD_CV2>111</CARD_CV2>
				<CARD_NAME>Mr Mike Cockfield</CARD_NAME>
				<PREAUTH>0</PREAUTH>
				<AUTH_CODE/>
				<TRANSACTION_ID/>
				<PREAUTH_REF/>
				<SECURITY_REF/>
				<SECURITY_COMMENT/>
				<ACCOUNT_NUMBER>1</ACCOUNT_NUMBER>
				<ACCOUNT_NAME>Business Current</ACCOUNT_NAME>
				<CHARGE_AMOUNT/>
			</PAYMENT_DETAIL>
		</PAYMENTS>
		<ORDER_HEADER>
			<INTERNAL_REF TYPE_ID="ABC" TYPE_DESC="ABC" VTYPE_ID="ABC">ABC</INTERNAL_REF>
			<ORDER_DATE>2004-01-20</ORDER_DATE>
			<DELIVERY_DATE>2004-01-21 10:11:22</DELIVERY_DATE>
			<ORDER_AMOUNT>56.99</ORDER_AMOUNT>
			<ORDER_CURRENCY_CODE>GBP</ORDER_CURRENCY_CODE>
			<SITE>Main Site</SITE>
			<ASSOCIATED_REF>DK07151555O</ASSOCIATED_REF>
			<AGENT>Test Agent</AGENT>
			<ORDER_NOTE/>
			<INVOICE_NOTE/>
			<DELIVERY_NET/>
			<DELIVERY_TAX/>
			<DELIVERY_GRS/>
			<COURIER_CODE/>
			<COURIER_DESC/>
			<PO_NUMBER>XXYZ</PO_NUMBER>
			<KEYCODE_CODE/>
			<BRAND>Website A</BRAND>
			<SALES_SOURCE>INTERNET</SALES_SOURCE>
			<COURIER_NOTE>Green door</COURIER_NOTE>
			<INV_PRIORITY/>
			<GIFT_AID/>
			<MANUAL_RECEIVED>0</MANUAL_RECEIVED>
			<REQUIRED_BY_DATE/>
			<CLIENT_NAME/>
			<CUSTOM>
				<CUSTOM_PROPERTY NAME="ORDER_WEIGHT">1000</CUSTOM_PROPERTY>
				<CUSTOM_PROPERTY NAME="INFLATE_PRICES">-1</CUSTOM_PROPERTY>
			</CUSTOM>
			<COMM_LOGS>
				<COMM_LOG_ENTRY>
					<CONTACT_TYPE>Application</CONTACT_TYPE>
					<DATE>2004-01-21 10:11:22</DATE>
					<NEXT_DATE>2004-01-21 10:11:22</NEXT_DATE>
					<DESCRIPTION>Comm log description</DESCRIPTION>
					<RESPONSE>Comm log response</RESPONSE>
				</COMM_LOG_ENTRY>
				<COMM_LOG_ENTRY>
					<CONTACT_TYPE>Application</CONTACT_TYPE>
					<DATE>2004-01-21 10:11:22</DATE>
					<NEXT_DATE>2004-01-21 10:11:22</NEXT_DATE>
					<DESCRIPTION>Comm log description 2</DESCRIPTION>
					<RESPONSE>Comm log response 2</RESPONSE>
				</COMM_LOG_ENTRY>
			</COMM_LOGS>
			<WEBSITE_NAME>FrenchSite</WEBSITE_NAME>
			<CHANNEL_ID>CH1</CHANNEL_ID>
			<SHIP_DATE/>
			<CONSIGNMENT_REF/>
		</ORDER_HEADER>
		<ORDER_ITEMS>
			<ORDER_ITEM>
				<STOCK_CODE>HL0453</STOCK_CODE>
				<MAPPING_TYPE/>
				<STOCK_DESC>LARGE DISPLAY CABINET</STOCK_DESC>
				<EXTENDED_DESC>Please fit with brass handles.</EXTENDED_DESC>
				<ORDER_QTY/>
				<PRICE_NET>12.34</PRICE_NET>
				<PRICE_GRS>56.99</PRICE_GRS>
				<TAX_RATE/>
				<OPTION_REF1>OAK</OPTION_REF1>
				<OPTION_REF2>PANELED</OPTION_REF2>
				<OPTION_REF3/>
				<CUSTOM>
					<CUSTOM_PROPERTY NAME="56">ALPHA1000</CUSTOM_PROPERTY>
					<CUSTOM_PROPERTY NAME="57">12345</CUSTOM_PROPERTY>
				</CUSTOM>
				<FREEITEM_REASON/>
				<IMPORT_REF/>
				<WEBITEM_REF/>
				<KSD_DISCOUNT>5.00</KSD_DISCOUNT>
				<PACK_SORT_ORDER/>
				<SITE/>
			</ORDER_ITEM>
			<ORDER_ITEM>
				<STOCK_CODE>HL0453</STOCK_CODE>
				<MAPPING_TYPE/>
				<STOCK_DESC>LARGE DISPLAY CABINET</STOCK_DESC>
				<EXTENDED_DESC>Please fit with brass handles.</EXTENDED_DESC>
				<ORDER_QTY/>
				<PRICE_NET>12.34</PRICE_NET>
				<PRICE_GRS>56.99</PRICE_GRS>
				<TAX_RATE/>
				<OPTION_REF1>OAK</OPTION_REF1>
				<OPTION_REF2>PANELED</OPTION_REF2>
				<OPTION_REF3/>
				<CUSTOM>
					<CUSTOM_PROPERTY NAME="56">ALPHA1000</CUSTOM_PROPERTY>
					<CUSTOM_PROPERTY NAME="57">12345</CUSTOM_PROPERTY>
				</CUSTOM>
				<FREEITEM_REASON/>
				<IMPORT_REF/>
				<WEBITEM_REF/>
				<KSD_DISCOUNT>5.00</KSD_DISCOUNT>
				<SITE/>
			</ORDER_ITEM>
		</ORDER_ITEMS>
	</SALES_ORDER>
</SALES_ORDERS>
<!--
	Change History:
	1.01 - KEYCODE Support
	1.02 - SALES_SOURCE Support
	1.03 - COMPANY_CLASS Support
	1.04 - Multiple Payment Support
	1.05 - Support added for Authorised / PreAuthed Payments.
	1.06 - CARD_NAME added
	1.07 - Clarified behaviour of DELIVERY tags
	1.08 - Country Name import now supported. Invoice Country used as Company Country. Blank field defaults to system default. ie. "United Kingdom"
	1.09 - Invoice Note support added, the tag information will appear on KhaosControl Invoice Documentation for imported orders.
	1.10 - Country Specified by 3 letter Code now supported. Invoice Country used as Company Country. This field takes precedense over Country Name
	1.11 - clarified behaviour of payment tags when dealing with Protx payments
	1.12 - Currency ID now supported, add payment ACCOUNT_NUMBER
	1.13 - Courier code/desc imported if specified
	1.14 - Courier note & invoice priority tags added, ADDITIONAL tags added to customer
	1.15 - UDA collection added / CUSTOMER_DETAIL->ADDITIONAL grouped in the collection
	1.16 - Boolean (-1, 0) GIFT AID tag support added for project 0063. New Tags accepted under Customer Details and Order Header. Not supported on any other project.
	1.17 - Customer Source Code added
	1.18 - COMPANY_TYPE Support
	1.19 - CUSTOM_PROPERTY support, added more comments
	1.20 - ACCOUNT_NAME tag added
	1.21 - PO_NUMBER tag added
	1.22 - DOB tag (Contact.DateOfBirth) added
	1.23 - Custom options added
	1.24 - OTHER_REF and WEB_USER against CUSTOMER_DETAIL
	1.25 - FREEITEM_REASON in ORDER_ITEMS
	1.26 - IMPORT_REF in ORDER_ITEMS
	1.27 - Add character limits to major fields and slight tidy up
	1.28 - REQUIRED_DATE
	1.29 - AGENT
	1.30 - Removed comment for keycode to reflect actual functionality.
	1.31 - Changed Protx to SagePay
	1.32 - Preferred Communication Method added to contact details
	1.33 - Added CLIENT_NAME for fulfilment customers
	1.34 - Added OTHER_REF_TYPE for KCS use
	1.35 - Added PACK_SORT_ORDER to allow packs to be imported onto an order in the correct order
	1.36 - Added WEBITEM_REF in ORDER_ITEMS
-->