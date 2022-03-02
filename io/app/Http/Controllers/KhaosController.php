<?php

namespace App\Http\Controllers;

use App\Company;
use App\CompanyAddress;
use App\CompanyAddressContact;
use App\CompanyClass;
use App\CompanyList;
use App\CompanyStatus;
use App\CountryList;
use App\DefaultBillingAddress;
use App\Keycode;
use App\KeycodeType;
use App\MailingStatus;
use App\Manufacturer;
use App\Order_Address;
use App\Order_Grid;
use App\Order_History;
use App\Order_Item;
use App\Order_Payment;
use App\SaleSource;
use App\PriceListStockItems;
use App\PriceLists;
use App\KhaosControl;
use App\Order;
use App\Site_Migration;
use App\StockLevel;
use App\Images;
use App\Product;
use App\ParentProduct;
use App\Sites;
use App\Customer;
use App\StockStatus;

use Magento\CatalogInventory\Model\Stock;
use SoapClient;

use DB;
use mysql_xdevapi\Exception;
use Redirect;
use Schema;

use ArgumentSequence\ParentRequiredObject;
use Illuminate\Http\Request;
use App\Http\Requests;

class KhaosController extends Controller
{
    public $web_services, $magento_db;

    public function __construct()
    {
        ini_set('default_socket_timeout', 600);

        $this->magento_db = DB::connection('mysql2');

        $this->export_services = [
            'Push Orders' => 'https://www.wholesalelingerie.co.uk/io/public/orders',
            'Migrate Products' => 'http://www.wholesalelingerie.co.uk/io/public/migrate/products',
            'Migrate Products (SKU)' => 'http://www.wholesalelingerie.co.uk/io/public/migrate/products?sku=ABBEY-03',
            'Migrate Products (Image Debug w/SKU)' => 'http://www.wholesalelingerie.co.uk/io/public/migrate/products?sku=ABBEY-03&image_debug=Y',
            'Migrate Products (Debug w/SKU)' => 'http://www.wholesalelingerie.co.uk/io/public/migrate/products?sku=ABBEY-03&debug=Y',
            'Migrate Products (All)' => 'http://www.wholesalelingerie.co.uk/io/public/migrate/products/all',
            'Migrate Categories' => 'http://www.wholesalelingerie.co.uk/io/public/migrate/categories/all',
            'Migrate Price Lists' => 'https://wholesalelingerie.co.uk/io/public/migrate/pricelists',
            'Save Products' => 'http://www.wholesalelingerie.co.uk/save-product.php',
            'Remove Empty Categories' => 'https://www.wholesalelingerie.co.uk/removeEmptyCats.php',
            'Pull Price Lists' => 'https://wholesalelingerie.co.uk/io/public/request/ExportPriceLists',
            'Export Stock (Choose SKU)' => 'http://www.wholesalelingerie.co.uk/io/public/request/GetStockList/5?stock_code=ABBEY-03',
            'Export Stock (5 Mins)' => 'http://www.wholesalelingerie.co.uk/io/public/request/GetStockList/5',
            'Export Stock (60 Mins)' => 'http://www.wholesalelingerie.co.uk/io/public/request/GetStockList/60',
            'Export Stock (12 Hours)' => 'http://www.wholesalelingerie.co.uk/io/public/request/GetStockList/720',
            'Export Stock (24 Hours)' => 'http://www.wholesalelingerie.co.uk/io/public/request/GetStockList/1440',
        ];

        $tables = [
            'parent_product',
            'product',
            'images',
            'stock_levels',
            'price_lists',
            'price_list_stock_items',
            'order',
            'sites',
            'sale_source',
            'order_item',
            'order_payment',
            'order_history',
            'order_grid',
            'order_address',
            'manufacturer',
            'mailing_status',
            'keycode_types',
            'keycodes',
            'customer',
            'country_list',
            'company_status',
            'company_list',
            'company_class',
            'company_address_contact',
            'company_address',
            'company'
        ];

        foreach ($tables as $table) {
            $this->export_services['Export Table: '.ucwords(str_replace("_", " ", $table))] = 'https://www.wholesalelingerie.co.uk/io/public/export/'.$table;
        }

        $this->import_services = [
            'ImportCatalogueRequest' => 'KSDXMLCatRequest',
            'ImportOrders' => 'KSDXMLImportFormat',
            'ImportCompany' => 'KSDXMLCustomer',
            'ImportStockStatus' => 'KSDXMLStockStatus'
        ];

        $this->khaos_web_service = new KhaosControl();
        $this->khaos_kls_web_service = new KhaosControl('kls');

        if (!empty($_GET['request'])) {
            if (!in_array($_GET['request'], $this->export_services)) {
                die ("'".$_GET['request']."' is not a valid web service, please try again");
            }
            $this->khaos_web_service->request($_GET['request']);
        } elseif (!empty($_GET['scan'])) {

            $dir = "../public/khaos/in/";
            $cdir = scandir($dir);

            $files = [];

            if (!empty($cdir)) {
                foreach ($cdir as $item) {
                    if ($item != "."  && $item != "..") {
                        $split = explode("_", str_replace(".xml", "", $item));
                        $files[] = [
                            'type' => $split[0],
                            'date/time' => date("Y-m-d G:i:s", $split[1]),
                            'filepath' => $dir.$item,
                            'contents' => file_get_contents($dir.$item)
                        ];
                    }
                }
            }

            if (!empty($files)) {
                echo "<pre>";
                print_r($files);
                exit;
            }
        }

    }

