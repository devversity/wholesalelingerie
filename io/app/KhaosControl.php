<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;
use SoapClient;
use File;
use Response;
use ZipArchive;
use DB;

class KhaosControl extends Model
{
    private $khaos_endpoint;
    private $khaos_kls_endpoint;
    private $endpoint;
    private $soap_client;
    private $in_dir, $out_dir;

    public function __construct($endpoint = '')
    {
        $this->khaos_endpoint = 'https://77.107.185.72:4433/khaoskev.exe/wsdl/IKosWeb';
        $this->khaos_kls_endpoint = 'https://77.107.185.72:4434/khaoskev_kls.exe/wsdl/IKosWeb';

        $this->in_dir = public_path('/khaos/in');
        $this->out_dir = public_path('/khaos/out');

        switch ($endpoint) {
            case "kls":
                $this->endpoint = $this->khaos_kls_endpoint;
                break;
            default:
                $this->endpoint = $this->khaos_endpoint;
                break;
        }

        $opts = [
            'ssl' => [

                'verify_peer' => false,
                'verify_peer_name' => false,
                ]
        ];

        $params = [
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => SOAP_1_2,
            'trace' => 1,
            'exceptions' => 1,
            'connection_timeout' => 3600,
            'stream_context' => stream_context_create($opts)
        ];

        $this->cmd = false;

    if(!extension_loaded("soap"))
    {
        dl("php_soap.dll");
    }
    
    ini_set("soap.wsdl_cache_enabled", "0");

        $this->soap_client = new SoapClient($this->endpoint, $params);
    }

    public function get_stock_potential($stock_code)
    {
        return $this->soap_client->GetStockPotential($stock_code, 1);
    }

    public function export_stock_status($stock_code)
    {
        return $this->soap_client->ExportStockStatus($stock_code, 1);
    }

    public function build($request, $params = [])
    {
        $content = "";

        switch ($request) {
            case "ImportCatalogueRequest":
                break;
            case "ImportOrders":
                break;
            case "ImportCompany":
                break;
            case "ImportStockStatus":
                break;
        }
        return $content;
    }

