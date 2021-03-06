<STOCK_ITEMS>
  <STOCK_ITEM>
    <STOCK_CODE />
    <!--Comment: main stock code -->
    <ASSOCIATED_REF />
    <!--Comment: Other Ref in KhaosControl, may be blank -->
    <STOCK_DESC />
    <!--Comment: Short description / name -->
    <LONG_DESC />
    <!--Comment: Optional extended/long description -->
    <WEB_TEASER />
    <!--Comment: Optional web teaser (long text) -->
    <BUY_PRICE IS_NET="0">2.48</BUY_PRICE>
    <SELL_PRICE IS_NET="0">5.99</SELL_PRICE>
    <SELL_PRICE_WEB></SELL_PRICE_WEB>
    <!--Comment: Used to override the sell price for the web -->
    <IMAGE_FILENAME />
    <!--Comment: Deprecated, use STOCK_IMAGES below -->
    <VAT_RATE />
    <!--Comment: 17.5, 0, 15, ETC-->
    <COUNTRY_RATES>
      <COUNTRY_RATE COUNTRY_CODE="GB" COUNTRY_NAME="England">17.5</COUNTRY_RATE>
      <COUNTRY_RATE />
    </COUNTRY_RATES>
    <STOCK_TTYPE EPOS_DESC="XYZ">Level one stock</STOCK_TTYPE>
    <!--Comment: (level 1) (parent/header) type -->
    <STOCK_TTYPE_ID />
    <!--Comment: Unique ID -->
    <STOCK_TYPE EPOS_DESC="ABC">Sample stock type</STOCK_TYPE>
    <!--Comment: (level 2) Textual description eg. "Books" -->
    <STOCK_TYPE_ID />
    <!--Comment: Unique ID -->
    <STOCK_MID_TYPE EPOS_DESC="XYZ">aaaaM</STOCK_MID_TYPE>
    <!--Comment: Description for stock mid type (level 3) - alphanumberic, eg. "56K" -->
    <STOCK_MID_TYPE_ID />
    <!--Comment: Unique ID -->
    <SUB_TYPE EPOS_DESC="XYZ" />
    <!--Comment: duplicate of STOCK_SUB_TYPE -->
    <SUB_TYPE_ID />
    <!--Comment: duplicate of STOCK_SUB_TYPE_ID -->
    <STOCK_SUB_TYPE EPOS_DESC="XYZ">L4 type</STOCK_SUB_TYPE>
    <!--Comment: Description for stock sub type (level 3/4) - alphanumberic, eg. "56K" -->
    <STOCK_SUB_TYPE_ID />
    <!--Comment: Unique ID -->
    <STOCK_ID />
    <!--Comment: Unique ID for stock item -->
    <MANUFACTURER />
    <SUPPLIER_STOCK_CODE />
    <!--Stock code for the preferred supplier-->
    <LEAD_TIME />
    <!--Comment: lead time in days which this item normally takes to arrive when purchased -->
    <WEIGHT />
    <DELETED />
    <!--Comment: 0=Not deleted, -1=Deleted item; combines "web" and "discontinued" properties -->
    <WEB>0</WEB>
    <!--Comment: is published to web site -->
    <DISCONTINUED>0</DISCONTINUED>
    <DISCOUNTS_DISABLED>0</DISCOUNTS_DISABLED>
    <RUN_TO_ZERO>0</RUN_TO_ZERO>
    <DROP_SHIP>0</DROP_SHIP>
    <WEB_PAGEORDER>1</WEB_PAGEORDER>
    <WEB_COLOURVALUE>xyz</WEB_COLOURVALUE>
    <STOCK_CONTROLLED>0</STOCK_CONTROLLED>
    <VAT_RELIEF_QUALIFIED>0</VAT_RELIEF_QUALIFIED>
    <!-- Whether item is stock controlled or not (0=false, -1=true) -->
    <META_TITLE></META_TITLE>
    <META_DESCRIPTION></META_DESCRIPTION>
    <META_KEYWORDS></META_KEYWORDS>
    <!-- SOE data -->
    <EPOS_DESC>xxxx</EPOS_DESC>
    <!-- Short description used on EPOS tills -->
    <AVAILABILITY></AVAILABILITY>
    <!-- Availability text for item -->
    <MAX_DISPLAY_QTY>0</MAX_DISPLAY_QTY>
    <!-- Max Display Qty (Integer), limit the amount of stock displayed -->
    <LAUNCH_DATE>2008-12-31</LAUNCH_DATE>
    <LAUNCH_TIME>00:00:00</LAUNCH_TIME>
	<!-- Min Reorder Level -->
	<MIN_LEVEL>12</MIN_LEVEL>
	<!-- Safe Reorder Level -->
	<SAFE_LEVEL>20</SAFE_LEVEL>
    <REWARD_POINTS></REWARD_POINTS><!--The cost of this item in reward points -->
		<REORDER_MULTIPLE>0</REORDER_MULTIPLE>
		<DISCOUNT_CODE></DISCOUNT_CODE> <!-- The pricing discount code -->
    <SALES_MULTIPLE/>
    <STOCK_OPTION NAME="Run To Zero">0</STOCK_OPTION>
    <STOCK_OPTION NAME="Require Serial">0</STOCK_OPTION>
    <STOCK_OPTION NAME="Don't print child items">0</STOCK_OPTION>
    <STOCK_OPTION NAME="Exclude delivery labels">0</STOCK_OPTION>
    <!--Comment: system stock options (DEPRECATED - DO NOT USE) -->
    <OPTION NAME="Specialist Range">0</OPTION>
    <OPTION NAME="Required Reading">0</OPTION>
    <!--Comment: user defined stock options -->
    <USER_DEFINED NAME="Author" TYPE="TEXT">Margaret Weis / Tracy Hickman</USER_DEFINED>
    <USER_DEFINED NAME="Date Published" TYPE="DATE">2008-12-31</USER_DEFINED>
    <USER_DEFINED NAME="Heavy" TYPE="YES/NO">0</USER_DEFINED>
    <USER_DEFINED NAME="ISBN-10" TYPE="NUMBER">0786915749</USER_DEFINED>
    <USER_DEFINED NAME="ISBN-13" TYPE="TEXT">978-0786915743</USER_DEFINED>
	<USER_DEFINED NAME="List Example" TYPE="LIST">"List Option 2","List Option 3"</USER_DEFINED>
	<USER_DEFINED NAME="Choice Example" TYPE="CHOICE">tester1</USER_DEFINED>
    <!--Comment: user defined attributes (UDAs) -->
    <BARS>
      <!-- List of restrictions/bars on this item (limits on where it can be sold -->
      <!-- The bar type indicates what type of restriction is imposed. -->
      <!-- The only valid value currently is 1 = barred from country -->
      <!-- The tag contents show the specific bar in question, eg. for countries, the 3 letter country code which is barred -->
      <BAR TYPE="1">JPN</BAR>
      <BAR TYPE="1">DEU</BAR>
    </BARS>
    <BARCODES>
      <BARCODE DESC="ABC">ABCDEFG</BARCODE>
      <BARCODE DESC="ABC">ABCDEFG</BARCODE>
    </BARCODES>
    <!-- List of external files linked to stock item -->
    <EXTERNAL_DATA>
      <EXTERNAL_ITEM>
        <EXTERNAL_TYPE>1</EXTERNAL_TYPE>
        <EXTERNAL_VALUE>C:\File.txt</EXTERNAL_VALUE>
        <EXTERNAL_SHORT_DESC>My File Description</EXTERNAL_SHORT_DESC>
        <EXTERNAL_SORT_ORDER>1</EXTERNAL_SORT_ORDER>
      </EXTERNAL_ITEM>
      <EXTERNAL_ITEM>
        <EXTERNAL_TYPE>1</EXTERNAL_TYPE>
      </EXTERNAL_ITEM>
    </EXTERNAL_DATA>
    <!-- List of images linked to stock item -->
    <STOCK_IMAGES>
      <STOCK_IMAGE>
        <IMAGE_NAME>Image Name</IMAGE_NAME>
        <IMAGE_DESC>Desctiption of the image</IMAGE_DESC>
        <FILE_NAME>C:\TestImage.jpg</FILE_NAME>
        <IMAGE_TYPE>Thumbnail</IMAGE_TYPE>
      </STOCK_IMAGE>
      <STOCK_IMAGE>
        <IMAGE_NAME>Image Name</IMAGE_NAME>
      </STOCK_IMAGE>
    </STOCK_IMAGES>
    <LINKED_ITEMS>
      <LINK_ITEM>
        <!--Comment: code for related item -->
        <STOCK_CODE />
        <LINK_TYPE />
        <!-- Comment: 1=Upsell, 2=Crosssell, 3=Pack, 4=Associated -->
        <LINK_VALUE />
        <!-- For U/C sell, the special price; for pack items, the quantity contained -->
        <AUTO_ADD />
        <!-- Will this item auto add to the sales order 0=No, -1=Yes-->
      </LINK_ITEM>
      <LINK_ITEM>
        <!--Comment: further links follow... -->
      </LINK_ITEM>
    </LINKED_ITEMS>
	<!--Comment: A list of all supplier stock codes -->
	<SUPPLIER_STOCK_CODES>
		<SUPPLIER_REF IS_PREFERRED="-1" SUPPLIER_NAME="SOME SUPPLIER" SUPPLIER_URN="SU001" DISCOUNT="0.00" PURCHASE_COST="0.00" REORDER_QTY="0.00" QTY_AVAIL="0.00">00012A</SUPPLIER_REF>
    <SUPPLIER_REF IS_PREFERRED="0" SUPPLIER_NAME="SOME OTHER SUPPLIER" SUPPLIER_URN="SU002" DISCOUNT="0.00" PURCHASE_COST="0.00" REORDER_QTY="0.00" QTY_AVAIL="0.00">221012A</SUPPLIER_REF>
	</SUPPLIER_STOCK_CODES>
    <SCS>
      <!--Comment: The heading indicate what data each 'level' within the template contains -->
      <HEADING LEVEL="1">Size</HEADING>
      <HEADING LEVEL="2">Colour</HEADING>
      <HEADING LEVEL="3">Style</HEADING>
      <HEADING LEVEL="4">Length</HEADING>
      <!--Comment: styles can be up to four levels deep with an SCS item at the lowest level -->
      <!--Comment: tags all have same format as tags in main item above -->
      <STYLE>
        <DESCRIPTION />
        <!--Comment: stock code suffix for this element (already included in STOCK_CODE - just separated out for information) -->
        <SCS_CODE_SUFFIX>ABC</SCS_CODE_SUFFIX>
        <SCS_ITEM>
          <STOCK_CODE />
          <!--Comment: Other Ref in KhaosControl, may be blank	 -->
          <ASSOCIATED_REF />
          <DESCRIPTION />
          <WEIGHT />
          <BUY_PRICE />
          <SELL_PRICE />
          <!-- The IS_NET attribute is not included for SCS children as the value from the parent should be inherited.-->
          <SELL_PRICE_WEB />
          <STOCK_ID />
          <LONG_DESC />
					<WEB_TEASER />
          <LAUNCH_DATE>2008-12-31</LAUNCH_DATE>
	  <LAUNCH_TIME>00:00:00</LAUNCH_TIME>
          <LEAD_TIME />
          <SUPPLIER_STOCK_CODE />
          <IMAGE_FILENAME />
          <!--Comment: 0=Not deleted, -1=Deleted item -->
          <DELETED />
          <!--Comment: separate "show on web" and "discontinued" properties -->
          <WEB>0</WEB>
          <DISCONTINUED>0</DISCONTINUED>
          <!--Comment: Website properties -->
          <DROP_SHIP>0</DROP_SHIP>
          <DISCOUNTS_DISABLED>0</DISCOUNTS_DISABLED>
          <RUN_TO_ZERO>0</RUN_TO_ZERO>
          <WEB_PAGEORDER>1</WEB_PAGEORDER>
          <WEB_COLOURVALUE>xyz</WEB_COLOURVALUE>
          <STOCK_CONTROLLED>0</STOCK_CONTROLLED>
          <VAT_RELIEF_QUALIFIED>0</VAT_RELIEF_QUALIFIED>
          <EPOS_DESC>xxxx</EPOS_DESC>
          <!--Comment: Short description used on EPOS tills -->
          <META_TITLE></META_TITLE>
          <META_DESCRIPTION></META_DESCRIPTION>
          <META_KEYWORDS></META_KEYWORDS>
          <!-- SOE data -->
          <AVAILABILITY></AVAILABILITY>
          <REWARD_POINTS></REWARD_POINTS>
          <!--The cost of this item in reward points -->
		  <REORDER_MULTIPLE>0</REORDER_MULTIPLE>
		  <DISCOUNT_CODE></DISCOUNT_CODE> <!-- The pricing discount code -->
          <!-- Min Reorder Level -->
		  <MIN_LEVEL>12</MIN_LEVEL>
		  <!-- Safe Reorder Level -->
		  <SAFE_LEVEL>20</SAFE_LEVEL>
		  <SALES_MULTIPLE/>
		  <!-- Max Display Qty (Integer) -->
		  <MAX_DISPLAY_QTY>0</MAX_DISPLAY_QTY>
          <!--Comment: 17.5, 0, 15, ETC-->
          <VAT_RATE />
          <COUNTRY_RATES>
            <COUNTRY_RATE COUNTRY_CODE="GB" COUNTRY_NAME="England">17.5</COUNTRY_RATE>
            <COUNTRY_RATE />
          </COUNTRY_RATES>
          <STOCK_TYPE EPOS_DESC="ABC">Sample stock type</STOCK_TYPE>
          <!--Comment: Textual description eg. "Books" -->
          <STOCK_TTYPE EPOS_DESC="XYZ">Level one stock</STOCK_TTYPE>
          <!--Comment: Level one (parent/header) type -->
          <STOCK_MID_TYPE EPOS_DESC="XYZ">aaaaM</STOCK_MID_TYPE>
          <!--Comment: Description for stock mid type (level 3) - alphanumberic, eg. "56K" -->
          <STOCK_SUB_TYPE EPOS_DESC="XYZ">L4 type</STOCK_SUB_TYPE>
          <!--Comment: Description for stock sub type (level 3/4) - alphanumberic, eg. "56K" -->
          <SUB_TYPE_ID></SUB_TYPE_ID>
          <!--Comment: ID of the <STOCK_SUB_TYPE> field -->
          <!--Comment: SCS stock item related items -->
          <LINKED_ITEMS>
            <LINK_ITEM>
              <!--Comment: code for related item -->
              <STOCK_CODE />
              <!--Comment: 1=Upsell, 2=Crosssell, 3=Pack, 4=Associated -->
              <LINK_TYPE />
              <!-- For U/C sell, the special price; for pack items, the quantity contained -->
              <LINK_VALUE />
            </LINK_ITEM>
            <LINK_ITEM>
              <!--Comment: further links follow... -->
            </LINK_ITEM>
          </LINKED_ITEMS>
          <!-- List of images linked to stock item -->
          <STOCK_IMAGES>
            <STOCK_IMAGE>
              <IMAGE_NAME>Image Name</IMAGE_NAME>
              <IMAGE_DESC>Desctiption of the image</IMAGE_DESC>
              <FILE_NAME>C:\TestImage.jpg</FILE_NAME>
              <IMAGE_TYPE>Thumbnail</IMAGE_TYPE>
            </STOCK_IMAGE>
            <STOCK_IMAGE>
              <IMAGE_NAME>Image Name</IMAGE_NAME>
            </STOCK_IMAGE>
          </STOCK_IMAGES>
          <BARCODES>
            <BARCODE DESC="ABC">ABCDEFG</BARCODE>
            <BARCODE DESC="ABC">ABCDEFG</BARCODE>
          </BARCODES>
          <STOCK_OPTION NAME="Run To Zero">0</STOCK_OPTION>
          <STOCK_OPTION NAME="Require Serial">0</STOCK_OPTION>
          <STOCK_OPTION NAME="Don't print child items">0</STOCK_OPTION>
          <STOCK_OPTION NAME="Exclude delivery labels">0</STOCK_OPTION>
          <!--Comment: system stock options (DEPRECATED - DO NOT USE) -->
          <OPTION NAME="Specialist Range">0</OPTION>
          <OPTION NAME="Required Reading">0</OPTION>
          <!--Comment: user defined stock options -->
          <USER_DEFINED NAME="Author">Margaret Weis / Tracy Hickman</USER_DEFINED>
          <USER_DEFINED NAME="Date Published">2008-12-31</USER_DEFINED>
          <USER_DEFINED NAME="Heavy">0</USER_DEFINED>
          <USER_DEFINED NAME="ISBN-10">0786915749</USER_DEFINED>
          <USER_DEFINED NAME="ISBN-13">978-0786915743</USER_DEFINED>
          <!--Comment: user defined attributes (UDAs) -->
		  <!--Comment: A list of all supplier stock codes -->
		  <SUPPLIER_STOCK_CODES>
		    <SUPPLIER_REF IS_PREFERRED="-1" SUPPLIER_NAME="SOME SUPPLIER" SUPPLIER_URN="SU001" DISCOUNT="0.00" PURCHASE_COST="0.00" REORDER_QTY="0.00" QTY_AVAIL="0.00">00012A</SUPPLIER_REF>
				<SUPPLIER_REF IS_PREFERRED="0" SUPPLIER_NAME="SOME OTHER SUPPLIER" SUPPLIER_URN="SU002" DISCOUNT="0.00" PURCHASE_COST="0.00" REORDER_QTY="0.00" QTY_AVAIL="0.00">221012A</SUPPLIER_REF>
		  </SUPPLIER_STOCK_CODES>
        </SCS_ITEM>
        <STYLE>
          <DESCRIPTION />
        </STYLE>
        <STYLE>
          <DESCRIPTION />
        </STYLE>
      </STYLE>
      <STYLE>
        <DESCRIPTION />
      </STYLE>
    </SCS>
  </STOCK_ITEM>
  <STOCK_ITEM>
    <!--Comment: further items follow... -->
  </STOCK_ITEM>
  <!-- File structure:
	SCS (Size/Colour/Style variants) can be either exported as completely separate items
	(in which case they get their own STOCK_ITEM tag, and aren't directly 'linked together')
	or they can be exported with the structure intact, in which case there is one STOCK_ITEM
	tag for the 'parent item'/'template', with an SCS tag containing the hierarchy of child
	items.
	-->
</STOCK_ITEMS>