    private function attributes()
    {
        $attribute_option_values = [];
        foreach ($this->magento_db->table('eav_attribute_option_value')->get() as $row) {
            $attribute_option_values[$row->option_id][$row->value_id] = $row;
        }

        $attribute_option_swatches = [];
        foreach ($this->magento_db->table('eav_attribute_option_swatch')->get() as $row) {
            $attribute_option_swatches[$row->option_id][$row->swatch_id] = $row;
        }

        $attribute_options = [];
        foreach ($this->magento_db->table('eav_attribute_option')->get() as $row) {
            $attribute_options[$row->attribute_id][$row->option_id] = $row;
        }

        $attribute_groups = [];
        foreach ($this->magento_db->table('eav_attribute_group')->get() as $row) {
            $attribute_groups[$row->attribute_group_id] = $row;
        }

        $attribute_labels = [];
        foreach ($this->magento_db->table('eav_attribute_label')->get() as $row) {
            $attribute_labels[$row->attribute_id][$row->attribute_label_id] = $row;
        }

        $this->attributes = [];
        foreach ($this->magento_db->table('eav_attribute')->get() as $row) {
            $this->attributes[$row->attribute_id] = $row;

            if (isset($attribute_labels[$row->attribute_id])) {
                $this->attributes[$row->attribute_id]->labels = $attribute_labels[$row->attribute_id];
            }
            if (isset($attribute_options[$row->attribute_id])) {
                $this->attributes[$row->attribute_id]->options = $attribute_options[$row->attribute_id];
                foreach ($this->attributes[$row->attribute_id]->options as &$option) {
                    if (isset($attribute_option_swatches[$option->option_id])) {
                        $option->swatches = $attribute_option_swatches[$option->option_id];
                    }
                    if (isset($attribute_option_values[$option->option_id])) {
                        $option->values = $attribute_option_values[$option->option_id];
                    }
                }
            }
        }

        return $this->attributes;
    }