    public function request($type, $interval = 3600, $format = null, $body = null, $get = null, $post = null, $return = false, $cmd = false)
    {
        if (empty($format)) {
            $format = 'xml';
        }

        if (!empty($body)) {
            $_GET = $body;
        }

        $name = "";
        if (strpos($type, "_") > -1) {
            $type_data = explode("_", $type);
            $type = $type_data[0];
            $name = $type_data[1];
        }

        $this->cmd = $cmd;
        $format = "." . $format;
        $contents = "";
        $date_interval = date("Y-m-d \TG:i:s", time() - $interval);

        $tmp_file = "";
        $dir = $this->in_dir . '/' . $type;
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        try {
            switch ($type) {
                case "ExportPriceLists":
                    $price_lists = [];

                    if (empty($name)) {
                        $price_lists = $this->soap_client->GetPriceLists();
                    } else {
                        $price_lists[] = $name;
                    }

                    if (!empty($price_lists)) {
                        foreach ($price_lists as $price_list_name) {
                            $response = [];
                            try {
                                $response[$price_list_name] = $this->soap_client->ExportPriceList($price_list_name);

                                if (
                                    is_string($response) &&
                                    !empty($response)
                                ) {
                                    $tmp_file = $dir . '/' . time() . $format;
                                    File::put($tmp_file, $response);
                                    echo "Saved " .$price_list_name . " - ". $type . ": " . $tmp_file;
                                    echo $this->cmd ? "\n" : "<br/>";
                                } elseif (is_object($response) || is_array($response)) {
                                    $tmp_file = $dir . '/' . time() . ".json";
                                    File::put($tmp_file, json_encode($response));
                                    echo "Saved " .$price_list_name . " - ". $type . ": " . $tmp_file;
                                    echo $this->cmd ? "\n" : "<br/>";
                                }

                            } catch (\Exception $ex) {
                                echo "Skipping ".$price_list_name." - Failed to pull PriceList data";
                                echo $this->cmd ? "\n" : "<br/>";
                            }
                        }
                    }
                    break;
                case "ExportStock":
                    return;
                    $date_interval = date("Y-m-d \TG:i:s", time() - $interval);

                    if (!isset($_GET['show_contents'])) {
                        echo "Fetching from: ".$date_interval;
                        echo $this->cmd ? "\n" : "<br/>";
                    }


                    if (
                        isset($_GET['filename']) &&
                        file_exists($this->in_dir."/".$type."/archive/".$_GET['filename'])
                    ) {
                        $contents = file_get_contents($this->in_dir."/".$type."/archive/".$_GET['filename']);
                    } else {
//                        try {
                            $contents = $this->soap_client->ExportStock("", 1, $date_interval);
//                        } catch (\Exception $ex) {
////                            var_dump($ex);exit;
////                            var_dump($this->soap_client->__getLastRequest());exit;
//                        }

                    }

                    if (isset($_GET['show_contents'])) {
                        print_r($contents);
                        exit;
                    }

                    break;
                case "GetStockList":

                    if (isset($_GET['skip'])) {
                        break;
                    }

                    try {
                        $revisions = $this->soap_client->GetStockList(0, $date_interval, 0); // Revision Date
                        $movements = $this->soap_client->GetStockList(2, $date_interval, 0); // Movement Date
                    } catch (\Exception $ex) {
                        print_r($ex);exit;
                    }

                    $export_stock_codes = [];

                    if (isset($_GET['stock_code'])) {
                        $export_stock_codes[] = $_GET['stock_code'];
                    } elseif (!empty($revisions) || !empty($movements)) {
                        if (!empty($revisions)) {
                            foreach ($revisions->List as $export_stock_row) {
                                if (!empty($export_stock_row->ParentCode)) {
                                    $export_stock_codes[$export_stock_row->ParentCode] = $export_stock_row->ParentCode;
                                } else {
                                    $export_stock_codes[$export_stock_row->StockCode] = $export_stock_row->StockCode;
                                }
                            }
                        }
                        if (!empty($movements)) {
                            foreach ($movements->List as $export_stock_row) {
                                if (!empty($export_stock_row->ParentCode)) {
                                    $export_stock_codes[$export_stock_row->ParentCode] = $export_stock_row->ParentCode;
                                } else {
                                    $export_stock_codes[$export_stock_row->StockCode] = $export_stock_row->StockCode;
                                }
                            }
                        }
                    }

                    if (count($export_stock_codes) == 0) {
                        return;
                    }

                    if (!empty($export_stock_codes) && !isset($_GET['skip'])) {
                        $stock_codes_chunked = array_chunk($export_stock_codes, 15);

                        foreach ($stock_codes_chunked as $key => $chunk) {

//                            if (isset($chunk[0])) {
//                                $chunk = $chunk[0];
//                            }
//
//                            if (!is_array($chunk)) {
//                                $chunk = [$chunk];
//                            }
                            $chunked_stock_codes = implode(",", $chunk);

                            try {

                                echo $this->cmd ? "\n" : "<br/>";
                                $stock_xml = $this->soap_client->ExportStock($chunked_stock_codes, 1);
                                $stock_dir = $this->in_dir . '/'.$type;

                                if (isset($_GET['debug'])) {
                                    echo "<pre>";
                                    print_r($stock_xml);
                                    echo "</pre>";
                                    exit;
                                }

                                if (!is_dir($stock_dir)) {
                                    mkdir($stock_dir);
                                }

                                $stock_tmp_file = $stock_dir . '/' . time() . '_' . $key . $format;
                                File::put($stock_tmp_file, $stock_xml);
                                chmod($stock_tmp_file, 0777);
                                echo 'Stored ExportStock ' . ($key + 1) . '/' . count($stock_codes_chunked) . ': ' . $stock_tmp_file;
                                echo $this->cmd ? "\n" : "<br/>";
                            } catch (\Exception $ex) {
                                echo 'Error: ' . ($key + 1) . " - " . $ex;
                                echo $this->cmd ? "\n" : "<br/>";
                                continue;
                            }
                        }
                    }

                    break;
                default:
                    $this->soap_client->__soapCall($type, [$body]);
                    return $this->soap_client->__getLastResponse();
                    break;
            }
        } catch (\Exception $ex) {

            if ($type == "ImportOrders" && !isset($_GET['debug'])) {
                if ($return) {
                    return $this->soap_client->__getLastResponse();
                } else {
                    return "error";
                }
            } else {
                echo "Soap Fault (Exception): " . $ex . "\n";

                if ($return) {
                    return var_dump($this->soap_client->__getLastResponse());
                } else {
                    var_dump($this->soap_client->__getLastRequest());
                    return "error";
                }

            }
        }

        if (
            is_string($contents) &&
            !empty($contents)
        ) {
            $tmp_file = $dir . '/' . time() . $format;
            File::put($tmp_file, $contents);
            echo "Saved " . $type . ": " . $tmp_file;
            echo $this->cmd ? "\n" : "<br/>";
        } elseif (is_object($contents) || is_array($contents)) {
            $tmp_file = $dir . '/' . time() . ".json";
            File::put($tmp_file, json_encode($contents));
            echo "Saved " . $type . ": " . $tmp_file;
            echo $this->cmd ? "\n" : "<br/>";
        }

        $this->process_folder($dir, $type);

        return;
    }

