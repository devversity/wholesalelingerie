<CATREQUESTS>
	<REQUEST>
		<!-- Note same customer detail format as for orders -->
		<CUSTOMER_DETAIL>
			<!--Comment: -1 = True, 0 = False-->
			<!--Comment: If false, update customer details from values (matching on COMPANY_CODE) instead of creating new-->
			<IS_NEW_CUSTOMER>0</IS_NEW_CUSTOMER>
			<!--Limit: 10 characters-->
			<COMPANY_CODE>KCS001</COMPANY_CODE>
			<!--Comment: Specify a company class from KhaosControl, Only pre-existing classes will be mapped onto the customer record. Case Sensitive. -->
			<COMPANY_CLASS>Internet</COMPANY_CLASS>
			<!--Limit: 50 characters-->
			<COMPANY_NAME>Khaos Control Solutions Ltd</COMPANY_NAME>
			<!--Comment: Company Source Code. This set the new customers source code. -->
			<!--Comment: It will also set the catalogue requests keycode, if the catalogue tag is not specified or does not have any associated keycode configured in KhaosControl.-->
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
			<MAILING_STATUS>3</MAILING_STATUS>
			<!--Comment: DO NOT USE for GDPR compliance-->
			<OPTIN_NEWSLETTER/>
			<!-- VAT no. -->
			<!--Limit: 20 characters-->
			<TAX_REFERENCE/>
			<!-- User defined properties -->
			<ADDITIONAL NAME="Security Question">Mother's Maiden Name</ADDITIONAL>
			<ADDITIONAL NAME="Favourite Colour">Salmon Pink</ADDITIONAL>
			<!-- In addresses, the ADDRESS1, TOWN, POSTCODE, and contact (TITLE/FORENAME/SURNAME) fields should be set -->
			<!-- Other fields are optional but are recommended, particularly COUNTRY_CODE -->
			<ADDRESSES>
				<INVADDR>
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
					<!--Comment: If both Country fields are blank the system default will be assumed ie. GBR, United Kingdom. Country Code is used if available. Country Name is only used if Code is blank. -->
					<ICOUNTRY_CODE/>
					<ICOUNTRY_NAME/>
				</INVADDR>
				<!--Comment: If empty delivery address deatils then use same address as above-->
				<DELADDR>
					<!--Limit: as above-->
					<DADDRESS1/>
					<DADDRESS2/>
					<DADDRESS3/>
					<DTOWN/>
					<DCOUNTY/>
					<DPOSTCODE/>
					<DTITLE/>
					<DFORENAME/>
					<DSURNAME/>
					<DTEL/>
					<DFAX/>
					<DMOBILE/>
					<DEMAIL/>
					<!--Comment: If both Country fields are blank the system default will be assumed ie. GBR, United Kingdom. Country Code is used if available. Country Name is only used if Code is blank. -->
					<DCOUNTRY_CODE/>
					<DCOUNTRY_NAME/>
				</DELADDR>
			</ADDRESSES>
		</CUSTOMER_DETAIL>
		<!-- Date of request yyyy-mm-dd -->
		<REQUEST_DATE/>
		<!-- Which catalogue to request (optional)  -->
		<CATALOGUE/>
	</REQUEST>
	<!-- Further requests follow -->
	<REQUEST/>
</CATREQUESTS>