    public function csv($table)
    {
        $columns = [];
        $exclude_columns = ['imported', 'stock_option', 'user_defined'];
        $csv_name = "";

        $additional_data = [];

        switch($table) {
            case "parent_product":
                $class = new ParentProduct;
                $columns = $class->getColumns();
                $data = ParentProduct::all();
                $exclude_columns[] = 'buy_price';
                break;
            case "price_lists":
                $class = new PriceLists;
                $columns = $class->getColumns();
                $data = PriceLists::all();
                break;
            case "price_list_stock_items":
                $class = new PriceListStockItems;
                $columns = $class->getColumns();
                $data = PriceListStockItems::all();
                break;
            case "product":
                $class = new Product;
                $columns = $class->getColumns();
                $data = Product::all();
                $exclude_columns[] = 'buy_price';
                break;
            case "order":
                $class = new Order;
                $columns = $class->getColumns();
                $data = Order::all();
                break;
            case "images":
                $class = new Images;
                $columns = $class->getColumns();
                $data = Images::all();
                foreach ($data as &$row) {
                    $row->image_name  = str_replace("P:\Khaos\Images\\", "", $row->file_name);
                    $row->file_name = str_replace("P:\Khaos\Images\\", "", $row->file_name);
                }
                break;
            case "stock_levels":
                $class = new StockLevel;
                $columns = $class->getColumns();
                $data = StockLevel::all();
                break;
            case "sites":
                $class = new Sites;
                $columns = $class->getColumns();
                $data = Sites::all();
                break;
            case "sale_source":
                $class = new SaleSource;
                $columns = $class->getColumns();
                $data = SaleSource::all();
                break;
            case "order_item":
                $class = new Order_Item;
                $columns = $class->getColumns();
                $data = Order_Item::all();
                break;
            case "order_payment":
                $class = new Order_Payment;
                $columns = $class->getColumns();
                $data = Order_Payment::all();
                break;
            case "order_history":
                $class = new Order_History;
                $columns = $class->getColumns();
                $data = Order_History::all();
                break;
            case "order_grid":
                $class = new Order_Grid;
                $columns = $class->getColumns();
                $data = Order_Grid::all();
                break;
            case "order_address":
                $class = new Order_Address;
                $columns = $class->getColumns();
                $data = Order_Address::all();
                break;
            case "manufacturer":
                $class = new Manufacturer;
                $columns = $class->getColumns();
                $data = Manufacturer::all();
                break;
            case "mailing_status":
                $class = new MailingStatus;
                $columns = $class->getColumns();
                $data = MailingStatus::all();
                break;
            case "keycode_types":
                $class = new KeycodeType;
                $columns = $class->getColumns();
                $data = KeycodeType::all();
                break;
            case "keycodes":
                $class = new Keycode;
                $columns = $class->getColumns();
                $data = Keycode::all();
                break;
            case "customer":
                $class = new Customer;
                $columns = $class->getColumns();
                $data = Customer::all();
                break;
            case "country_list":
                $class = new CountryList;
                $columns = $class->getColumns();
                $data = CountryList::all();
                break;
            case "company_status":
                $class = new CompanyStatus;
                $columns = $class->getColumns();
                $data = CompanyStatus::all();
                break;
            case "company_list":
                $class = new CompanyList;
                $columns = $class->getColumns();
                $data = CompanyList::all();
                break;
            case "company_class":
                $class = new CompanyClass;
                $columns = $class->getColumns();
                $data = CompanyClass::all();
                break;
            case "company_address_contact":
                $class = new CompanyAddressContact;
                $columns = $class->getColumns();
                $data = CompanyAddressContact::all();
                break;
            case "company_address":
                $class = new CompanyAddress;
                $columns = $class->getColumns();
                $data = CompanyAddress::all();
                break;
            case "company":
                $class = new Company;
                $columns = $class->getColumns();
                $data = Company::all();
                break;
            case "custom":

                $start = 0;
                $limit = 15000;

                if (isset($_GET['start'])) {
                    $start = $_GET['start'];
                }
                if (isset($_GET['limit'])) {
                    $limit = $_GET['limit'];
                }

                if (isset($_GET['debug'])) {
                    $limit = 999999999;
                }

                if (isset($_GET['platform'])) {

                    switch ($_GET['platform']) {

                        case "woocommerce":
                            $columns = [
                                'stock_code',
                                'description',
                                'parent_stock_type',
                                'parent_stock_code',
                                'stock_available',
                                'post_type',
                                'image_1',
                                'image_2'
                            ];

                            $additional_data = [
                                'post_type' => 'product'
                            ];

                            break;
                        case "magento":
                            $columns = [
                                'stock_code',
                                'parent_stock_type',
                                'parent_stock_code',
                                'stock_available',
                            ];
                            break;
                        case "shopify":
                            $columns = [
                                'stock_code',
                                'description',
                                'parent_stock_type',
                                'parent_stock_code',
                                'stock_available',
                            ];
                            break;

                    }

                } else {
                    $columns = [
                        'stock_code',
                        'description',
                        'parent_stock_type',
                        'parent_stock_code',
                        'stock_available',
                    ];
                }

                $platform = "custom";
                if (isset($_GET['platform'])) {
                    $platform = $_GET['platform'];
                }

                $product_instance = new Product;
                $parent_product_instance = new ParentProduct;
                $stock_level_instance = new StockLevel;

                //$ps_columns = $product_instance->getColumns();
                $sls = StockLevel::all();
                $sls_columns = $stock_level_instance->getColumns();

                $pps_columns = $parent_product_instance->getColumns();

                $count = 0;
                $products = [];
                $stock_levels = [];
                $parent_products = [];
                $data = [];

                $images_raw = file_get_contents("../../exportfiles/images.csv","r");
                $image_csv = explode(PHP_EOL, $images_raw);
                $images = [];
                foreach ($image_csv as $line) {
                    $array = str_getcsv($line);
                    if (
                        isset($array[1]) &&
                        !empty($array[4]) &&
                        $array[4] != '-'
                    ) {
                        $images[$array[1]][] = str_replace("P:\Khaos\Images\\", "", $array[4]);
                    }
                }

                if (isset($_GET['brand'])) {

                    $_GET['brand'] = str_replace("___", " ", $_GET['brand']);

                    $pps = ParentProduct::with('skus')->where('manufacturer', '=', $_GET['brand'])->get();
                    foreach ($pps as $parent_product) {
                        $parent_products[$parent_product->stock_code] = $parent_product;

                        if (count($parent_product->skus) == 0) {
                            $products[$parent_product->stock_code] = $parent_product;
                        } else {
                            foreach ($parent_product->skus as $product) {
                                $products[$product->stock_code] = $product;
                            }
                        }
                    }
                    foreach ($sls as $stock_level) {
                        $stock_levels[$stock_level->stock_code] = $stock_level;
                    }

                } else {
                    $pps = ParentProduct::all();
                    $ps = Product::all();

                    foreach ($pps as $parent_product) {
                        $parent_products[$parent_product->stock_code] = $parent_product;
                    }
                    foreach ($ps as $product) {
                        $products[$product->stock_code] = $product;
                    }
                    foreach ($sls as $stock_level) {
                        $stock_levels[$stock_level->stock_code] = $stock_level;
                    }
                }

                foreach ($products as $stock_code => $product) {

                    $continue = 0;
                    if ($count >= $start && $count <= ($start + $limit)) {
                        $continue = 1;
                    } elseif ($count <= $start) {
                        $count++;
                        continue;
                    }

                    $count++;
                    if ($continue == 0) {
                        break;
                    }

                    foreach ($pps_columns as $column) {
                        if (isset($parent_products[$product->parent_stock_code]->{$column})) {
                            $product->{'parent_'.$column} = $parent_products[$product->parent_stock_code]->{$column};
                        }
                    }
                    foreach ($sls_columns as $column) {
                        if (isset($stock_levels[$product->stock_code]->{$column})) {
                            $product->{'stock_'.$column} = $stock_levels[$product->stock_code]->{$column};
                        }
                    }
                    if (isset($parent_products[$product->parent_stock_code])) {
                        $data[] = $product;
                    } elseif (isset($parent_products[$product->stock_code])) {
                        foreach ($pps_columns as $column) {
                            if (isset($parent_products[$product->stock_code]->{$column})) {
                                $product->{'parent_'.$column} = $parent_products[$product->stock_code]->{$column};
                            }
                        }
                        $data[] = $product;
                    }

                    if (isset($_GET['debug'])) {
                        echo "found: ";
                        echo count($products);
                        echo "<pre>";
                        print_r($data);exit;
                    }
                }

                break;
        }

        if (!empty($exclude_columns)) {
            foreach ($columns as $key => $column) {
                foreach ($exclude_columns as $excluded_column) {
                    if ($excluded_column == $column) {
                        unset($columns[$key]);
                    }
                }
            }
        }

        if (empty($columns)) {
            die("No data found");
        }

        if (isset($platform)) {
            $csv_name = $platform;
            if (isset($_GET['brand'])) {
                $csv_name .= "_".$_GET['brand'];
            }
        } else {
            $csv_name = $table;
        }

        $csv = [];

        foreach ($data as $key => $row) {
            foreach ($columns as $column) {
                if (is_null($row->{$column}) || isset($row->{$column})) {
                    $csv[$key][$column] = $row->{$column};
                }
            }
            foreach ($additional_data as $row_key => $row_val) {
                $csv[$key][$row_key] = $row_val;
            }
        }

        foreach ($csv as &$csv_row) {
            if (!empty($images[$csv_row['stock_code']])) {
                $images_row = $images[$csv_row['stock_code']];
                $img_count = 1;
                foreach ($images_row as $image) {
                    $csv_row['image_'.$img_count] = $image;
                    $img_count++;
                }
            }
        }

//        header("Content-type: text/csv");
//        header("Content-Disposition: attachment; filename=".$csv_name.".csv");
//        header("Pragma: no-cache");
//        header("Expires: 0");

        $filename = "../../exportfiles/".$csv_name.".csv";

        $file = fopen($filename, 'wb');
        fputcsv($file, $columns);
        foreach($csv as $row) {
            fputcsv($file, $row);
        }

        exit("Moved to ".$filename);
    }

