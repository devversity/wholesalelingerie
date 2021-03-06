<COMPANYS>
	<COMPANY>
		<!--Comment: Internal Khaos Control ID for the customer. -->
		<!--Comment: This should not be used unless you already have this number from KC -->
		<COMPANY_ID/>
		<!--Comment: Company code / reference -->
		<!--Comment: Company Code will not update on import for an existing customer record. -->
		<COMPANY_CODE/>
		<!--Comment: Third party data import reference. When importing customers, this reference (which you can make up) will be returned in the results so that you can tie-up what you sent with what is returned -->
		<PASSED_REFERENCE/>
		<!--Comment: Company name (contact name if residential) -->
		<COMPANY_NAME/>
		<!--Comment: Associated reference code eg. website reference code or ID -->
		<OTHER_REF/>
		<!--Comment: Customer's web site URL-->
		<!--Limit: 50 characters-->
		<WEB_SITE/>
		<!--Comment: Company Classification eg. retail, mailorder, etc -->
		<!--Comment: Mandatory -->
		<!--Comment: Please note; when using the ImportCustomer method, the value populated in this tag will only be used when creating a new customer. When updating a customer record this value must still be populated but will not update Khaos Control if the value in the XML diffes to that in the Khaos Control database. -->
		<!--Comment: ENFORCE_SALES_MULTIPLE attribute is not mandatory and should only be used when the Customer requires this to be set on import. -->
		<COMPANY_CLASS ENFORCE_SALES_MULTIPLE="ABC" />
		<!--Comment: Company type -->
		<COMPANY_TYPE/>
		<!--Comment: Company status -->
		<COMPANY_STATUS>Active</COMPANY_STATUS>
		<!--Comment: Source eg "Advertising Campaign 2004" -->
		<!--Comment: Deprecated, please do not use! Use SOURCE_CODE instead -->
		<COMPANY_SOURCE/>
		<!--Comment: Source code is the code of a Keycode already set up within Khaos Control that you wish to record against the customer record as being the Keycode that initiated their creation. Omit or leave blank to set no source against the customer. -->
		<!--Comment: Please note that sources can only be set on import when a new customer is created. -->
		<SOURCE_CODE>KC001</SOURCE_CODE>
		<!--Comment: Date of creation -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<DATE_CREATED/>
		<!--Comment: Proforma customer -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<PROFORMA/>
		<!--Comment: Stop this customer having Sales Orders created against them -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<SORDER_LOCKED/>
		<!--Comment: Is supplier 0 = No, -1 = Yes -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<SUPPLIER>0</SUPPLIER>
		<!--Comment: EC Company 0 = No, -1 = Yes -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<EC_COMPANY>0</EC_COMPANY>
		<!--Comment: Pays VAT 0 = No, -1 = Yes -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<PAYS_VAT>0</PAYS_VAT>
		<!--Comment: Customer requires PO Code 0 = No, -1 = Yes -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<POCODE_REQUIRED>0</POCODE_REQUIRED>
		<!--Comment: Customer can earn and redeem reward points 0 = No, -1 = Yes -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<EARN_AND_REDEEM_REWARD_POINTS>0</EARN_AND_REDEEM_REWARD_POINTS>
		<!--Comment: Customer's current reward point balance -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<REWARD_POINT_BALANCE/>
		<!--Comment: Customer's current reward points update date -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<REWARD_POINTS_UPDATED/>
		<!--Comment: VAT Relief Qualified 0 = No, -1 = Yes -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<VAT_RELIEF_QUALIFIED/>
		<!--Comment: Website username. Can be used to store this data in Khaos Control so that this can be viewed and / or changed without using the website CMS. -->
		<WEB_USER/>
		<!--Comment: Website password. Can be used to store this data in Khaos Control so that this can be viewed and / or changed without using the website CMS. -->
		<WEB_PASSWORD/>
		<!--Comment: Agent associated with the Customer in Khaos Control. -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<AGENT_NAME/>
		<!--Comment: VAT no. -->
		<TAX_REFERENCE/>
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
		<MAILING_STATUS>3</MAILING_STATUS>
		<!--Comment: percentage discount this customer receives on their orders by default -->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<CUSTOMER_DISCOUNT>2.5</CUSTOMER_DISCOUNT>
		<!--Comment: Default Sales Source to stamp on orders created for the customer -->
		<SALE_SOURCE>USA</SALE_SOURCE>
		<!--Comment: Customer's default payment type, values are as follows:
				Cash
				Cheque
				Credit Card
				Account
				Voucher
				Direct Debit (DDI)                            
				BACS                                                     
		-->
		<!--Comment: This tag is IGNORED on Import, but is provided for information on Export. --> 
		<!--Comment: New customers created via ImportCompany will have their payment type set according to their Company Class -->
		<PAYMENT_TYPE>Credit Card</PAYMENT_TYPE>
		<!--Comment: Customer's currency -->
		<CURRENCY_CODE>GBP</CURRENCY_CODE>
		<!--Comment: Price List(s) that the customer is associated with -->
		<!--Comment: Please note, PRICE_LISTS are output by ExportCompany only, they are not supported by ImportCompany -->
		<PRICE_LISTS>
			<!--Comment: IS_NET defines whether the price list is net (-1) or gross (0) -->
			<!--Comment: CURRENCY_CODE defines the currency of the price list -->
			<!--Comment: PRICELIST_TYPE - empty or 0 = PriceList associated with Company without any prices defined on Promotions screen, -->
			<!--         1 = Company / Stock Item, 2 = Company / Stock Type, 3 = Price List / Stock Item, 4 = Price List / Stock Type -->
			<!--         Note that the same Price List entry may appear twice (ie when Price List is used both with Stock Items and Stock Types -->
			<!--Comment: Price List Name - see Price Lists XML for detail. In the case of price list types 1 and 2, it is Company URN -->
			<PRICE_LIST IS_NET="-1" CURRENCY_CODE="GBP" PRICELIST_TYPE="">Retailers</PRICE_LIST>
			<PRICE_LIST/>
		</PRICE_LISTS>
		<!--Comment: Flag for whether a Customer is hidden. ((-1) for hidden.) Please note - this is not currently used-->
		<HIDE_COMPANY>0</HIDE_COMPANY>
		<!--Comment: Flag for whether a Customer is held. ((-1) for held.) -->
		<HOLD_DATA>0</HOLD_DATA>
		<!--Comment: Customer's address(es) -->
		<ADDRESSES>
			<!--Comment: Customer's address -->
			<!--Comment: Tags within the ADDRESS tag are Mandatory unless stated otherwise -->
			<ADDRESS>
				<!--Comment: Address line 1 -->
				<ADDR1/>
				<!--Comment: Address line 2 (Optional) -->
				<ADDR2/>
				<!--Comment: Address line 3 (Optional) -->
				<ADDR3/>
				<!--Comment: Postal town -->
				<TOWN/>
				<!--Comment: County (Optional) -->
				<COUNTY/>
				<!--Comment: Postcode -->
				<POSTCODE/>
				<!--Comment: Organisation (Optional) -->
				<ORGANISATION/>
				<!--Comment: Longitude of the customer's address -->
				<!--Comment: Please note, this value is output by ExportCompany only and is not supported by ImportCompany -->				
				<LONGITUDE/>
				<!--Comment: Latitude of the customer's address -->
				<!--Comment: Please note, this value is output by ExportCompany only and is not supported by ImportCompany -->				
				<LATITUDE/>
				<!--Comment: Address Type: 1=Invoice, 2=Delivery -->
				<!--comment: If the customer only has one address, this MUST be set to Invoice -->
				<ADDRTYPE/>
				<!--Comment: Address email (can specify contact email below instead) -->
				<EMAIL/>
				<!--Comment: Address telephone number -->
				<!--Limit: 19 characters -->
				<TEL/>
				<!--Comment: Address fax -->
				<!--Limit: 19 characters -->
				<FAX/>
				<!--Comment: Address' Country -->
				<!--Comment: Either COUNTRY or COUNTRY_CODE (i.e. FR) must be specified. This supercedes earlier behaviour in the web service, where Khaos Control defaulted the country to GBR when not populated -->
				<COUNTRY/>
				<COUNTRY_CODE/>
				<!--Comment: Unique ID for address -->
				<ADDRESS_ID/>
				<!--Comment: Make Address Inactive -1=Yes 0=No -->
				<INACTIVE/>
				<!--Comment: Preferred delivery address:-1 for yes, 0 for no -->
				<!--Comment: Defaults to 0 if not specified -->
				<IS_PREFERRED_DELIVERY/>
				<!--Comment: Flag for whether an address is hidden. ((-1) for hidden.) Please note - this is not currently used-->
				<HIDE_ADDRESS>0</HIDE_ADDRESS>
				<!--Comment: Flag for whether an address is held. ((-1) for held.) -->
				<HOLD_DATA>0</HOLD_DATA>
				<!--Comment: Contacts defined against this address -->
				<CONTACTS>
					<!--Comment: Contact -->
					<CONTACT>
						<!--Comment: Title eg "Mr" -->
						<TITLE/>
						<!--Comment: Forename -->
						<FORENAME/>
						<!--Comment: Surname -->
						<SURNAME/>
						<!--Comment: Job title eg. "Sales manager" -->
						<JOBTITLE/>
						<!--Comment: Telephone -->
						<!--Limit: 19 characters -->
						<TEL/>
						<!--Comment: Fax number -->
						<!--Limit: 19 characters -->
						<FAX/>
						<!--Comment: Mobile number -->
						<!--Limit: 19 characters -->
						<MOBILE/>
						<!--Comment: Email address -->
						<EMAIL/>
						<!--Comment: Misc. notes/details -->
						<NOTE/>
						<!--Comment: EmailSubscribe: -1 for yes, 0 for no -->
						<EMAILSUBSCRIBE>-1</EMAILSUBSCRIBE>
						<!--Comment: Date of birth: yyyy-mm-dd -->
						<DOB/>
						<!--Comment: Unique ID for contact -->
						<CONTACT_ID/>
						<!--Comment: Make Contact Inactive -1=Yes 0=No -->
						<INACTIVE/>
						<!--Comment: Preferred Communication Method -->
						<!--Comment: Please note, this value is output by ExportCompany only and is not supported by ImportCompany -->		
						<PREFERRED_COMMUNICATION_METHOD/>
						<!--Comment: Preferred contact:-1 for yes, 0 for no -->
						<!--Comment: Defaults to 0 if not specified -->
						<IS_PREFERRED/>
						<!--Comment: Preferred invoice contact:-1 for yes, 0 for no -->
						<!--Comment: Defaults to 0 if not specified -->
						<IS_PREFERRED_INVOICE/>
						<!--Comment: Preferred delivery contact:-1 for yes, 0 for no -->
						<!--Comment: Defaults to 0 if not specified -->
						<IS_PREFERRED_DELIVERY/>
						<!--Comment: Flag for whether a contact is hidden. ((-1) for hidden.) Please note - this is not currently used. -->
						<HIDE_CONTACT>0</HIDE_CONTACT>
						<!--Comment: Flag for whether a contact is held. ((-1) for held.) -->
						<HOLD_DATA>0</HOLD_DATA>
					</CONTACT>
					<CONTACT>
						<TITLE/>
						<FORENAME/>
						<SURNAME/>
						<JOBTITLE/>
						<TEL/>
						<FAX/>
						<MOBILE/>
						<EMAIL/>
						<NOTE/>
						<EMAILSUBSCRIBE>-1</EMAILSUBSCRIBE>
						<DOB/>
						<CONTACT_ID/>
						<INACTIVE>0</INACTIVE>
						<PREFERRED_COMMUNICATION_METHOD/>
					</CONTACT>
				</CONTACTS>
			</ADDRESS>
			<ADDRESS>
				<ADDR1/>
				<ADDR2/>
				<ADDR3/>
				<TOWN/>
				<COUNTY/>
				<POSTCODE/>
				<ORGANISATION/>
				<LONGITUDE/>
				<LATITUDE/>
				<ADDRTYPE/>
				<EMAIL/>
				<TEL/>
				<FAX/>
				<COUNTRY/>
				<COUNTRY_CODE/>
				<ADDRESS_ID/>
				<INACTIVE>0</INACTIVE>
				<CONTACTS>
					<CONTACT>
						<TITLE/>
						<FORENAME/>
						<SURNAME/>
						<JOBTITLE/>
						<TEL/>
						<FAX/>
						<MOBILE/>
						<EMAIL/>
						<NOTE/>
						<EMAILSUBSCRIBE>-1</EMAILSUBSCRIBE>
						<DOB/>
						<CONTACT_ID/>
						<INACTIVE>0</INACTIVE>
						<PREFERRED_COMMUNICATION_METHOD/>
					</CONTACT>
					<CONTACT>
						<TITLE/>
						<FORENAME/>
						<SURNAME/>
						<JOBTITLE/>
						<TEL/>
						<FAX/>
						<MOBILE/>
						<EMAIL/>
						<NOTE/>
						<EMAILSUBSCRIBE>-1</EMAILSUBSCRIBE>
						<DOB/>
						<CONTACT_ID/>
						<INACTIVE>0</INACTIVE>
						<PREFERRED_COMMUNICATION_METHOD/>
					</CONTACT>
				</CONTACTS>
			</ADDRESS>
		</ADDRESSES>
		<!--Comment: Customer's Country -->
		<!--Comment: Either COUNTRY or COUNTRY_CODE (i.e. DE) must be specified. This supercedes earlier behaviour in the web service, where Khaos Control defaulted the country to GBR when not populated -->
		<COUNTRY/>
		<COUNTRY_CODE/>
		<!--Comment: User defined properties follow -->
		<!--Comment: Customer UDA values. The 'type' of UDA is defined by the parameter. These will be unique in every instance of Khaos Control. -->
		<ADDITIONAL NAME="Security Question">Mother's Maiden Name</ADDITIONAL>
		<ADDITIONAL NAME="Favourite Colour">Salmon Pink</ADDITIONAL>
		<!--Comment: Printing / Emailing of documents. These tags control whether the customer has the always print option ticked for this email document; -1 = Always Print, 0 = Email When Possible -->
		<EMAIL_OPTION NAME="Statement">-1</EMAIL_OPTION>
		<EMAIL_OPTION NAME="Invoice">0</EMAIL_OPTION>
	</COMPANY>
	<COMPANY>
		<COMPANY_CODE/>
		<COMPANY_NAME/>
		<OTHER_REF/>
		<WEB_SITE/>
		<COMPANY_CLASS ENFORCE_SALES_MULTIPLE="ABC" />
		<COMPANY_TYPE/>
		<COMPANY_STATUS>Active</COMPANY_STATUS>
		<COMPANY_SOURCE/>
		<SOURCE_CODE>KC001</SOURCE_CODE>
		<DATE_CREATED/>
		<COMPANY_ID/>
		<PROFORMA/>
		<SORDER_LOCKED/>
		<SUPPLIER>0</SUPPLIER>
		<EC_COMPANY>0</EC_COMPANY>
		<PAYS_VAT>0</PAYS_VAT>
		<POCODE_REQUIRED>0</POCODE_REQUIRED>
		<EARN_AND_REDEEM_REWARD_POINTS>0</EARN_AND_REDEEM_REWARD_POINTS>
		<REWARD_POINT_BALANCE/>
		<REWARD_POINTS_UPDATED/>
		<WEB_USER/>
		<WEB_PASSWORD/>
		<AGENT_NAME/>
		<TAX_REFERENCE/>
		<MAILING_STATUS>3</MAILING_STATUS>
		<CUSTOMER_DISCOUNT>2.5</CUSTOMER_DISCOUNT>
		<SALE_SOURCE>USA</SALE_SOURCE>
		<PAYMENT_TYPE>Account</PAYMENT_TYPE>
		<CURRENCY_CODE>GBP</CURRENCY_CODE>
		<PRICE_LISTS>
			<PRICE_LIST IS_NET="-1" CURRENCY_CODE="GBP">Retailers</PRICE_LIST>
			<PRICE_LIST IS_NET="-1" CURRENCY_CODE="GBP">Retailers</PRICE_LIST>
		</PRICE_LISTS>
		<ADDRESSES>
			<ADDRESS>
				<ADDR1/>
				<ADDR2/>
				<ADDR3/>
				<TOWN/>
				<COUNTY/>
				<POSTCODE/>
				<ORGANISATION/>
				<LONGITUDE/>
				<LATITUDE/>
				<ADDRTYPE/>
				<EMAIL/>
				<TEL/>
				<FAX/>
				<COUNTRY/>
				<COUNTRY_CODE/>
				<ADDRESS_ID/>
				<INACTIVE>0</INACTIVE>
				<CONTACTS>
					<CONTACT>
						<TITLE/>
						<FORENAME/>
						<SURNAME/>
						<JOBTITLE/>
						<TEL/>
						<FAX/>
						<MOBILE/>
						<EMAIL/>
						<NOTE/>
						<EMAILSUBSCRIBE>-1</EMAILSUBSCRIBE>
						<DOB/>
						<CONTACT_ID/>
						<INACTIVE>0</INACTIVE>
						<PREFERRED_COMMUNICATION_METHOD/>
					</CONTACT>
					<CONTACT>
						<TITLE/>
						<FORENAME/>
						<SURNAME/>
						<JOBTITLE/>
						<TEL/>
						<FAX/>
						<MOBILE/>
						<EMAIL/>
						<NOTE/>
						<EMAILSUBSCRIBE>-1</EMAILSUBSCRIBE>
						<DOB/>
						<CONTACT_ID/>
						<INACTIVE>0</INACTIVE>
						<PREFERRED_COMMUNICATION_METHOD/>
					</CONTACT>
				</CONTACTS>
			</ADDRESS>
			<ADDRESS>
				<ADDR1/>
				<ADDR2/>
				<ADDR3/>
				<TOWN/>
				<COUNTY/>
				<POSTCODE/>
				<ORGANISATION/>
				<LONGITUDE/>
				<LATITUDE/>
				<ADDRTYPE/>
				<EMAIL/>
				<TEL/>
				<FAX/>
				<COUNTRY/>
				<COUNTRY_CODE/>
				<ADDRESS_ID/>
				<INACTIVE>0</INACTIVE>
				<CONTACTS>
					<CONTACT>
						<TITLE/>
						<FORENAME/>
						<SURNAME/>
						<JOBTITLE/>
						<TEL/>
						<FAX/>
						<MOBILE/>
						<EMAIL/>
						<NOTE/>
						<EMAILSUBSCRIBE>-1</EMAILSUBSCRIBE>
						<DOB/>
						<CONTACT_ID/>
						<INACTIVE>0</INACTIVE>
						<PREFERRED_COMMUNICATION_METHOD/>
					</CONTACT>
					<CONTACT>
						<TITLE/>
						<FORENAME/>
						<SURNAME/>
						<JOBTITLE/>
						<TEL/>
						<FAX/>
						<MOBILE/>
						<EMAIL/>
						<NOTE/>
						<EMAILSUBSCRIBE>-1</EMAILSUBSCRIBE>
						<DOB/>
						<CONTACT_ID/>
						<INACTIVE>0</INACTIVE>
						<PREFERRED_COMMUNICATION_METHOD/>
					</CONTACT>
				</CONTACTS>
			</ADDRESS>
		</ADDRESSES>
		<COUNTRY/>
		<COUNTRY_CODE/>
		<ADDITIONAL NAME="Security Question">Mother's Maiden Name</ADDITIONAL>
		<ADDITIONAL NAME="Favourite Colour">Salmon Pink</ADDITIONAL>
		<EMAIL_OPTION NAME="Statement">-1</EMAIL_OPTION>
		<EMAIL_OPTION NAME="Invoice">0</EMAIL_OPTION>
	</COMPANY>
</COMPANYS>