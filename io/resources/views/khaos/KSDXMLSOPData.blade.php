<SOPDATA>
	<COURIERS>
		<COURIER CODE="ABCD">Royal Mail Standard</COURIER>
		<!-- Comment: Courier Name. -->
		<!-- Comment: If a code has been specified for the courier in Khaos Control this will be included in the CODE property -->
		<COURIER CODE="1234">UPS Standard</COURIER>
	</COURIERS>
	<!-- Comment: Couriers exported from [Khaos Control | System Data | Couriers] -->
	<!-- Comment: This data can be used to specify a specific Courier Name or Courier Code when creating a Sales Order on the website -->
	<DELRATES>
		<RATE>
			<COUNTRY_CODE>GBR</COUNTRY_CODE>
			<!-- Comment: Country Code associated with Delivery Rate. If not populated then Delivery Rate is applicable to all countries -->
			<ZONE>Zone 1</ZONE>
			<!-- Comment: Zone associated with Delivery Rate. Zones are configured in [ System Data | Countries ]. If not populated then Delivery Rate is applicable to all zones  -->
			<POSTCODES>CH43,CH44</POSTCODES>
			<!-- Comment: Postcodes associated with Delivery Rate. If not populated then Delivery Rate is applicable to all Delivery Addresses -->
			<COURIER_CODE>ABCD</COURIER_CODE>
			<!-- Comment: Courier that the Delivery Rate is associated with -->
			<COURIER_NAME>ABCD</COURIER_NAME>
			<COMPANY_CLASS>Retail</COMPANY_CLASS>
			<!-- Comment: Company Class that the Delivery Rate is associated with. If not populated, then Delivery Rate is applicable to customers in all Company Classes -->
			<WEIGHT LOW="100.1" HIGH="200.5">?</WEIGHT>
			<!-- Comment: Weight banding applicable to the Delivery Rate. Not mandatory or generally relevant to website orders. Contact KCS if considering using. -->
			<VALUE LOW="50.50" HIGH="75.20">?</VALUE>
			<!-- Comment: Order value banding applicable to the Delivery Rate. Whether this a Gross or Net figure is controlled by the GROSS property against the <POSTAGE /> tag that follows -->
			<POSTAGE GROSS="-1" VAT="-1" PERKG="100.10" ISKG="0">15.00</POSTAGE>
			<!-- Comment: Delivery Amount to be charged when this rate is applicable to the sales order -->
			<!-- Comment: GROSS property defines whether the order value banding is for the Gross or Net Order total -->
			<!-- Comment: VAT property defines whether the Delivery Amount is vatable or not -->
			<!-- Comment: PERKG property is only applicable when the delivery rate is applied in Khaos Control -->
			<!-- Comment: ISKG property is only applicable when the delivery rate is applied in Khaos Control -->
			<PRIORITY>1</PRIORITY>
			<!-- Comment: The priority this delivery rate has over other exported rates where 1 is high and 10 is low -->
			<CURRENCY_DESC>Pounds Sterling</CURRENCY_DESC>
			<!-- Comment: The currency applicable to this delivery rate. If not defined, then default to GBP / Pounds Sterling -->
			<BRAND/>
			<!-- Comment: The applicable brand associated with this delivery rate. -->
			<INVPRIORITY_ID>12</INVPRIORITY_ID>
			<!-- Comment: Invoice Priority applicable to this delivery rate -->
		</RATE>
		<RATE>
			<!-- Comment: Next del rate -->
		</RATE>
	</DELRATES>
	<!-- Comment: Delivery Rates exported from [Khaos Control | System Data | Delivery Rates] -->
	<!-- Comment: This data can be used to define the specific Delivery Amount when creating a Sales Order on the website -->
	<COURIERBANDING>
		<BANDING>
			<WEIGHT LOW="100.5" HIGH="200.75" />
			<!-- Comment: Weight range where sales order should be assigned to courier defined against this band. Contact KCS if considering using -->
			<ZONE>Zone 7</ZONE>
			<!-- Comment: Zone this band applies to. Not mandatory -->
			<COURIER>ABCD</COURIER>
			<!-- Comment: Name of the Courier that should be set against the sales orders that fall inside this courier band -->
			<COURIER_NAME>ABCD</COURIER_NAME>
		</BANDING>
		<BANDING>
			<WEIGHT LOW="100.5" HIGH="200.75" />
			<ZONE>Zone 7</ZONE>
			<COURIER>ABCD</COURIER>
			<COURIER_NAME>ABCD</COURIER_NAME>
		</BANDING>
	</COURIERBANDING>
	<!-- Comment: Courier Banding exported from [Khaos Control | System Data | Courier Banding] -->
	<!-- Comment: This data can be used to select the appropriate courier to use when creating a Sales Order on the website, when there are multiple options that the customer is not selecting from manually -->
	<KEYCODES>
		<KEYCODE ID="KEYCODE_ID">
			<CODE>Keycode</CODE>
			<!-- Comment: Keycode's code -->
			<DESCRIPTION>Description</DESCRIPTION>
			<!-- Comment: Keycode's description -->
			<SOURCE_CODE>-1</SOURCE_CODE>
			<!-- Comment: Defines whether or not the Keycode should be used when creating new customers -->
			<!-- Comment: -1=True, 0=False -->
			<ADDITIVE_DISCOUNT>-1</ADDITIVE_DISCOUNT>
			<!-- Comment: Defines whether the discount associated with the keycode should be added to other discounts on the order OR override them all -->
			<!-- Comment: -1=True, 0=False -->
			<COMPANY_CLASS>Mailorder</COMPANY_CLASS>
			<!-- Comment: If populated, the Keycode can only be used for this Company Class. Not mandatory -->
			<PRICE_LIST>Mailorder</PRICE_LIST>
			<!-- Comment: If populated, the price list that is appliable to any sales orders created using the Keycode. Not mandatory -->
			<KEYCODE_DATE>2006-01-01</KEYCODE_DATE>
			<!-- Comment: Manual date set against the Keycode by the user. Generally the date created -->
			<EXPIRY_DATE>2007-01-01</EXPIRY_DATE>
			<!-- Comment: The date on which the Keycode expires. Not mandatory -->
			<WEB_ONLY>-1</WEB_ONLY>
			<!-- DEPRACATED - use WEB_USE and MOTO_USE tags instead now -->
			<!-- Comment: Defines whether or not the Keycode should only be applied to web orders -->
			<!-- Comment: -1=True, 0=False -->
			<WEB_USE>-1</WEB_USE>
			<MOTO_USE>-1</MOTO_USE>
			<REWARD_POINT_MULTIPLIER>1.123</REWARD_POINT_MULTIPLIER>
			<GLOBAL_REWARD_POINTS>-1</GLOBAL_REWARD_POINTS>
			<DISCOUNTS>
				<DISCOUNT ORDERLOW="0.49" ORDERHIGH="0.99" PCDISCOUNT="40.4" NETDELIVERY="5.99" DELACTIVE="0">?</DISCOUNT>
				<!-- Comment: Specific discount for the Keycode. This is not mandatory, however there may be more than one Discount band per Keycode-->
				<!-- Comment: ORDERLOW property defines the low gross value of the sales order to qualify for the discounts / delivery charge on this band -->
				<!-- Comment: ORDERHIGH property defines the high gross value of the sales order to qualify for the discounts / delivery charge on this band -->
				<!-- Comment: PCDISCOUNT property defines the percentage discount to be applied to the sales order -->
				<!-- Comment: NETDELIVERY property defines the net delivery amount to be applied to the sales order -->
				<!-- Comment: DELACTIVE properly defines whether or not the delivery amount should be applied to the sales order-->
				<DISCOUNT ORDERLOW="0.99" ORDERHIGH="10.99" PCDISCOUNT="50.5" NETDELIVERY="10.99" DELACTIVE="0">?</DISCOUNT>
			</DISCOUNTS>
			<!-- Comment: Discounts and Reduced Delivery Charges that the Keycode qualifies the sales order for -->
			<FREEITEMS>
				<ITEM ORDERLOW="0.99" ORDERHIGH="10.99" QTY="0.5" AMOUNT="10.99" AUTOADD="-1" DISCOUNT="5" REWARD_POINT="100">STOCKCODE</ITEM>
				<!-- Comment: Specific free / reduced price stock item for the Keycode. This is not mandatory, however there may be more than one item per Keycode-->
				<!-- Comment: ORDERLOW property defines the low gross value of the sales order to qualify for the special price -->
				<!-- Comment: ORDERHIGH property defines the high gross value of the sales order to qualify for the special price -->
				<!-- Comment: QTY property defines how much of this item to add to the order when the special price triggers -->
				<!-- Comment: AMOUNT property defines how much this to sell this item for when the conditions are met. This can be zero -->
				<ITEM ORDERLOW="0" ORDERHIGH="0" QTY="0.5" AMOUNT="10.99" AUTOADD="-1" DISCOUNT="7" REWARD_POINT="100">STOCKCODE</ITEM>
			</FREEITEMS>
			<!-- Comment: Specific free or reduced price items when the Keycode is active -->
		</KEYCODE>
		<KEYCODE>
			<!-- Comment: next keycode -->
		</KEYCODE>
	</KEYCODES>
	<!-- Comment: Keycodes exported from [Khaos Control | Promotions | Key Code] -->
	<!-- Comment: For detailed information on Keycodes and how they are used in Khaos Control, navigate to http://support.khaoscontrol.com/wiki_kcx/index.php?title=Promotion_Keycodes_Tab -->
	<BOGOFS>
		<BOGOF ID="BOGOF_ID">
			<DESCRIPTION>Foo</DESCRIPTION>
			<!-- Comment: BOGOF description -->
			<ACTIVE DATE_FROM="2000-01-01" DATE_TO="2000-12-31">0</ACTIVE>
			<!-- Comment: Defines whether or not the BOGOF is always active or not -->
			<!-- Comment: -1=True, 0=False -->
			<!-- Comment: DATE_FROM and DATE_TO properties will be populated when set against the BOGOF. If the BOGOF has been configured with a date range within which it should apply this should only be used when <ACTIVE /> is set to '0' -->
			<KEYCODE>Code</KEYCODE>
			<BOGOF_KEYCODES>
				<KEYCODE>Code</KEYCODE>
				<KEYCODE>Code</KEYCODE>
			</BOGOF_KEYCODES>
			<!-- Comment: The Keycode that triggers the BOGOF. When specified, only sales orders that have this Keycode stamped against them should have the BOGOF applied to them. Not mandatory -->
			<WEB_USE>-1</WEB_USE>
			<MOTO_USE>-1</MOTO_USE>
			<TRIGGERS>
				<TRIGGER>
					<STOCK STOCK_CODE="CODE" STOCK_TYPE="TYPE">?</STOCK>
					<!-- Comment: The trigger for the BOGOF. This will either be a Stock Code or a Stock Type, as defined in the Properties. If you are intending to use Stock Type as the trigger, please contact KCS in advance -->
					<COMPANY COMPANY_CODE="CODE" COMPANY_CLASS="CLASS">?</COMPANY>
					<!-- Comment: The specific Company of Company Class that the BOGOF is limited to, as defined in the Properties. This is not mandatory. If you are intending to use Company Class, please contact KCS in advance-->
					<QTY>2</QTY>
					<!-- Comment: Nunber of items that have be on the sales order in order for it to qualify for the BOGOF -->
					<UNIT_PRICE>23.49</UNIT_PRICE>
					<!-- Comment: The item price for the items that must be paid in order for the order to qualify for the BOGOF. If zero then the price trigger is not applicable -->
					<SINGLE_LINE>0</SINGLE_LINE>
					<!-- Comment: Defines whether there is one or more offer items associated with the trigger -->
					<!-- Comment: -1=True, 0=False -->
					<OFFERS>
						<OFFER>
							<STOCK_CODE>Code</STOCK_CODE>
							<!-- Comment: The offer item's stock code -->
							<QTY>34</QTY>
							<!-- Comment: The number of items to be offered (either given away free or at reduced price)	-->
							<!-- Comment: The customer does NOT qualify for the BOGOF offer until they have added the ENTIRE quantity specified of the offer item onto the sales order -->
							<DISCOUNT>33.3</DISCOUNT>
							<!-- Comment: The discount percentage to be applied to the item's price. i.e. 100% = free, 50% = half price -->
						</OFFER>
						<OFFER>
							<STOCK_CODE>Code</STOCK_CODE>
							<QTY>34</QTY>
							<DISCOUNT>33.3</DISCOUNT>
						</OFFER>
					</OFFERS>
					<!-- Comment: The associated offer items for the trigger item -->
				</TRIGGER>
				<TRIGGER>
					<STOCK STOCK_CODE="CODE" STOCK_TYPE="TYPE">?</STOCK>
					<COMPANY COMPANY_CODE="CODE" COMPANY_CLASS="CLASS">?</COMPANY>
					<QTY>2</QTY>
					<UNIT_PRICE>23.49</UNIT_PRICE>
					<SINGLE_LINE>0</SINGLE_LINE>
					<OFFERS>
						<OFFER>
							<STOCK_CODE>Code</STOCK_CODE>
							<QTY>34</QTY>
							<DISCOUNT>33.3</DISCOUNT>
						</OFFER>
						<OFFER>
							<STOCK_CODE>Code</STOCK_CODE>
							<QTY>34</QTY>
							<DISCOUNT>33.3</DISCOUNT>
						</OFFER>
					</OFFERS>
					<!-- Comment: The associated offer items for the trigger item -->
				</TRIGGER>
			</TRIGGERS>
			<!-- Comment: The trigger and offer items that the BOGOF is comprised of -->
		</BOGOF>
		<BOGOF>
			<!-- Comment: Next BOGOF -->
		</BOGOF>
	</BOGOFS>
	<!-- Comment: BOGOFs exported from [Khaos Control | Promotions | B.O.G.O.F] -->
	<!-- Comment: For detailed information on BOGOFs and how they are used in Khaos Control, navigate to http://support.khaoscontrol.com/wiki_kcx/index.php?title=Promotion_BOGOF_Tab -->
	<SPECIAL_OFFERS>
		<SPECIAL_OFFER>
			<QTY/>
			<!-- Comment: Quantity to be added to the sales order by default -->
			<SELL_PRICE IS_NET="-1">10.99</SELL_PRICE>
			<!-- Comment: Special Offer price for the stock item -->
			<ORDER_TOTAL>29.99</ORDER_TOTAL>
			<!-- Comment: The Sales Order value trigger for the Special Offer. Can be zero, in which case the Special Offer applies to every active Special Offer -->
			<START_DATE>2005-01-01</START_DATE>
			<!-- Comment: The date on which the Special Offer starts -->
			<END_DATE>2007-01-01</END_DATE>
			<!-- Comment: The date on which the Special Offer ends -->
			<ACTIVE/>
			<!-- Comment: Defines whether or not the Special Offer is active -->
			<!-- Comment: -1=True, 0=False -->
			<STOCK_CODE>Code</STOCK_CODE>
			<!-- Comment: The Special Offer item's stock code -->
			<EXTENDED>
				<DESC>This is a description line!</DESC>
				<!-- Comment: An extended description line for the Special Offer item -->
				<DESC>This is another one</DESC>
				<!-- Comment: A further description line for the Special Offer item -->
			</EXTENDED>
			<!-- Comment: The extended stock description for the Special Offer item. This is specific to the offer and is not mandatory -->
			<BRAND>Brand Name</BRAND>
			<!-- Comment: The Brand that the Special Offer applies to. Not mandatory. Please contact KCS in advance if you are intending to use this option -->
			<WEB_USE>-1</WEB_USE>
			<MOTO_USE>-1</MOTO_USE>
			<COMPANY_CLASS>Company Class</COMPANY_CLASS>
			<!-- Comment: The Company Class that the Special Offer applies to. Not mandatory. Please contact KCS in advance if you are intending to use this option -->
		</SPECIAL_OFFER>
		<SPECIAL_OFFER>
			<!-- Comment: Next special offer -->
		</SPECIAL_OFFER>
	</SPECIAL_OFFERS>
	<!-- Comment: Special Offers exported from [Khaos Control | Promotions | Special Offers] -->
	<!-- Comment: For details information on Special Offers and how they are used in Khaos Control, navigate to http://support.khaoscontrol.com/wiki_kcx/index.php?title=Promotion_Special_Offers_Tab -->
	<TELESALE_PROMPTS>
		<TELESALE_PROMPT ID="ABC">
			<SHORT_DESC>Short description</SHORT_DESC>
			<!-- Comment: The Short Description for the Telesales Prompt -->
			<LONG_DESC>Long description</LONG_DESC>
			<!-- Comment: The Long Description for the Telesales Prompt -->
			<!-- Triggers start -->
			<DATE_START>2009-01-01</DATE_START>
			<!-- Comment: The date that the Telesales Prompt is active from -->
			<DATE_END>2009-12-31</DATE_END>
			<!-- Comment: The date that the Telesales Prompt is active to -->
			<ALWAYS_ACTIVE>-1</ALWAYS_ACTIVE>
			<!-- Comment: Defines whether or not the Telesales Prompt is always active -->
			<!-- Comment: -1=True, 0=False. When set to -1 then the Start and End Dates are ignored -->
			<COMPANY_CLASS>COMPANY_CLASS</COMPANY_CLASS>
			<!-- Comment: The Company Class that the Telesales Prompt will apply to. Not Mandatory. Please contact KCS in advance if you are intending to use this option -->
			<COMPANY_TYPE>COMPANY_TYPE</COMPANY_TYPE>
			<!-- Comment: The Company Type that the Telesales Prompt will apply to. Not Mandatory. Please contact KCS in advance if you are intending to use this option -->
			<TELESALE_KEYCODE CODE="ABC">Keycode description</TELESALE_KEYCODE>
			<!-- Comment: The description of the Keycode required to initiate the Telesales Prompt. Not mandatory -->
			<!-- Comment: CODE property defines the Code of the Keycode that will initiate the Telesales Prompt -->
			<APPLY_KEYCODE CODE="ABC">Keycode description</APPLY_KEYCODE>
			<!-- Comment: The description of the Keycode that will be applied to the sales order that has triggered the Telesales Prompt. Not mandatory -->
			<!-- Comment: CODE property defines the Code of the Keycode that will be applied to the sales order -->
			<ORDER_VALUE>20.45</ORDER_VALUE>
			<!-- Comment: Minimum order value that will initiate the Telesales Prompt. May be set to zero -->
			<ORDER_WEIGHT>1.34</ORDER_WEIGHT>
			<!-- Comment: The order weight that will initiate the Telesales Prompt. Not mandatory, or generally relevant to websites. Please contact KCS in advance if you are intending to use this option -->
			<NEW_ONLY>-1</NEW_ONLY>
			<!-- Comment: Defines whether the Telesales Prompt will initiate for New sales orders only, or all sales orders -->
			<!-- Comment: -1=True, 0=False -->
			<TELESALE_ITEMS>
				<TELESALE_ITEM STOCK_CODE="ABC" OTHER_REF="ABC" STOCK_TYPE="TYPE" QTY="20.4">A</TELESALE_ITEM>
				<!-- Comment: A trigger stock item or stock type for the Telesales Prompt -->
				<!-- Comment: STOCK_CODE property defines the stock item which should trigger the Telesales Prompt -->
				<!-- Comment: OTHER_REF property also defines the stock item which should trigger the Telesales Prompt -->
				<!-- Comment: STOCK_TYPE property defines the stock type which should trigger the Telesales Prompt. Please contact KCS in advance if you are intending to use this option -->
				<!-- Comment: Either the STOCK_CODE and OTHER_REF properties in the above tag will be populated OR the STOCK_TYPE. Never both -->
				<!-- Comment: The number of items of the specific stock item or stock type that are required on the sales order to trigger the Telesales Prompt -->
				<TELESALE_ITEM STOCK_CODE="ABC" OTHER_REF="ABC" STOCK_TYPE="TYPE" QTY="20.4">A</TELESALE_ITEM>
				<!-- Comment: More trigger stock items / types for the Telesales Prompt -->
			</TELESALE_ITEMS>
			<!-- Comment: The items (or stock types), if any, which should initiate the Telesales Prompt when they are added to a Sales Order. Not mandatory -->
			<COMPANY_CLASSES>
				<COMPANY_CLASS>Company class</COMPANY_CLASS>
				<!-- Comment: The Company Class that the Telesales Prompt should apply to. Please contact KCS in advance if you are intending to use this option -->
				<COMPANY_CLASS>Company class</COMPANY_CLASS>
				<!-- Comment: More Company Classes that the Telesales Prompt should apply to -->
			</COMPANY_CLASSES>
			<!-- Comment: The Company Classes that the Telesales Prompt should apply to. This is not mandatory -->
			<!-- Comment: If no Company Classes are passed, then the Telesales Prompt should apply to all customers who trigger it -->
			<COMPANY_TYPES>
				<COMPANY_TYPE>Company type</COMPANY_TYPE>
				<!-- Comment: The Company Type that the Telesales Prompt should apply to. Please contact KCS in advance if you are intending to use this option -->
				<COMPANY_TYPE>Company type</COMPANY_TYPE>
				<!-- Comment: More Company Types that the Telesales Prompt should apply to -->
			</COMPANY_TYPES>
			<!-- Comment: The Company Types that the Telesales Prompt should apply to. This is not mandatory -->
			<!-- Comment: If no Company Types are passed, then the Telesales Prompt should apply to all customers who trigger it -->
			<COUNTRIES>
				<COUNTRY CODE2="AB" CODE3="ABC">Country</COUNTRY>
				<!-- Comment: The Names of the Country that the Telesales Prompt should apply to. Please contact KCS in advance if you are intending to use this option -->
				<!-- Comment: CODE2 property defines the two-letter country code -->
				<!-- Comment: CODE3 property defines the three-letter country code -->
				<COUNTRY CODE2="AB" CODE3="ABC">Country</COUNTRY>
				<!-- Comment: More Countries that the Telesales Prompt should apply to -->
			</COUNTRIES>
			<!-- Comment: The Countries that the Telesales Prompt should apply to. This is not mandatory -->
			<!-- Comment: If no Countries are passed, then the Telesales Prompt should apply to all customers who trigger it -->
			<CURRENCIES>
				<CURRENCY CODE="ABC">Currency</CURRENCY>
				<!-- Comment: The Name of the Currency that the Telesales Prompt should apply to. Please contact KCS in advance if you are intending to use this option -->
				<!-- Comment: CODE property defines the currency code -->
				<CURRENCY CODE="ABC">Currency</CURRENCY>
				<!-- Comment: More Currencies that the Telesales Prompt should apply to -->
			</CURRENCIES>
			<!-- Comment: The Currencies that the Telesales Prompt should apply to. This is not mandatory -->
			<!-- Comment: If no Currencies are passed, then the Telesales Prompt should apply to all customers who trigger it -->
			<!-- Triggers end -->
			<ACTION>
				<APPLY_ACTION>-1</APPLY_ACTION>
				<!-- Is action active. -1 = True, 0 = False -->
				<SALE_PRICE>25.95</SALE_PRICE>
				<!-- Unit price to set -->
				<DISCOUNT>45.50</DISCOUNT>
				<!-- Discount to set -->
				<QTY_LIMIT>15</QTY_LIMIT>
				<!-- Limit discount to max qty -->
				<BOGOF_QTY>15</BOGOF_QTY>
				<!-- Limit discount sets of x number of items - e.g. for every 3 bought, apply discount -->
				<IS_NET>-1</IS_NET>
				<!-- SALE_PRICE is applied net -1 or gross 0 unit -->
				<NET_DELIVERY>3.99</NET_DELIVERY>
				<!-- Apply this delivery amount -->
				<ZERO_DELIVERY>-1</ZERO_DELIVERY>
				<!-- Delivery is should be zero and not overriden elsewhere in the system -->
			</ACTION>
			<!-- If triggered, this telesale rule should apply an action if APPLY_ACTION is -1 -->
			<!-- SALE_PRICE is the line unit price and is net determined by IS_NET. DISCOUNT is a line discount -->
			<!-- NET_DELIVERY is used against the sales order header -->
			<!-- The action could be applied as a result of the below conditions, but if there are no conditions then it will still apply -->
			<CONDITION_GROUP_TYPE>3</CONDITION_GROUP_TYPE>
			<CONDITION_GROUP_ITEMS>t94;k304,k1346"</CONDITION_GROUP_ITEMS>
			<CONDITION_GROUP_ITEMS_SK>stock_code1,stock_code2</CONDITION_GROUP_ITEMS_SK>
			<!-- Breaks CONDITION_GROUP_ITEMS into stock codes -->
			<CONDITION_GROUP_ITEMS_ST>stype_desc1,stype_desc2</CONDITION_GROUP_ITEMS_ST>
			<!-- Breaks CONDITION_GROUP_ITEMS into stock types -->
			<!-- CONDITION_GROUPs are ONLY used when there are conditions, this determines the scope of the comparison when PRESET value is used in the condition -->
			<!-- GROUP_TYPE: 0 - The condition item line being evaluated -->
			<!-- GROUP_TYPE: 1 - The condition item line being evaluated's stock type across the rest of the order -->
			<!-- GROUP_TYPE: 2 - The whole sales order -->
			<!-- GROUP_TYPE: 4 - Only the stock items or stock type specified in GROUP_ITEMS, where tNumber is stock type id and sNumber is the stock id -->
			<CONDITION_ITEMS>
				<CONDITION_ITEM>
					<CONDITION>
						<CONDITION_ID>"2"</CONDITION_ID>
						<PARENT_CONDITION_ID>"1"</PARENT_CONDITION_ID>
						<!-- If populated then this condition is a child of another condition - the conditions are defined as a tree structure in Khaos Control -->
						<ANY_ALL>-1</ANY_ALL>
						<!-- -1 = Any (i.e. Any of the conditions can be true/false); 0 = All of the conditions must be true/false -->
						<TRUE_FALSE>-1</TRUE_FALSE>
						<!-- Should the condition evaluate to true or false -->
					</CONDITION>
					<CONDITION_SUB>
						<!-- Each condition item should be evaluated against each line on the sales order -->
						<!-- However, each item should not be evaluated in isolation, but as part of the overarching condition tree structure -->
						<!-- A condition item can be checking against the following types, but it will only be against ONE of these types (never combined as the example data suggests): -->
						<!-- 1) A stock UDA value; 2) For a certain stock item; 3) For a certain stock type; OR 4) A PRESET check against the CONDITION_GROUP above -->
						<CSUB_ID>1</CSUB_ID>
						<UDA_FIELD_INDEX>A267</UDA_FIELD_INDEX>
						<UDA_FIELD_NAME>Running Time</UDA_FIELD_NAME>
						<COMPARISON_ID>A3</COMPARISON_ID>
						<COMPARISON_DESC>equal to or greater than</COMPARISON_DESC>
						<PRESET_ID>A2</PRESET_ID>
						<PRESET_DESC>Net Total</PRESET_DESC>
						<COMPARISON_VALUE>100.99</COMPARISON_VALUE>
						<STOCK_ID>A309</STOCK_ID>
						<STOCK_CODE>A002260</STOCK_CODE>
						<STYPE_ID>A55</STYPE_ID>
						<STOCK_TYPE>Homeware Bedroom</STOCK_TYPE>
					</CONDITION_SUB>
				</CONDITION_ITEM>
				<CONDITION_ITEM />
			</CONDITION_ITEMS>
		</TELESALE_PROMPT>
		<TELESALE_PROMPT>
			<!-- Comment: Next Telesale Rule -->
		</TELESALE_PROMPT>
	</TELESALE_PROMPTS>
	<!-- Comment: Telesales Rules exported from [Khaos Control | Promotions | Telesales Rules]. Telesale Rules used to be called Telesale Prompts -->
	<BARRED_PRODUCTS>
		<COMPANY_BARRED_PRODUCTS>
			<BARRED_COMPANY COMPANY_ID="ABC" COMPANY_CODE="ABC" OTHER_REF="ABC">
				<STOCK_TYPES>
					<STOCK_TYPE STYPE_ID="ABC" DESC="ABC" EXEMPT="0"/>
					<STOCK_TYPE STYPE_ID="ABC" DESC="ABC" EXEMPT="0"/>
				</STOCK_TYPES>
				<STOCK_CODES>
               				<STOCK_CODE STOCK_ID="ABC" STOCK_CODE="ABC" OTHER_REF="ABC" EXEMPT="0"/>
					<STOCK_CODE STOCK_ID="ABC" STOCK_CODE="ABC" OTHER_REF="ABC" EXEMPT="0"/>
				</STOCK_CODES>
			</BARRED_COMPANY>
         		<BARRED_COMPANY COMPANY_ID="ABC" COMPANY_CODE="ABC" OTHER_REF="ABC">
            			<STOCK_TYPES>
               				<STOCK_TYPE STYPE_ID="ABC" DESC="ABC" EXEMPT="0"/>
					<STOCK_TYPE STYPE_ID="ABC" DESC="ABC" EXEMPT="0"/>
				</STOCK_TYPES>
				<STOCK_CODES>
					<STOCK_CODE STOCK_ID="ABC" STOCK_CODE="ABC" OTHER_REF="ABC" EXEMPT="0"/>
					<STOCK_CODE STOCK_ID="ABC" STOCK_CODE="ABC" OTHER_REF="ABC" EXEMPT="0"/>
				</STOCK_CODES>
			</BARRED_COMPANY>      
		</COMPANY_BARRED_PRODUCTS>
		<COMPCLASS_BARRED_PRODUCTS>
			<BARRED_COMPCLASS COMPCLASS_ID="ABC" DESC="ABC">
				<STOCK_TYPES>
					<STOCK_TYPE STYPE_ID="ABC" DESC="ABC" EXEMPT="0"/>
					<STOCK_TYPE STYPE_ID="ABC" DESC="ABC" EXEMPT="0"/>
				</STOCK_TYPES>
				<STOCK_CODES>
					<STOCK_CODE STOCK_ID="ABC" STOCK_CODE="ABC" OTHER_REF="ABC" EXEMPT="0"/>
					<STOCK_CODE STOCK_ID="ABC" STOCK_CODE="ABC" OTHER_REF="ABC" EXEMPT="0"/>
				</STOCK_CODES>
			</BARRED_COMPCLASS>
			<BARRED_COMPCLASS COMPCLASS_ID="ABC" DESC="ABC">
				<STOCK_TYPES>
					<STOCK_TYPE STYPE_ID="ABC" DESC="ABC" EXEMPT="0"/>
					<STOCK_TYPE STYPE_ID="ABC" DESC="ABC" EXEMPT="0"/>
				</STOCK_TYPES>
				<STOCK_CODES>
					<STOCK_CODE STOCK_ID="ABC" STOCK_CODE="ABC" OTHER_REF="ABC" EXEMPT="0"/>
					<STOCK_CODE STOCK_ID="ABC" STOCK_CODE="ABC" OTHER_REF="ABC" EXEMPT="0"/>
				</STOCK_CODES>
			</BARRED_COMPCLASS>
		</COMPCLASS_BARRED_PRODUCTS>
	</BARRED_PRODUCTS>
	<FREE_GIFTS>
		<FREE_GIFT FREEGIFT_ID="ABC121" LOW_VALUE="1.00" HIGH_VALUE="100.00">
			<FREE_ITEMS>
				<FREE_ITEM>ARX_100</FREE_ITEM>
				<FREE_ITEM>ARX_101</FREE_ITEM>
			</FREE_ITEMS>
		</FREE_GIFT>
		<FREE_GIFT FREEGIFT_ID="ABC122" LOW_VALUE="10.00" HIGH_VALUE="1000.00">
			<FREE_ITEMS>
				<FREE_ITEM>ARX_200</FREE_ITEM>
				<FREE_ITEM>ARX_201</FREE_ITEM>
			</FREE_ITEMS>
		</FREE_GIFT>
	</FREE_GIFTS>
</SOPDATA>