<STOCK_STATUS SITE="Main Site">
	<!-- Code: stock item code eg BM101901, ID=KhaosControl internal ID, Desc=Description -->
	<STOCK_ITEM CODE="BM101901" ID="A3567" DESC="Black T-Shirt Size L">
		<!--Comment: Stock item's Buy Price from the [ Stock | Detail | Properties ] screen -->
		<BUY_PRICE>99.00</BUY_PRICE>
		<!--Comment: Stock item's Sell Price from the [ Stock | Detail | Properties ] screen -->
		<SELL_PRICE>100.00</SELL_PRICE>
		<!--Comment: Stock item's Web Sell Price from the [ Stock | Detail | Telesales / Internet ] screen -->
		<WEBSELL_PRICE>100.00</WEBSELL_PRICE>
		<!--Comment: No longer used -->
		<BUNDLE_PRICE>99.99</BUNDLE_PRICE>
		<!--Comment: The stock safe level as held against the stock item -->
		<SAFE_LEVEL>1</SAFE_LEVEL>
		<!--Comment: The stock minimum level as held against the stock item -->
		<MIN_LEVEL>1</MIN_LEVEL>
		<!--Comment: Amount of stock available to be sold -->
		<LEVEL>53</LEVEL>
		<!--Comment: Status:
			0 = In stock,
			1 = Stock all allocated,
			2 = Out of stock (stock due in),
			3 = Out of stock (not due in soon)
			4 = Discontinued (unavailable)
			5 = Non-controlled (do not track levels at all for this item) -->
		<STATUS>0</STATUS>
		<!--Comment: Purchase Order Due Date for the item. Based on the PotentialLeadTime property this will be either:
			-1 = Due Date of the next Purchase Order with available stock for the item
			0 / not passed = Due Date of the oldest undelivered Purchase Order for the item -->
		<LEAD_TIME>2009-08-27</LEAD_TIME>
		<!--Comment: If this product is a build parent, this is what could be built from available child stock
			Note: this is a quick check that only considers the first level of child stock -->
		<BUILD_POTENTIAL_CHILDREN>123</BUILD_POTENTIAL_CHILDREN>
		<!--Comment: If this product is a build parent, this is what could be built from stock contained within other build parent
			Note: this function is not available by default and requires development to enable for a customer as it relies on addition functionality  -->
		<BUILD_POTENTIAL_PARENTS>123</BUILD_POTENTIAL_PARENTS>
	</STOCK_ITEM>
	<STOCK_ITEM CODE="BM101902" ID="A3508" OTHER_REF="ABCSTOCK" DESC="Red printed T-Shirt Size XL">
		<BUY_PRICE>99.00</BUY_PRICE>
		<SELL_PRICE>100.00</SELL_PRICE>
		<WEBSELL_PRICE>100.00</WEBSELL_PRICE>
		<BUNDLE_PRICE>99.99</BUNDLE_PRICE>
		<LEVEL>0</LEVEL>
		<MIN_LEVEL>12</MIN_LEVEL>
		<SAFE_LEVEL>20</SAFE_LEVEL>
		<STATUS>1</STATUS>
		<LEAD_TIME>2009-08-27</LEAD_TIME>
	</STOCK_ITEM>
</STOCK_STATUS>