    public function stock_sync()
    {
        $this->khaos_endpoint = 'https://77.107.185.72:4433/khaoskev.exe/wsdl/IKosWeb';
        $this->endpoint = $this->khaos_endpoint;

        $opts = [
            'ssl' => [
                'ciphers' => 'RC4-SHA',
                'verify_peer' => false,
                'verify_peer_name' => false
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
        $this->soap_client = new SoapClient($this->endpoint, $params);

        if (isset($_GET['reset'])) {
            DB::table('stock_levels')->update(['imported' => 0]);
            echo "Reset all stock for sync.";
            exit;
        }

        $already_done_stock_codes = [];
        if (isset($_GET['sku'])) {
            $parent_products = ParentProduct::where('stock_code', '=', $_GET['sku'])->get();
            $products = Product::where('parent_stock_code', '=', $_GET['sku'])->get();
        } else {
            $already_done_stock_codes = StockLevel::where('imported','=', 1)->get();
            $parent_products = ParentProduct::all();
            $products = Product::all();
        }

        $already_done = [];
        $stock_codes = [];

        foreach ($already_done_stock_codes as $stock) {
            $already_done[$stock->stock_code] = $stock->stock_code;
        }

        foreach ($parent_products as $product) {
            if (!in_array($product->stock_code, $already_done)) {
                $stock_codes[$product->stock_code] = $product->stock_code;
            }
        }

        foreach ($products as $product) {
            if (!in_array($product->stock_code, $already_done)) {
                $stock_codes[$product->stock_code] = $product->stock_code;
            }
        }

        $loop_count = 0;
        if (!empty($stock_codes)) {
            $chunked_child_stock_codes = array_chunk($stock_codes, 500);

            foreach ($chunked_child_stock_codes as $key => $child_stock_codes) {
                echo "Fetching stock: " . ($key + 1) . "/" . count($chunked_child_stock_codes);
                echo $this->cmd ? "\n" : "<br/>";

                try {
                    $stock_status = $this->soap_client->ExportStockStatus(implode(",", $child_stock_codes), 1);
                    $stock_status = simplexml_load_string($stock_status, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

                    $stock_data = $this->soap_client->GetStockPotential(implode(",", $child_stock_codes), 1);
                } catch (\Exception $ex) {
                }

                if (!empty($stock_data) && !empty($stock_status)) {

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
                        $stock_level->imported = 1;
                        $stock_level->save();
                    }
                }
                $loop_count++;

                if ($loop_count > 15) {
                    break;
                }
            }
        }

        foreach ($parent_products as $parent_product) {
            $parent_product->imported = 0;
            $parent_product->save();
        }
    }

    public function index()
    {
        $page_description = "KEVCO Wholesale API Management Suite";
        $page_title = "KEVCO Wholesale API Management Suite";

        return View('dashboard', [
            'web_services' => $this->export_services,
            'page_description' => $page_description,
            'page_title' => $page_title,
        ]);
    }

    public function stock_code()
    {
        $page_description = "KEVCO Wholesale API Management Suite";
        $page_title = "KEVCO Wholesale API Management Suite";

        return View('stock-code', [
            'page_description' => $page_description,
            'page_title' => $page_title,
        ]);
    }

    public function sites($site_id = 0)
    {
        $page_description = "KEVCO Wholesale API Management Suite";
        $page_title = "KEVCO Wholesale API Management Suite";

        if (!empty($_POST['brands'])) {
            foreach ($_POST['brands'] as $site_id => $brands) {
                $site = Sites::where('id', '=', $site_id)->first();
                $site->brands = implode(",", $brands);
                $site->save();
            }
        }
        if (!empty($_POST['categories'])) {
            foreach ($_POST['categories'] as $site_id => $categories) {
                $site = Sites::where('id', '=', $site_id)->first();
                $site->categories = implode(",", $categories);
                $site->save();
            }
        }

        $raw = DB::select('SELECT manufacturer, stock_ttype, stock_code FROM parent_product');

        $brands = [];
        $categories = [];

        // STOCK_TTYPE = 1, STOCK_TYPE = 2, STOCK_SUB_TYPE = 3 (CATEGORIES)
        // MANFACTURER = BRAND

        foreach ($raw as $row) {
            $brands[strtolower($row->manufacturer)] = $row->manufacturer;
            $categories[strtolower($row->stock_ttype)] = $row->stock_ttype;
        }

        $sites = Sites::where('active', '=', 1)->where('visible','=',1)->get();
        $site_brands = [];
        $site_categories = [];

        foreach ($sites as $site) {
            $site_brands[$site->id] = array_filter(explode(",", strtolower($site->brands)));
            $site_categories[$site->id] = array_filter(explode(",", strtolower($site->categories)));
        }

        return View('sites', [
            'sites' => $sites,
            'categories' => $categories,
            'brands' => $brands,
            'site_brands' => $site_brands,
            'site_categories' => $site_categories,
            'page_description' => $page_description,
            'page_title' => $page_title,
        ]);

    }

    public function data($table, $stock_code = "")
    {
        $show_link = 0;
        $data = [];
        $headers = [];
        $parent_fields = [];
        $child_fields = [];
        $stock_fields = [];
        $page_description = "KEVCO Wholesale API Management Suite";
        $page_title = "KEVCO Wholesale API Management Suite";

        if (!empty($stock_code)) {
            switch ($table) {
                case "parent_product":
                    $data = ParentProduct::with('skus', 'skus.images', 'parent_images', 'parent_stock_levels')
                        ->where('stock_code', '=', $stock_code)
                        ->first();

                    $child_fields = [
                        'id',
                        'parent_stock_code',
                        'stock_code',
                        'colour',
                        'colour_suffix',
                        'size',
                        'size_suffix',
                        'description',
                        'buy_price',
                        'sell_price',
                        'sell_price_web',
                        'weight',
                        'stock_id',
                        'long_desc',
                        'web_teaser',
                        'deleted',
                        'web',
                        'stock_controlled',
                        'discounted',
                        'drop_ship',
                        'discounts_disabled',
                        'run_to_zero',
                        'var_relief_qualified',
                        'web_pageorder',
                        'web_colourvalue',
                        'epos_desc',
                        'associated_ref',
                        'availability',
                        'meta_title',
                        'meta_description',
                        'meta_keywords',
                        'max_display_qty',
                        'launch_date',
                        'launch_time',
                        'vat_rate',
                        'stock_type',
                        'stock_ttype',
                        'stock_mid_type',
                        'stock_sub_type',
                        'lead_time',
                        'sub_type_id',
                        'reward_points',
                        'reorder_multiple',
                        'purchase_multiple',
                        'min_level',
                        'safe_level',
                        'height',
                        'width',
                        'depth',
                        'country_of_manufacture',
                        'supplier_stock_code',
                        'stock_option',
                        'user_defined',
                        'barcode',
                    ];

                    $parent_fields = [
                        'id',
                        'stock_code',
                        'associated_ref',
                        'stock_desc',
                        'long_desc',
                        'epos_desc',
                        'web_teaser',
                        'buy_price',
                        'sell_price',
                        'sell_price_web',
                        'image_filename',
                        'vat_rate',
                        'stock_type',
                        'stock_type_id',
                        'stock_mid_type',
                        'stock_mid_type_id',
                        'sub_type',
                        'stock_sub_type',
                        'sub_type_id',
                        'manufacturer',
                        'supplier_stock_code',
                        'lead_time',
                        'weight',
                        'stock_ttype',
                        'stock_ttype_id',
                        'web_pageorder',
                        'web_colourvalue',
                        'deleted',
                        'web',
                        'discounted',
                        'drop_ship',
                        'discounts_disabled',
                        'run_to_zero',
                        'vat_relief_qualified',
                        'stock_id',
                        'stock_controlled',
                        'availability',
                        'meta_title',
                        'meta_description',
                        'meta_keywords',
                        'max_display_qty',
                        'launch_date',
                        'launch_time',
                        'reward_points',
                        'reorder_multiple',
                        'purchase_multiple',
                        'min_level',
                        'safe_level',
                        'height',
                        'width',
                        'depth',
                        'country_of_manufacture',
                        'imported',
                    ];

                    $stock_fields = [
                        'stock_code',
                        'other_ref',
                        'stock_desc',
                        'available',
                        'on_order',
                        'back_order',
                        'potential',
                    ];
                    break;
            }

            if (empty($data)) {
                return;
            }

            return View('data-'.$table, [
                'data' => $data,
                'table' => $table,
                'page_title' => $page_title,
                'page_description'=> $page_description,
                'parent_fields' => $parent_fields,
                'child_fields' => $child_fields,
                'stock_fields' => $stock_fields,
            ]);
        }

        switch ($table) {
            case "parent_product":
                $data = DefaultBillingAddress::all();
                $headers = [
                    'id',
                    'entity_id',
                    'stock_code',
                    'sell_price',
                    'stock_ttype',
                    'stock_type',
                    'sub_type',
                    'manufacturer',
                    'imported'
                ];
                $show_link = 1;
                break;
            case "parent_product":
                $data = ParentProduct::all();
                $headers = [
                    'id',
                    'entity_id',
                    'stock_code',
                    'sell_price',
                    'stock_ttype',
                    'stock_type',
                    'sub_type',
                    'manufacturer',
                    'imported'
                ];
                $show_link = 1;
                break;
            case "product":
                $data = Product::all();
                $headers = [
                    'id',
                    'parent_stock_code',
                    'stock_code',
                    'sell_price',
                    'stock_ttype',
                    'stock_type',
                    'sub_type',
                    'manufacturer',
                ];
                break;
            case "image":
                $data = Images::all();
                $headers = [
                    'id',
                    'stock_code',
                    'image_name',
                    'file_name',
                ];

                break;
            case "stock_levels":
                $data = StockLevel::all();
                $headers = [
                    'id',
                    'stock_code',
                    'available',
                    'on_order',
                    'back_order',
                    'potential',
                ];
                break;
            case "order":
                $data = Order::all();
                $headers = [
                    'id',
                    'entity_id',
                    'state',
                    'status',
                    'coupon_code',
                    'shipping_description',
                    'grand_total',
                    'sub_total',
                    'shipping_description',
                    'tax_amount',
                    'shipping_amount',
                    'shipping_tax',
                    'discount_amount',
                    'customer_email',
                    'customer_firstname',
                    'customer_surname',
                    'currency_code',
                    'ip_address',
                    'shipping_method',
                    'order_date',
                    'imported',
                ];
                break;
            case "customer":
                $data = Customer::all();
                $headers = [
                    'id',
                    'entity_id',
                    'first_name',
                    'surname',
                    'email',
                ];
                break;
            case "order_address":
                $data = Order_Address::all();
                $headers = [
                    'id',
                    'entity_id',
                    'first_name',
                    'surname',
                    'email',
                ];
                break;
            case "order_grid":
                $data = Order_Grid::all();
                $headers = [
                    'id',
                    'entity_id',
                    'base_grand_total',
                    'base_total_paid',
                    'grand_total',
                    'total_paid',
                    'base_currency_code',
                    'order_currency_code',
                    'shipping_name',
                    'billing_name',
                    'billing_address',
                    'shipping_address',
                    'shipping_information',
                    'customer_email',
                    'subtotal',
                    'shipping_and_handling',
                    'customer_name',
                    'payment_method',
                    'total_refunded',
                ];
                break;
            case "order_history":
                $data = Order_History::all();
                $headers = [
                    'id',
                    'entity_id',
                    'comment',
                    'status',
                    'entity_name',
                ];
                break;
            case "order_item":
                $data = Order_Item::all();
                $headers = [
                    'id',
                    'item_id',
                    'order_id',
                    'product_id',
                    'product_type',
                    'product_options',
                    'weight',
                    'sku',
                    'product_name',
                    'description',
                    'quantity',
                    'price',
                    'tax_percent',
                    'tax_amount',
                    'discount_percent',
                    'discount_amount',
                    'row_total',
                    'row_weight',
                ];
                break;
            case "order_payment":
                $data = Order_Payment::all();
                $headers = [
                    'id',
                    'entity_id',
                    'amount_paid',
                    'shipping_amount',
                    'amount_refunded',
                    'shipping_refunded',
                    'method',
                    'cc_exp_month',
                    'cc_exp_year',
                    'cc_ss_start_month',
                    'cc_ss_start_year',
                    'cc_debug_request_body',
                    'cc_secure_verify',
                    'cc_approval',
                    'cc_last_4',
                    'cc_status_description',
                    'cc_cid_status',
                    'cc_owner',
                    'cc_type',
                    'cc_status',
                    'cc_ss_issue',
                    'cc_avs_status',
                    'protection_eligibility',
                    'last_trans_id',
                    'po_number',
                    'anet_trans_method',
                    'account_status',
                    'echeck_bank_name',
                    'echeck_account_type',
                    'echeck_type',
                    'echeck_routing_number',
                    'echeck_account_name',
                ];
                break;
            case "company":
                $data = Company::all();
                $headers = [
                    'id',
                    'company_code',
                    'company_name',
                    'other_ref',
                    'web_site',
                    'company_class',
                    'company_type',
                    'company_status',
                    'source_code',
                    'sale_source',
                    'currency_code',
                    'country',
                    'country_code',
                    'web_user',
                    'web_password',
                    'agent_name',
                    'tax_reference',
                    'hide_company',
                    'hold_data',
                    'mailing_status',
                    'proforma',
                    'sorder_locked',
                    'customer_discount',
                    'supplier',
                    'ec_company',
                    'pays_vat',
                    'pocode_required',
                    'reward_point_balance',
                    'reward_points_updated',
                    'payment_type',
                    'earn_and_redeem_reward_points',
                    'vat_relife_qualified',
                    'credit_code',
                ];
                break;
            case "default_billing_address":
                $data = DefaultBillingAddress::all();
                $headers = [
                    'customer_id',
                    'first_name',
                    'surname',
                    'company',
                    'street',
                    'city',
                    'region',
                    'postcode',
                    'country_code',
                ];
                //$show_link = 2;
                break;
            case "company_address":
                $data = CompanyAddress::all();
                $headers = [
                    'id',
                    'entity_id',
                    'first_name',
                    'surname',
                    'company',
                    'street',
                    'city',
                    'region',
                    'postcode',
                    'country',
                    'country_code',
                    'telephone',
                    'email',
                ];
                break;
            case "company_address_contact":
                $data = CompanyAddressContact::all();
                $headers = [
                    'id',
                    'address_id',
                    'title',
                    'forename',
                    'surname',
                    'jobtitle',
                    'tel',
                    'fax',
                    'mobile',
                    'email',
                    'note',
                    'emailsubscribe',
                    'dob',
                    'hide_contact',
                    'hold_data',
                    'is_preferred',
                    'is_preferred_invoice',
                    'is_preferred_delivery',
                    'mailing_flag',
                ];
                break;
            case "company_class":
                $data = CompanyClass::all();
                $headers = [
                    'id',
                    'name',
                ];
                break;
            case "company_list":
                $data = CompanyList::all();
                $headers = [
                    'id',
                    'company_code',
                    'other_ref',
                ];
                break;
            case "company_status":
                $data = CompanyStatus::all();
                $headers = [
                    'id',
                    'name',
                ];
                break;
            case "keycodes":
                $data = Keycode::all();
                $headers = [
                    'id',
                    'keycode_code',
                    'keycode_type_desc',
                    'source_code',
                    'web_use',
                    'motouse',
                ];
                break;
            case "keycode_types":
                $data = KeycodeType::all();
                $headers = [
                    'id',
                    'name',
                ];
                break;
            case "mailing_status":
                $data = MailingStatus::all();
                $headers = [
                    'id',
                    'description',
                ];
                break;
            case "manufacturer":
                $data = Manufacturer::all();
                $headers = [
                    'id',
                    'name',
                ];
                break;
            case "sale_source":
                $data = SaleSource::all();
                $headers = [
                    'id',
                    'name',
                ];
                break;
            case "country_list":
                $data = CountryList::all();
                $headers = [
                    'id',
                    'country_name',
                    'country_code2',
                    'country_code3',
                    'eu_member',
                    'pays_vat',
                    'currency_name',
                    'currency_code',
                    'zone',
                ];
                break;
        }

        return View('data', [
            'data' => $data,
            'table' => $table,
            'headers' => $headers,
            'page_title' => $page_title,
            'page_description'=> $page_description,
            'show_link' => $show_link,
        ]);
    }

    /**
     * Populate magento database from API database
     */
    public function customer_sync()
    {
        Customer::sync($this->magento_db);
    }

    /**
     * Populate magento database from API database
     *
     * @param $option
     */
    public function migrate($option, $all = false)
    {
        if ($option == "pricelists") {
            PriceLists::migrate($this->magento_db, PriceLists::all());
            return;
        } elseif ($option == "images") {
            if (isset($_GET['disable_images'])) {
                $images = $this->magento_db->table('catalog_product_entity_media_gallery')
                    ->where('disabled', '=', '0')
                    ->take($_GET['disable_images'])
                    ->get();

                foreach ($images as $image) {
                    $this->magento_db->table('catalog_product_entity_media_gallery')
                        ->updateOrInsert(
                            [
                                'value_id' => $image->value_id,
                            ],
                            [
                                'disabled' => 1,
                            ]
                        );
                }
                echo "Finished";
                return;
            } elseif (isset($_GET['enable_images'])) {
                $images = $this->magento_db->table('catalog_product_entity_media_gallery')->get();

                foreach ($images as $image) {
                    $this->magento_db->table('catalog_product_entity_media_gallery')
                        ->updateOrInsert(
                            [
                                'value_id' => $image->value_id,
                            ],
                            [
                                'disabled' => 0,
                            ]
                        );

                }
                echo "Finished";
                return;
            }

            $magento_image_directory = "./../../pub/media/catalog/product/";
            $images = $this->magento_db->table('catalog_product_entity_media_gallery')->get();

            foreach ($images as $image) {
                $filename = $magento_image_directory.$image->value;

                if (!file_exists($filename)) {
                    echo "<b>".$filename." - not found</b><br/>";
                    $this->magento_db->table('catalog_product_entity_media_gallery')
                        ->where('value_id', '=', $image->value_id)
                        ->delete();
                } else {
                    echo $filename." - found<br/>";
                }
            }
            return;
        }

        $attributes = $this->attributes();
        $customer_groups = $this->magento_db->table('customer_group')->get();

        // Determine existing products.
        $existing_products = [];
        foreach ($this->magento_db->table('catalog_product_entity')->get() as $row) {
            $existing_products[$row->sku] = $row->entity_id;
        }
        $skus = array_keys($existing_products);

        $all_skus = [];
        $parent_products = ParentProduct::all();
        foreach ($parent_products as $pp) {
            $all_skus[$pp->stock_code] = $pp->stock_code;
        }
        $products = Product::all();
        foreach ($products as $p) {
            $all_skus[$p->stock_code] = $p->stock_code;
        }

        // Delete from magento if dont exist in API database.
        foreach ($existing_products as $sku => $entity_id) {
            if (!in_array($sku, $all_skus)) {
                ParentProduct::delete_magento_product($this->magento_db, $entity_id, $sku);
            }
        }

        $limit = 999999;
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sku'])) {
            $products = ParentProduct::with('skus', 'skus.images', 'parent_images')
                ->where('stock_code', '=', $_GET['sku'])
                ->take($limit)
                ->get();
        } elseif (!empty($all)) {
            $products = ParentProduct::with('skus', 'skus.images', 'parent_images')
                ->take($limit)->get();
        } else {
            $products = ParentProduct::with('skus', 'skus.images', 'parent_images')
                ->where('imported', '=', 0)
                ->take($limit)->get();
        }

        // Handle failures
        ParentProduct::failures(false);

        if (count($products)) {
            foreach ($products as $product) {

                if (isset($_GET['sku'])) {
                    if ($product->stock_code != $_GET['sku']) {
                        continue;
                    }

                    if (isset($_GET['cmd']) && $_GET['cmd'] == 'Y') {

                        echo "Marked for migrate.";
                        $product->imported = 0;
                        $product->save();

                        if (isset($_GET['return'])) {
                            return Redirect::to($_GET['return']);
                        }
                        continue;
                    }
                }

                echo "Migrating " . $product->stock_code . " ";
                $exists = 0;
                if (in_array($product->stock_code, $skus)) {
                    $exists = 1;
                    echo " (Update) ";
                } else {
                    echo " (Create) ";
                }

                switch ($option) {
                    case "categories":
                        ParentProduct::migrate($product, $this->magento_db, $attributes, $customer_groups, '', false, $option);
                        echo "<br/>";
                        break;
                    case "products":
                        $entity_id = ParentProduct::migrate($product, $this->magento_db, $attributes, $customer_groups, '', false, $option);

                        if (!is_array($entity_id)) { // If not marked as deleted
                            $parent_product = ParentProduct::where('stock_code', '=', $product->stock_code)->first();
                            $parent_product->imported = 2;
                            $parent_product->entity_id = $entity_id;
                            $parent_product->save();
                        } else { // If marked as deleted

                            $stock_codes = [];
                            foreach ($entity_id as $stock_code) {
                                $parent_products = ParentProduct::where('stock_code', '=', $stock_code)->get();
                                $products = Product::where('stock_code', '=', $stock_code)->get();
                                foreach ($parent_products as $p) {
                                    $stock_codes[] = $p->stock_code;
                                }
                                foreach ($products as $p) {
                                    $stock_codes[] = $p->stock_code;
                                }
                            }

                            foreach ($stock_codes as $stock_code) {
                                Images::where('stock_code', '=', $stock_code)->delete();
                                StockLevel::where('stock_code', '=', $stock_code)->delete();
                                ParentProduct::where('stock_code', '=', $stock_code)->delete();
                                Product::where('stock_code', '=', $stock_code)->delete();
                            }
                        }

                        break;
                }
            }
        } else {
            echo 'No products to migrate.';
        }

    }

    /**
     * Pull data from Khaos into API database
     *
     * @param $request
     * @param int $interval_mins
     */
    public function request($request, $interval_mins = 60)
    {
        $valid_services = [
            'ExportStock',
            'ExportStockCompressed',
            'ExportPriceLists',
            'GetStockList'
        ];

        if (!in_array($request, $valid_services)) {
            die ("'".$request."' is not a valid web service, please try again");
        }

        $interval = $interval_mins * 60;
        echo "Calling ".$request.": ".$interval_mins." minute interval changes.<br/>";

        $this->khaos_web_service->request($request, $interval);
    }

    /**
     * Pull orders from Magento
     */
    public function orders($o_id = null)
    {
        $dir = "./../public/khaos/out/";

        Order::pull($this->magento_db);
        Order::clean($dir);
        Order::transform($dir, $o_id);
        Order::export($dir, $o_id);
        Order::import($dir, $o_id, false, $this->magento_db);
    }

    public function categories($option)
    {
        ParentProduct::categories($this->magento_db, $option);
    }



}
