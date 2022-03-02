<!--Comment: The web category structure in Khaos Control is defined against a website. Where the system is interacting with multiple websites and / or channels, there may be a web category for each individual site / channel -->
<WEBSITE NAME="xxx" ID="00001_">
	<!--Comment: Properties for stock items PER CATEGORY. Website-wide stock item properties are defined later in the file -->
	<!--Comment: Each website consists of a set of categories, that can be defined on multiple levels. Stock items are associated with multiple categories at, potentially, multiple levels -->
	<!--Comment: ID is the internal Khaos Control ID for the Web Category and cannot be changed within Khaos Control -->
	<!--Comment: NAME is the name for the Web Category, this is editable in Khaos Control -->
	<!--Comment: FILE_NAME will be populated with a file name when an image has been associated with the category. The image itself must be uploaded to the relevant web server independently of this feed -->
	<!--Comment: LONG_DESC will be populated with the long description for the category, when defined -->
	<!--Comment: SHORT_DESC will be populated with the short description for the category, when defined -->
	<CATEGORY ID="A0001" NAME="Top level category 1" FILE_NAME="Image000.jpg" LONG_DESC="LongDesc" SHORT_DESC="Short Description" DELETE="-1">
		<!--Comment: The category structure is defined by the way in which categories are nested within each other in the file -->
		<CATEGORY ID="A0001" NAME="Sub category" FILE_NAME="Image000.jpg" LONG_DESC="LongDesc" SHORT_DESC="Short Description">
			<!--Comment: ITEM is only here for backward compatibility, please use STOCK_ITEM -->
			<ITEM>ABC1234</ITEM>
			<ITEM>CCC1111</ITEM>
			<!--Comment: Properties for stock item PER CATEGORY. When specified, these values should supercede both the Website stock item properties and those retrieved via the ExportStock method -->
			<STOCK_ITEM DELETE="-1">
				<!--Comment: Khaos Control Stock ID for the associated item -->
				<STOCK_ID>AAA1222</STOCK_ID>
				<!--Comment: Khaos Control Stock Code for the associated item -->
				<STOCK_CODE>AAA1222</STOCK_CODE>
				<!--Comment: Stock Code defined for the product within this category -->
				<CATEGORY_STOCK_CODE>AAA1222</CATEGORY_STOCK_CODE>
				<!--Comment: Long description defined for the product within this category -->
				<LONG_DESC>This is a long description of the stock item</LONG_DESC>
				<!--Comment: Short description defined for the product within this category -->
				<SHORT_DESC>This is a short description of the stock item</SHORT_DESC>
				<!--Comment: Web teaser defined for the product within this category -->
				<WEB_TEASER>This is a web teaser of the stock item</WEB_TEASER>
				<!--Comment: Sell price defined for the product within this cateory -->
				<SELL_PRICE>999.99</SELL_PRICE>
				<!--Comment: The name of the image to use for this item within this product, as defined by the <IMAGE_NAME /> tag returned via the ExportStock method. This is NOT a filename for the image -->
				<IMAGE_NAME>MainImage</IMAGE_NAME>
				<CANONICAL_ITEM>-1</CANONICAL_ITEM>
				<!-- List of SCS Stock codes related to this item -->
				<SCS>
					<STOCK_CODE>AAA1222_1</STOCK_CODE>	
					<STOCK_CODE>AAA1222_2</STOCK_CODE>
					<STOCK_CODE>AAA1222_3</STOCK_CODE>
					<STOCK_CODE>AAA1222_4</STOCK_CODE>
				</SCS>
			</STOCK_ITEM>
		</CATEGORY>
		<CATEGORY ID="A0001" NAME="Sub category 2" FILE_NAME="Image000.jpg" LONG_DESC="LongDesc" SHORT_DESC="Short Description">
			<!-- ITEM is only here for backward compatibility, please use STOCK_ITEM -->
			<ITEM>AAA1222</ITEM>
			<ITEM>PPP0134</ITEM>
			<STOCK_ITEM>
				<STOCK_ID />
				<STOCK_CODE>AAA1222</STOCK_CODE>
				<CATEGORY_STOCK_CODE>AAA1222</CATEGORY_STOCK_CODE>
				<LONG_DESC>This is a long description of the stock item</LONG_DESC>
				<SHORT_DESC>This is a short description of the stock item</SHORT_DESC>
				<WEB_TEASER>This is a web teaser of the stock item</WEB_TEASER>
				<SELL_PRICE>999.99</SELL_PRICE>
				<IMAGE_NAME>MainImage</IMAGE_NAME>
			</STOCK_ITEM>
			<STOCK_ITEM>
				<STOCK_CODE>AAA1222</STOCK_CODE>
				<CATEGORY_STOCK_CODE>AAA1222</CATEGORY_STOCK_CODE>
				<LONG_DESC>This is a long description of the stock item</LONG_DESC>
				<SHORT_DESC>This is a short description of the stock item</SHORT_DESC>
				<WEB_TEASER>This is a web teaser of the stock item</WEB_TEASER>
				<SELL_PRICE>999.99</SELL_PRICE>
				<IMAGE_NAME>MainImage</IMAGE_NAME>
			</STOCK_ITEM>
			<!--Comment: KCS ONLY, do not use -->
			<CUSTOM>
				<CUSTOM_PROPERTY NAME="CUSTOM_PROPERTY_01">ALPHA</CUSTOM_PROPERTY>
				<CUSTOM_PROPERTY NAME="CUSTOM_PROPERTY_02">-1</CUSTOM_PROPERTY>
			</CUSTOM>
		</CATEGORY>
		<STOCK_ITEM>
			<STOCK_CODE>AAA1222</STOCK_CODE>
			<CATEGORY_STOCK_CODE>AAA1222</CATEGORY_STOCK_CODE>
			<LONG_DESC>This is a long description of the stock item</LONG_DESC>
			<SHORT_DESC>This is a short description of the stock item</SHORT_DESC>
			<WEB_TEASER>This is a web teaser of the stock item</WEB_TEASER>
			<SELL_PRICE>999.99</SELL_PRICE>
			<IMAGE_NAME>MainImage</IMAGE_NAME>
		</STOCK_ITEM>
	</CATEGORY>
	<CATEGORY ID="A0001" NAME="Books" FILE_NAME="Image000.jpg" LONG_DESC="LongDesc" SHORT_DESC="Short Description">
		<CATEGORY ID="A0001" NAME="Paperbacks" FILE_NAME="Image000.jpg" LONG_DESC="LongDesc" SHORT_DESC="Short Description">
			<ITEM>ABC1234</ITEM>
			<ITEM>RRR5555</ITEM>
			<ITEM>RRR5535</ITEM>
			<ITEM>RRR5545</ITEM>
		</CATEGORY>
		<STOCK_ITEM>
			<STOCK_CODE>AAA1222</STOCK_CODE>
			<CATEGORY_STOCK_CODE>AAA1222</CATEGORY_STOCK_CODE>
			<LONG_DESC>This is a long description of the stock item</LONG_DESC>
			<SHORT_DESC>This is a short description of the stock item</SHORT_DESC>
			<WEB_TEASER>This is a web teaser of the stock item</WEB_TEASER>
			<SELL_PRICE>999.99</SELL_PRICE>
			<IMAGE_NAME>MainImage</IMAGE_NAME>
		</STOCK_ITEM>
		<CUSTOM>
			<CUSTOM_PROPERTY NAME="CUSTOM_PROPERTY_01">ALPHA</CUSTOM_PROPERTY>
			<CUSTOM_PROPERTY NAME="CUSTOM_PROPERTY_02">-1</CUSTOM_PROPERTY>
		</CUSTOM>
	</CATEGORY>
	<!--Comment: Properties for stock item PER WEBSITE -->
	<WEBSITE_STOCK_ITEMS>
		<!--Comment: Properties for stock item PER WEBSITE. When specified, these values should supercede those retrieved via the ExportStock method -->
		<STOCK_ITEM>
			<!--Comment: Khaos Control Stock ID for the associated item -->
			<STOCK_ID>AAA1222</STOCK_ID>
			<!--Comment: Khaos Control Stock Code for the associated item -->
			<STOCK_CODE>ABC1234</STOCK_CODE>
			<!--Comment: Stock Code defined for the product within this website -->
			<WEBSITE_STOCK_CODE>AAA1222</WEBSITE_STOCK_CODE>
			<!--Comment: Long description defined for the product within this website -->
			<LONG_DESC>This is a long description of the stock item</LONG_DESC>
			<!--Comment: Short description defined for the product within this wesbite -->
			<SHORT_DESC>This is a short description of the stock item</SHORT_DESC>
			<!--Comment: Web teaser defined for the product within this website -->
			<WEB_TEASER>This is a web teaser of the stock item</WEB_TEASER>
		</STOCK_ITEM>
		<STOCK_ITEM>
			<STOCK_ID />
			<STOCK_CODE>AAA1222</STOCK_CODE>
			<WEBSITE_STOCK_CODE>AAA1222</WEBSITE_STOCK_CODE>
			<LONG_DESC>This is a long description of the stock item</LONG_DESC>
			<SHORT_DESC>This is a short description of the stock item</SHORT_DESC>
			<WEB_TEASER>This is a web teaser of the stock item</WEB_TEASER>
		</STOCK_ITEM>
	</WEBSITE_STOCK_ITEMS>
</WEBSITE>
<!--Change History:
	30/03/2012	Category ID added and comments for all tags updated
-->