    private function process_folder($dir, $type, $archive_time = 604800)
    {
        $archive_folder = $dir . "/archive";

        // Create archive folder is doesn't exist
        if (!is_dir($archive_folder)) {
            mkdir($archive_folder);
        }

        // Remove old files
        $archived_files = glob($archive_folder . "/*");
        foreach ($archived_files as $file) {

            $filemtime = filemtime($file);
            if (time() - $filemtime >= $archive_time) {
                echo "Removing file " . $file . " from archive.";
                echo $this->cmd ? "\n" : "<br/>";
                unlink($file);
            }
        }

        $files = [];
        $scan = scandir($dir);
        $i = 999;

        foreach ($scan as $key => $file) {
            if (
                $file != "." &&
                $file != ".." &&
                $file != "archive"
            ) {
                list($timestamp, $ext) = explode(".", $file);

                if ($key < $i) {
                    $files[$timestamp] = [
                        'location' => $dir . "/" . $file,
                        'file' => $file,
                        'ext' => $ext
                    ];
                } else {
                    break;
                }

            }
        }

        switch ($type) {
            case "GetCompanyClass":
                DB::table('company_class')->truncate();
                break;
            case "GetCompanyList":
                DB::table('company_list')->truncate();
                break;
            case "GetCompanyStatus":
                DB::table('company_status')->truncate();
                break;
            case "GetCountryList":
                DB::table('country_list')->truncate();
                break;
            case "GetKeycodeTypes":
                DB::table('keycode_types')->truncate();
                break;
            case "GetKeycodes":
                DB::table('keycodes')->truncate();
                break;
            case "GetMailingStatusList":
                DB::table('mailing_status')->truncate();
                break;
            case "GetManufacturer":
                DB::table('manufacturer')->truncate();
                break;
        }

        if (!empty($files)) {
            ksort($files);
            foreach ($files as $file) {

                if (empty($file['location'])) {
                    continue;
                }

                if (file_exists($file['location'])) {
                    $contents = file_get_contents($file['location']);
                } else {
                    continue;
                }

                switch (strtolower($file['ext'])) {
                    case "xml":
                        $xml = simplexml_load_string($contents, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

                        if (empty($xml)) {
                            break;
                        }

                        echo "Found ".count($xml)." rows";
                        echo $this->cmd ? "\n" : "<br/>";

                        $count = 0;
                        foreach ($xml as $row) {
                            $this->process_row($type, $row, $file['ext']);
                            $count++;
                            echo "Processed ".$count." of ".count($xml)." rows";
                            echo $this->cmd ? "\n" : "<br/>";

                        }

                        break;
                    case "json":
                        $json = json_decode($contents);

                        switch ($type) {
                            case "GetCompanyList":
                            case "GetCountryList":
                                if (!empty($json->List)) {
                                    $json = $json->List;
                                } else {
                                    $json = "";
                                }
                                break;
                        }

                        if (empty($json) || !is_object($json)) {
                            break;
                        }

                        foreach ($json as $key => $row) {
                            switch ($type) {
                                case "ExportPriceLists":
                                    if (!empty($key)) {
                                        PriceLists::where('name','=', $key)->delete();
                                        PriceListStockItems::where('price_list','=', $key)->delete();
                                    }
                                    break;
                            }
                            $this->process_row($type, $row, $file['ext'], $key);
                        }

                        break;
                }

                $old = $file['location'];
                $new = str_replace($file['file'], "archive/" . $file['file'], $file['location']);

                if (file_exists($old)) {
                    rename($old, $new);
                }

            }
        }

        $files = glob($archive_folder."/*");
        $now = time();
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 30) { // 30 days
                    unlink($archive_folder . $file);
                }
            }
        }

    }

    private function process_row($type, $data, $ext, $key = '')
    {
        echo ".......Start Row.......";
        echo $this->cmd ? "\n" : "<br/>";
        $ref = "";

        switch ($type) {
            case "ExportPriceLists":
                $price_list_name = $key;
                $price_list = PriceLists::where('name', '=', $price_list_name)->first();
                if ($price_list == null) {
                    $price_list = new PriceLists;
                }
                $price_list->name = $key;
                $price_list->save();
                $price_list = PriceLists::where('name', '=', $price_list_name)->first();

                $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

                if (empty($xml)) {
                    break;
                }

                foreach ($xml as $row) {
                    foreach ($row->StockItem as $item) {

                        echo "Processing: " . $price_list->id.". ".$key." - ".$item->StockCode;
                        echo $this->cmd ? "\n" : "<br/>";

                        $price_list_item = PriceListStockItems::where('StockCode', '=', $price_list_name)
                            ->where('StockCode', '=', $item->StockCode)
                            ->first();
                        if ($price_list_item == null) {
                            $price_list_item = new PriceListStockItems;
                        }

                        $price_list_item->price_list = $price_list_name;
                        $price_list_item->AmountValue = $item->AmountValue;
                        $price_list_item->BuyPrice = $item->BuyPrice;
                        $price_list_item->BuyPriceMassage = $item->BuyPriceMassage;
                        $price_list_item->CalcBranch = $item->CalcBranch;
                        $price_list_item->CalculatedPrice = $item->CalculatedPrice;
                        $price_list_item->Discontinued = $item->Discontinued;
                        $price_list_item->DiscountValue = $item->DiscountValue;
                        $price_list_item->FlagsStrucDiscPerSOrderLine = $item->FlagsStrucDiscPerSOrderLine;
                        $price_list_item->MarkUp = $item->MarkUp;
                        $price_list_item->MassagedMarkUp = $item->MassagedMarkUp;
                        $price_list_item->OriginalCalculatedPrice = $item->OriginalCalculatedPrice;
                        $price_list_item->QtyEnd = $item->QtyEnd;
                        $price_list_item->QtyStart = $item->QtyStart;
                        $price_list_item->SellPrice = $item->SellPrice;
                        $price_list_item->StockCode = $item->StockCode;
                        $price_list_item->StockDesc = $item->StockDesc;
                        $price_list_item->StockID = $item->StockID;
                        $price_list_item->TaxPortion = $item->TaxPortion;
                        $price_list_item->TaxRate = $item->TaxRate;
                        $price_list_item->UseBuyPrice = $item->UseBuyPrice;
                        $price_list_item->save();
                    }
                }

                break;
            case "GetStockList":
            case "ExportStock":

                $child_stock_codes = [];
                $row = $data;

                if (empty($row->STOCK_CODE)) {
                    break;
                }

                $parent_product = ParentProduct::where('stock_code', '=', $row->STOCK_CODE)->first();

                if ($parent_product == null) {
                    $parent_product = new ParentProduct;
                }

                echo "Processing: " . $row->STOCK_CODE;
                echo $this->cmd ? "\n" : "<br/>";

                $parent_product->stock_code = $row->STOCK_CODE;
                $parent_product->associated_ref = $row->ASSOCIATED_REF;
                $parent_product->stock_desc = $row->STOCK_DESC;
                $parent_product->long_desc = $row->LONG_DESC;
                $parent_product->epos_desc = $row->EPOS_DESC;
                $parent_product->web_teaser = $row->WEB_TEASER;
                $parent_product->buy_price = $row->BUY_PRICE;
                $parent_product->sell_price = $row->SELL_PRICE;
                $parent_product->sell_price_web = $row->SELL_PRICE_WEB;
                $parent_product->image_filename = $row->IMAGE_FILENAME;
                $parent_product->vat_rate = $row->VAT_RATE;
                $parent_product->stock_type = $row->STOCK_TYPE;
                $parent_product->stock_type_id = $row->STOCK_TYPE_ID;
                $parent_product->stock_mid_type = $row->STOCK_MID_TYPE;
                $parent_product->stock_mid_type_id = empty($row->STOCK_MID_TYPE_ID) ? 0 : $row->STOCK_MID_TYPE_ID;
                $parent_product->sub_type = $row->SUB_TYPE;
                $parent_product->stock_sub_type = $row->STOCK_SUB_TYPE;
                $parent_product->sub_type_id = $row->SUB_TYPE_ID;
                $parent_product->manufacturer = $row->MANUFACTURER;
                $parent_product->supplier_stock_code = $row->SUPPLIER_STOCK_CODE;
                $parent_product->lead_time = $row->LEAD_TIME;
                $parent_product->weight = $row->WEIGHT;
                $parent_product->stock_ttype = $row->STOCK_TTYPE;
                $parent_product->stock_ttype_id = $row->STOCK_TTYPE_ID;
                $parent_product->web_pageorder = $row->WEB_PAGEORDER;
                $parent_product->web_colourvalue = $row->WEB_COLOURVALUE;
                $parent_product->deleted = $row->DISCONTINUED;
                $parent_product->web = $row->WEB;
                $parent_product->discounted = empty($row->DISCOUNTED) ? 0 : $row->DISCOUNTED;
                $parent_product->drop_ship = $row->DROP_SHIP;
                $parent_product->discounts_disabled = $row->DISCOUNTS_DISABLED;
                $parent_product->run_to_zero = $row->RUN_TO_ZERO;
                $parent_product->vat_relief_qualified = empty($row->VAT_RELIEF_QUALIFIED) ? 0 : $row->VAT_RELIEF_QUALIFIED;
                $parent_product->stock_id = $row->STOCK_ID;
                $parent_product->stock_controlled = $row->STOCK_CONTROLLED;
                $parent_product->availability = $row->AVAILABILITY;
                $parent_product->meta_title = $row->META_TITLE;
                $parent_product->meta_description = $row->META_DESCRIPTION;
                $parent_product->meta_keywords = $row->META_KEYWORDS;
                $parent_product->max_display_qty = $row->MAX_DISPLAY_QTY;

                if (!empty($row->LAUNCH_DATE)) {
                    $parent_product->launch_date = $row->LAUNCH_DATE;
                }

                $parent_product->launch_time = $row->LAUNCH_TIME;
                $parent_product->reward_points = $row->REWARD_POINTS;
                $parent_product->reorder_multiple = $row->REORDER_MULTIPLE;
                $parent_product->purchase_multiple = $row->PURCHASE_MULTIPLE;
                $parent_product->min_level = $row->MIN_LEVEL;
                $parent_product->safe_level = $row->SAFE_LEVEL;
                $parent_product->height = $row->HEIGHT;
                $parent_product->width = $row->WIDTH;
                $parent_product->depth = $row->DEPTH;
                $parent_product->country_of_manufacture = $row->COUNTRY_OF_MANUFACTURE;
                $parent_product->imported = 0;
                $parent_product->save();

                Images::where('stock_code', '=', $parent_product->stock_code)->delete();

                if (!empty($row->STOCK_IMAGES)) {
                    foreach ($row->STOCK_IMAGES->STOCK_IMAGE as $stock_image) {

                        if (empty($stock_image->FILE_NAME)) {
                            continue;
                        }

                        $images = new Images;
                        $images->stock_code = $parent_product->stock_code;
                        $images->stock_image = $stock_image->STOCK_IMAGE;
                        $images->image_name = $stock_image->IMAGE_NAME;
                        $images->image_desc = $stock_image->IMAGE_DESC;
                        $images->file_name = $stock_image->FILE_NAME;
                        $images->image_type = $stock_image->IMAGE_TYPE;
                        $images->imported = 0;
                        $images->save();
                    }
                }


                if (!empty($row->SCS->STYLE)) {
                    foreach ($row->SCS->STYLE as $sub_row) {

                        $colour = $sub_row->DESCRIPTION;
                        $colour_suffix = $sub_row->SCS_CODE_SUFFIX;

                        $styles = [];
                        if (!empty($sub_row->STYLE)) {
                            foreach ($sub_row->STYLE as $style) {
                                $styles[] = $style;
                            }
                        } else {
                            $styles[] = $sub_row;
                        }

                        if (!empty($styles)) {
                            foreach ($styles as $style) {

                                $all_data = $style;
                                $style = $style->SCS_ITEM;

                                //$child_stock_codes[] = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', (string)$style->STOCK_CODE);
                                $child_stock_codes[] = (string)$style->STOCK_CODE;

                                $product = Product::where('stock_code', '=', $style->STOCK_CODE)->first();

                                if ($product == null) {
                                    $product = new Product;
                                }

                                if (empty($style->STOCK_CODE)) {
                                    continue;
                                }

                                echo "Processing: " . $parent_product->stock_code . "->" . $style->STOCK_CODE;
                                echo $this->cmd ? "\n" : "<br/>";

                                $product->parent_stock_code = $parent_product->stock_code;
                                $product->stock_code = $style->STOCK_CODE;
                                $product->colour = $colour;
                                $product->colour_suffix = $colour_suffix;
                                $product->size = $all_data->DESCRIPTION;
                                $product->size_suffix = $all_data->SCS_CODE_SUFFIX;
                                $product->description = $style->DESCRIPTION;
                                $product->buy_price = $style->BUY_PRICE;
                                $product->sell_price = $style->SELL_PRICE;
                                $product->sell_price_web = $style->SELL_PRICE_WEB;
                                $product->weight = $style->WEIGHT;
                                $product->stock_id = $style->STOCK_ID;
                                $product->long_desc = $style->LONG_DESC;
                                $product->web_teaser = $style->WEB_TEASER;
                                $product->deleted = $style->DISCONTINUED;
                                $product->web = $style->WEB;
                                $product->stock_controlled = $style->STOCK_CONTROLLED;
                                $product->discounted = $style->DISCONTINUED;
                                $product->drop_ship = $style->DROP_SHIP;
                                $product->discounts_disabled = $style->DISCOUNTS_DISABLED;
                                $product->run_to_zero = $style->RUN_TO_ZERO;
                                $product->vat_relief_qualified = $style->VAT_RELIEF_QUALIFIED;
                                $product->web_pageorder = $style->WEB_PAGEORDER;
                                $product->web_colourvalue = $style->WEB_COLOURVALUE;
                                $product->epos_desc = $style->EPOS_DESC;
                                $product->associated_ref = $style->ASSOCIATED_REF;
                                $product->availability = $style->AVAILABILITY;
                                $product->meta_title = $style->META_TITLE;
                                $product->meta_description = $style->META_DESCRIPTION;
                                $product->meta_keywords = $style->META_KEYWORDS;
                                $product->max_display_qty = $style->MAX_DISPLAY_QTY;

                                if (!empty($style->LAUNCH_DATE)) {
                                    $product->launch_date = $style->LAUNCH_DATE;
                                }

                                $product->launch_time = $style->LAUNCH_TIME;
                                $product->vat_rate = $style->VAT_RATE;
                                $product->stock_type = $style->STOCK_TYPE;
                                $product->stock_ttype = $style->STOCK_TTYPE;
                                $product->stock_mid_type = $style->STOCK_MID_TYPE;
                                $product->stock_sub_type = $style->STOCK_SUB_TYPE;
                                $product->lead_time = $style->LEAD_TIME;
                                $product->sub_type_id = !empty($style->SUB_TYPE_ID) ? $style->SUB_TYPE_ID : 0;
                                $product->reward_points = $style->REWARD_POINTS;
                                $product->reorder_multiple = $style->REORDER_MULTIPLE;
                                $product->purchase_multiple = $style->PURCHASE_MULTIPLE;
                                $product->min_level = $style->MIN_LEVEL;
                                $product->safe_level = $style->SAFE_LEVEL;
                                $product->height = $style->HEIGHT;
                                $product->width = $style->WIDTH;
                                $product->depth = $style->DEPTH;
                                $product->country_of_manufacture = $style->COUNTRY_OF_MANUFACTURE;
                                $product->supplier_stock_code = $style->SUPPLIER_STOCK_CODE;
                                $product->stock_option = json_encode($style->STOCK_OPTION);
                                $product->user_defined = json_encode($style->USER_DEFINED);
                                $product->barcode = !empty($style->BARCODES->BARCODE) ? $style->BARCODES->BARCODE : "";
                                $product->imported = 0;
                                $product->save();

                                Images::where('stock_code', '=', $product->stock_code)->delete();

                                if (isset($style->STOCK_IMAGES)) {

                                    foreach ($style->STOCK_IMAGES->STOCK_IMAGE as $stock_image) {

                                        if (empty($stock_image->FILE_NAME)) {
                                            continue;
                                        }

                                        $images = new Images;
                                        $images->stock_code = $product->stock_code;
                                        $images->stock_image = $stock_image->STOCK_IMAGE;
                                        $images->image_name = $stock_image->IMAGE_NAME;
                                        $images->image_desc = $stock_image->IMAGE_DESC;
                                        $images->file_name = $stock_image->FILE_NAME;
                                        $images->image_type = $stock_image->IMAGE_TYPE;
                                        $images->imported = 0;
                                        $images->save();
                                    }
                                }
                            }
                        }
                    }
                }

                if (!empty($child_stock_codes)) {
                    $chunked_child_stock_codes = array_chunk($child_stock_codes, 500);

                    foreach ($chunked_child_stock_codes as $key => $child_stock_codes) {
                        echo "Fetching stock: " . ($key + 1) . "/" . count($chunked_child_stock_codes);
                        echo $this->cmd ? "\n" : "<br/>";

                        try {
                            $stock_status = $this->soap_client->ExportStockStatus(implode(",", $child_stock_codes), 1);
                        } catch (\Exception $ex) {
                            foreach ($child_stock_codes as $child_stock_code) {
                                $failure = new Failures;
                                $failure->stock_code = $child_stock_code;
                                $failure->error = $ex;
                                $failure->save();
                            }
                        }

                        try {
                            $stock_data = $this->soap_client->GetStockPotential(implode(",", $child_stock_codes), 1);
                        } catch (\Exception $ex) {
                            foreach ($child_stock_codes as $child_stock_code) {
                                $failure = new Failures;
                                $failure->stock_code = $child_stock_code;
                                $failure->error = $ex;
                                $failure->save();
                            }
                        }

                        if (!empty($stock_status)) {
                            $stock_status = simplexml_load_string($stock_status, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

                            foreach ($stock_status as $stock_status_row) {

                                $row_code = reset($stock_status_row->attributes()->CODE);
                                $stock_status = StockStatus::where('stock_code', '=', $row_code)
                                    ->where('site', '=', $stock_status_row->SITE)
                                    ->first();

                                if ($stock_status == null) {
                                    $stock_status = new StockStatus;
                                }

                                $stock_status->stock_code = $row_code;
                                $stock_status->level = $stock_status_row->LEVEL;
                                $stock_status->site = $stock_status_row->SITE;
                                $stock_status->on_order_level = $stock_status_row->ON_ORDER_LEVEL;
                                $stock_status->max_bo_qty = $stock_status_row->MAX_BO_QTY;
                                $stock_status->buy_price = $stock_status_row->BUY_PRICE;
                                $stock_status->sell_price = $stock_status_row->SELL_PRICE;
                                $stock_status->websell_price = $stock_status_row->WEBSELL_PRICE;
                                $stock_status->bundle_price = $stock_status_row->BUNDLE_PRICE;
                                $stock_status->safe_level = $stock_status_row->SAFE_LEVEL;
                                $stock_status->min_level = $stock_status_row->MIN_LEVEL;
                                $stock_status->status = $stock_status_row->STATUS;
                                $stock_status->save();
                            }
                        }

                        if (!empty($stock_data)) {

                            foreach ($stock_data as $stock) {
                                $stock_level = StockLevel::where('stock_code', '=', $stock->StockCode)->first();
                                if ($stock_level == null) {
                                    $stock_level = new StockLevel;
                                }

                                $stock_level->stock_code = $stock->StockCode;
                                $stock_level->other_ref = $stock->OtherRef;
                                $stock_level->stock_desc = $stock->StockDesc;
                                $stock_level->available = $stock->Available;
                                $stock_level->on_order = $stock->OnOrder;
                                $stock_level->back_order = $stock->BackOrder;
                                $stock_level->potential = $stock->Potential;
                                $stock_level->imported = 0;
                                $stock_level->save();
                            }
                        }
                    }
                }
                break;

            case "ExportCompany":

                $row = Company::where('id', '=', $data->COMPANY_ID)->first();
                if ($row == null) {
                    $row = new Company;
                }

                $ref = $data->COMPANY_ID;

                $row->id = $data->COMPANY_ID;
                $row->company_code = $data->COMPANY_CODE;
                $row->company_name = $data->COMPANY_NAME;
                $row->other_ref = $data->OTHER_REF;
                $row->web_site = $data->WEB_SITE;
                $row->company_class = $data->COMPANY_CLASS;
                $row->company_type = $data->COMPANY_TYPE;
                $row->company_status = $data->COMPANY_STATUS;
                $row->source_code = $data->SOURCE_CODE;
                $row->sale_source = $data->SALE_SOURCE;
                $row->currency_code = $data->CURRENCY_CODE;
                $row->country = $data->COUNTRY;
                $row->country_code = $data->COUNTRY_CODE;
                $row->web_user = $data->WEB_USER;
                $row->web_password = $data->WEB_PASSWORD;
                $row->agent_name = $data->AGENT_NAME;
                $row->tax_reference = $data->TAX_REFERENCE;
                $row->hide_company = $data->HIDE_COMPANY;
                $row->hold_data = $data->HOLD_DATA;
                $row->mailing_status = $data->MAILING_STATUS;
                $row->proforma = $data->PROFORMA;
                $row->sorder_locked = $data->SORDER_LOCKED;
                $row->customer_discount = $data->CUSTOMER_DISCOUNT;
                $row->supplier = $data->SUPPLIER;
                $row->ec_company = $data->EC_COMPANY;
                $row->pays_vat = $data->PAYS_VAT;
                $row->pocode_required = $data->POCODE_REQUIRED;
                $row->reward_point_balance = $data->REWARD_POINT_BALANCE;
                $row->reward_points_updated = $data->REWARD_POINTS_UPDATED;
                $row->payment_type = $data->PAYMENT_TYPE;
                $row->earn_and_redeem_reward_points = $data->EARN_AND_REDEEM_REWARD_POINTS;
                $row->vat_relief_qualified = $data->VAT_RELIEF_QUALIFIED;
                $row->credit_code = $data->CREDIT_CODE;
                $row->imported = 0;
                $row->save();

                foreach ($data->ADDRESSES as $address) {
                    if (empty($address->ADDRESS)) {
                        continue;
                    }

                    foreach ($address->ADDRESS as $address_row) {
                        $temp = CompanyAddress::where('id', '=', $address_row->ADDRESS_ID)->first();
                        if ($temp == null) {
                            $temp = new CompanyAddress;
                        }

                        $temp->id = $address_row->ADDRESS_ID;
                        $temp->company_id = $row->id;
                        $temp->addr1 = $address_row->ADDR1;
                        $temp->addr2 = $address_row->ADDR2;
                        $temp->addr3 = $address_row->ADDR3;
                        $temp->town = $address_row->TOWN;
                        $temp->county = $address_row->COUNTY;
                        $temp->postcode = $address_row->POSTCODE;
                        $temp->organisation = $address_row->ORGANISATION;
                        $temp->addrtype = $address_row->ADDRTYPE;
                        $temp->email = $address_row->EMAIL;
                        $temp->tel = $address_row->TEL;
                        $temp->fax = $address_row->FAX;
                        $temp->country = $address_row->COUNTRY;
                        $temp->country_code = $address_row->COUNTRY_CODE;
                        $temp->hide_address = $address_row->HIDE_ADDRESS;
                        $temp->hold_data = $address_row->HOLD_DATA;
                        $temp->is_preferred_delivery = $address_row->IS_PREFERRED_DELIVERY;
                        $temp->imported = 0;
                        $temp->save();

                        foreach ($address_row->CONTACTS as $contact) {
                            if (empty($contact->CONTACT)) {
                                continue;
                            }

                            foreach ($contact->CONTACT as $contact_row) {

                                $temp = CompanyAddressContact::where('id', '=', $contact_row->CONTACT_ID)->first();
                                if ($temp == null) {
                                    $temp = new CompanyAddressContact;
                                }

                                $temp->id = $contact_row->CONTACT_ID;
                                $temp->address_id = $address_row->ADDRESS_ID;
                                $temp->title = $contact_row->TITLE;
                                $temp->forename = $contact_row->FORENAME;
                                $temp->surname = $contact_row->SURNAME;
                                $temp->jobtitle = $contact_row->JOBTITLE;
                                $temp->tel = $contact_row->TEL;
                                $temp->fax = $contact_row->FAX;
                                $temp->mobile = $contact_row->MOBILE;
                                $temp->email = $contact_row->EMAIL;
                                $temp->note = $contact_row->NOTE;
                                $temp->emailsubscribe = $contact_row->EMAILSUBSCRIBE;
                                $temp->dob = $contact_row->DOB;
                                $temp->hide_contact = $contact_row->HIDE_CONTACT;
                                $temp->hold_data = $contact_row->HOLD_DATA;
                                $temp->is_preferred = $contact_row->IS_PREFERRED;
                                $temp->is_preferred_invoice = $contact_row->IS_PREFERRED_INVOICE;
                                $temp->is_preferred_delivery = $contact_row->IS_PREFERRED_DELIVERY;
                                $temp->mailing_flag = empty($contact_row->MAILING_FLAG) ? 0 : $contact_row->MAILING_FLAG;
                                $temp->imported = 0;
                                $temp->save();
                            }
                        }
                    }
                }

                break;
            case "GetCompanyClass":

                $ref = $data;
                $company_class = new CompanyClass;
                $company_class->name = $data;
                $company_class->imported = 0;
                $company_class->save();

                break;
            case "GetCompanyList":

                $ref = $data->CompanyCode;
                $company_list = new CompanyList;
                $company_list->company_code = $data->CompanyCode;
                $company_list->other_ref = $data->OtherRef;
                $company_list->imported = 0;
                $company_list->save();

                break;
            case "GetCompanyStatus":

                $ref = $data;
                $company_status = new CompanyStatus;
                $company_status->name = $data;
                $company_status->imported = 0;
                $company_status->save();

                break;
            case "GetCountryList":

                $ref = $data->CountryName;
                $country_list = new CountryList;
                $country_list->country_name = $data->CountryName;
                $country_list->country_code2 = $data->CountryCode2;
                $country_list->country_code3 = $data->CountryCode3;
                $country_list->eu_member = $data->EUMember;
                $country_list->pays_vat = $data->PaysVAT;
                $country_list->currency_name = $data->CurrencyName;
                $country_list->currency_code = $data->CurrencyCode;
                $country_list->zone = $data->Zone;
                $country_list->imported = 0;
                $country_list->save();

                break;
            case "GetKeycodeTypes":

                $ref = $data;
                $keycode_type = new KeycodeType;
                $keycode_type->name = $data;
                $keycode_type->imported = 0;
                $keycode_type->save();

                break;
            case "GetKeycodes":

                $ref = $data->KeycodeCode;
                $keycode = new Keycode;
                $keycode->keycode_code = $data->KeycodeCode;
                $keycode->keycode_type_desc = $data->KeycodeTypeDesc;
                $keycode->keycode_desc = $data->KeycodeDesc;
                $keycode->source_code = $data->SourceCode;
                $keycode->web_use = $data->WebUse;
                $keycode->motouse = $data->MOTOUse;
                $keycode->imported = 0;
                $keycode->save();

                break;
            case "GetMailingStatusList":

                $mailing_status = MailingStatus::where('id', '=', $data->ID)->first();
                if ($mailing_status == null) {
                    $mailing_status = new MailingStatus;
                }

                $ref = $data->Description;
                $mailing_status->id = $data->ID;
                $mailing_status->description = $data->Description;
                $mailing_status->imported = 0;
                $mailing_status->save();

                break;
            case "GetManufacturer":

                $ref = $data;
                $manufacturer = new Manufacturer();
                $manufacturer->name = $data;
                $manufacturer->imported = 0;
                $manufacturer->save();

                break;
            case "GetSaleSource":

                $ref = $data;
                $sale_source = new SaleSource;
                $sale_source->name = $data;
                $sale_source->imported = 0;
                $sale_source->save();

                break;
            default:
                echo "TODO - " . $type . "\n";
                exit;
                break;

        }

        echo ".......Finish Row.......";
        echo $this->cmd ? "\n" : "<br/>";
    }

}
