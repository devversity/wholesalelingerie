<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\Site_Migration;
use Magento\Backend\Block\Widget\Grid\Column\Filter\Price;

class Sites extends Model
{
    protected $table = 'sites';

    protected $fillable = [
        'id',
        'name',
        'pricelist_name',
        'url',
        'categories',
        'brands',
        'db_connection',
        'prefix',
        'visible',
        'active',
        'order_ref'
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

    public function clear($site_id = 0, $cmd = false)
    {
        if ($site_id == 0) {
            $sites = Sites::where('active', '=', 1)->get();
        } else {
            $sites = Sites::where('active', '=', 1)->where('id', '=', $site_id)->get();
        }

        $tables = [
            'catalog_product_entity_media_gallery',
        ];

        foreach ($sites as $site) {

            $site_db = DB::connection($site->db_connection);

            echo ".....................................................................";
            echo $cmd ? "\n" : "<br/>";
            echo "- Clearing Magento tables from the ".$site->name." database.";
            echo $cmd ? "\n" : "<br/>";

            $site_db->statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach ($tables as $table) {
                echo "- Cleared ".$site->prefix.$table." table.";
                $site_db->table($site->prefix.$table)->truncate();
                echo $cmd ? "\n" : "<br/>";
            }

            $site_db->statement('SET FOREIGN_KEY_CHECKS=1;');

            echo ".....................................................................";
            echo $cmd ? "\n" : "<br/>";
        }
    }

    public function refresh($site_id = 0, $cmd = false)
    {
        if ($site_id == 0) {
            $sites = Sites::where('active', '=', 1)->get();
            Site_Migration::truncate();
        } else {
            $sites = Sites::where('active', '=', 1)->where('id', '=', $site_id)->get();
            Site_Migration::where('site_id', '=', $site_id)->delete();
        }

        if (count($sites) == 0) {
            die("No site/s found. Please check if the site id is valid and the site is active.");
        }

        $raw = DB::select('SELECT manufacturer, stock_ttype, stock_code FROM parent_product');

        $brands = [];
        $categories = [];
        $all_stock_codes = [];

        // STOCK_TTYPE = 1, STOCK_TYPE = 2, STOCK_SUB_TYPE = 3 (CATEGORIES)
        // MANFACTURER = BRAND

        foreach ($raw as $row) {
            $brands[strtolower($row->manufacturer)][] = $row->stock_code;
            $categories[strtolower($row->stock_ttype)][] = $row->stock_code;
            $all_stock_codes[$row->stock_code] = $row->stock_code;
        }

        foreach ($sites as $site) {
            $site_stock_codes = $all_stock_codes;
            $site_stock_codes_prices = [];
            $brands_to_search = array_filter(explode(",", strtolower($site->brands)));
            $categories_to_search = array_filter(explode(",", strtolower($site->categories)));

            if (!empty($site->pricelist_name)) {

                $price_list_name_chunks = explode('-', $site->pricelist_name);
                $pricelist_names = [];
                for ($i=1; $i < 10; $i++) {
                    $pricelist_names[] = $price_list_name_chunks[0].'_'.$i.'-'.$price_list_name_chunks[1];
                }

                $site_stock_codes = [];
                foreach ($pricelist_names as $pricelist_name) {
                    $price_list_items = PriceListStockItems::where('price_list', '=', $pricelist_name)->get();
                    if (!empty($price_list_items)) {
                        foreach ($price_list_items as $price_list_item) {
                            if (isset($all_stock_codes[$price_list_item->StockCode])) {
                                $site_stock_codes_prices[$price_list_item->StockCode] = $price_list_item->AmountValue;
                                $site_stock_codes[$price_list_item->StockCode] = $price_list_item->StockCode;
                            }
                        }
                    }
                }
            } else {
                if (!empty($categories) && !empty($categories_to_search)) {
                    foreach ($categories as $category => $stock_codes) {
                        if (!in_array($category, $categories_to_search)) {
                            if (!empty($stock_codes)) {
                                foreach ($stock_codes as $stock_code) {
                                    if (isset($site_stock_codes[$stock_code])) {
                                        unset($site_stock_codes[$stock_code]);
                                    }
                                }
                            }
                        }
                    }
                }

                if (!empty($brands) && !empty($brands_to_search)) {
                    foreach ($brands as $brand => $stock_codes) {
                        if (!in_array($brand, $brands_to_search)) {
                            if (!empty($stock_codes)) {
                                foreach ($stock_codes as $stock_code) {
                                    if (isset($site_stock_codes[$stock_code])) {
                                        unset($site_stock_codes[$stock_code]);
                                    }
                                }
                            }
                        }
                    }
                }
            }


            echo ".....................................................................";
            echo $cmd ? "\n" : "<br/>";
            echo "SITE: ".$site->url;
            echo $cmd ? "\n" : "<br/>";
            echo "CATEGORIES: ".implode(", ", $categories_to_search);
            echo $cmd ? "\n" : "<br/>";
            echo "BRANDS: ".implode(", ", $brands_to_search);
            echo $cmd ? "\n" : "<br/>";
            echo "FOUND ".count($site_stock_codes)." SKUs to mark for migration.";
            echo $cmd ? "\n" : "<br/>";
            echo ".....................................................................";
            echo $cmd ? "\n" : "<br/>";

            foreach ($site_stock_codes as $site_stock_code) {

                $new_product_price = 0;
                if (isset($site_stock_codes_prices[$site_stock_code])) {
                    $new_product_price = $site_stock_codes_prices[$site_stock_code];
                }

                $site_migration = new Site_Migration();
                $site_migration->site_id = $site->id;
                $site_migration->sku = $site_stock_code;
                $site_migration->price = $new_product_price;
                $site_migration->save();

                if ($new_product_price > 0) {
                    echo "SKU - ".$site_stock_code." = ".$new_product_price;
                } else {
                    echo "SKU - ".$site_stock_code;
                }

                echo $cmd ? "\n" : "<br/>";
            }

            // Remove products in site which don't exist in API database.
            // Test once we have products in site.
            echo $cmd ? "\n" : "<br/>";
            echo ".....................................................................";
        }
    }

    public function remove_magento_products($sites, $cmd)
    {
        foreach ($sites as $site) {
            $db = DB::connection($site->db_connection);
            $migrate_raw = Site_Migration::where('site_id', '=', $site->id)->get();
            $magento_raw = $db->table($site->prefix.'catalog_product_entity')->get();

            $delete_tables_entity_ids = [
                $site->prefix.'catalog_product_entity',
                $site->prefix.'catalog_product_entity_text',
                $site->prefix.'catalog_product_entity_varchar',
                $site->prefix.'catalog_product_entity_datetime',
                $site->prefix.'catalog_product_entity_decimal',
                $site->prefix.'catalog_product_entity_int',
                $site->prefix.'catalog_product_entity_media_gallery_value',
                $site->prefix.'catalog_product_entity_media_gallery_value_to_entity',
            ];

            $delete_tables_product_ids = [
                $site->prefix.'catalog_category_product',
                $site->prefix.'catalog_product_super_attribute',
                $site->prefix.'cataloginventory_stock_status',
                $site->prefix.'cataloginventory_stock_item',
                $site->prefix.'catalog_product_website',
                $site->prefix.'catalog_product_super_link',
            ];

            $migrate_skus = [];
            foreach ($migrate_raw as $row) {
                $migrate_skus[$row->sku] = $row->sku;
            }

            if (!empty($site->pricelist_name)) {

                $price_list_name_chunks = explode('-', $site->pricelist_name);
                $pricelist_names = [];
                for ($i=1; $i < 10; $i++) {
                    $pricelist_names[] = $price_list_name_chunks[0].'_'.$i.'-'.$price_list_name_chunks[1];
                }

                foreach ($pricelist_names as $pricelist_name) {
                    $price_list_items = PriceListStockItems::where('price_list', '=', $pricelist_name)->get();
                    if (!empty($price_list_items)) {
                        foreach ($price_list_items as $price_list_item) {
                            $migrate_skus[$price_list_item->StockCode] = $price_list_item->StockCode;
                        }
                    }
                }
            }

            $magento_skus = [];
            if (!empty($magento_raw)) {
                foreach ($magento_raw as $row) {
                    $magento_skus[$row->sku] = $row->entity_id;
                }
            }

            $delete_skus = [];
            foreach ($magento_skus as $magento_sku => $entity_id) {
                if (empty($magento_sku)) {
                    continue;
                }

                if (!in_array($magento_sku, $migrate_skus)) {
                    $delete_skus[$magento_sku] = $entity_id;
                }
            }


            if (!empty($delete_skus)) {
                foreach ($delete_skus as $delete_sku => $entity_id) {
                    foreach ($delete_tables_entity_ids as $table) {
                        $db->table($table)->where('entity_id', '=', $entity_id)->delete();
                    }
                    foreach ($delete_tables_product_ids as $table) {
                        $db->table($table)->where('product_id', '=', $entity_id)->delete();
                    }
                    echo "Site: ".$site->name." - Removed ".$delete_sku.".";
                    echo $cmd ? "\n" : "<br/>";
                }
            }
        }

    }

    public function migrate($site_id = 0, $cmd = true, $hours = 24, $sku = null, $delete_mode = 1)
    {
        if ($site_id == 0) {
            $sites = Sites::where('active', '=', 1)->get();
        } else {
            $sites = Sites::where('active', '=', 1)->where('id', '=', $site_id)->get();
        }

        if (count($sites) == 0) {
            die("No site/s found. Please check if the site id is valid and the site is active.");
        }

        // Remove magento products
        if ($delete_mode == 1) {
            $this->remove_magento_products($sites, $cmd);
        }

        $site_by_id = [];
        foreach ($sites as $site) {
            $site_by_id[$site->id] = $site;
        }

        $date = date("Y-m-d G:i:s", time()-(3600*$hours));

        if (!empty($sku)) {
            $parent_products = ParentProduct::with('skus')->where('stock_code','=', $sku)->get();
        } else {
            $parent_products = ParentProduct::with('skus')->whereDate('updated_at','>=', $date)->get();
        }

        if (count($parent_products) == 0) {
            die("No product/s found to migrate");
        }

        echo "Migrating ".count($parent_products)." products updated since ".$date." to retail sites.";
        echo $cmd ? "\n" : "<br/>";
        echo ".....................................................................";
        echo $cmd ? "\n" : "<br/>";

        foreach ($parent_products as $parent_product) {

            if ($site_id == 0) {
                $valid_sites =
                    Site_Migration::where('sku', '=', $parent_product->stock_code)
                        ->where('imported', '=', 0)
                        ->get();
            } else {
                $valid_sites =
                    Site_Migration::where('sku', '=', $parent_product->stock_code)
                        ->where('site_id', '=', $site_id)
                        ->where('imported', '=', 0)
                        ->get();
            }

            if (count($valid_sites) == 0) {
                //echo " - No sites found to migrate product.";
                //echo $cmd ? "\n" : "<br/>";
                continue;
            } else {
                echo "Processing: ".$parent_product->stock_code;
                echo $cmd ? "\n" : "<br/>";
            }

            foreach ($valid_sites as $valid_site) {
                if (!isset($site_by_id[$valid_site->site_id])) {
                    continue;
                }

                $temp_site = $site_by_id[$valid_site->site_id];
                echo " - ".$temp_site->url." found to migrate product.";
                echo $cmd ? "\n" : "<br/>";

                $magento_db = DB::connection($temp_site->db_connection);

                if ($cmd) {
                    $magento_image_directory = '../pub/media/catalog/product/khaos/';
                } else {
                    $magento_image_directory = '';
                }

                $site_migration =
                    Site_Migration::where('sku', '=', $parent_product->stock_code)
                        ->where('site_id', '=', $valid_site->site_id)
                        ->first();

                $current_site = $site_by_id[$valid_site->site_id];
                $site_stock_codes_prices = [];
                if (!empty($current_site->pricelist_name)) {

                    $price_list_name_chunks = explode('-', $current_site->pricelist_name);
                    $pricelist_names = [];
                    for ($i=1; $i < 10; $i++) {
                        $pricelist_names[] = $price_list_name_chunks[0].'_'.$i.'-'.$price_list_name_chunks[1];
                    }

                    foreach ($pricelist_names as $pricelist_name) {
                        $price_list_items = PriceListStockItems::where('price_list', '=', $pricelist_name)->get();
                        if (!empty($price_list_items)) {
                            foreach ($price_list_items as $price_list_item) {
                                $site_stock_codes_prices[$price_list_item->StockCode] = $price_list_item->AmountValue;
                            }
                        }
                    }
                }

                if (
                    !empty($sku) ||
                    !empty($site_migration->migrate_ran) ||
                    $site_migration->migrate_ran < $date
                ) {
                    try {
                        ParentProduct::retail_migrate($parent_product, $site_migration, $site_stock_codes_prices, $magento_db, $magento_image_directory, $cmd, $temp_site);
                        $site_migration->migrate_ran = date("Y-m-d G:i:s");
                        $site_migration->imported = 2;
                    } catch (\Exception $ex) {
                        try {
                            echo "Re-trying (1) ".$parent_product->stock_code;
                            echo $cmd ? "\n" : "<br/>";
                            ParentProduct::retail_migrate($parent_product, $site_migration, $site_stock_codes_prices, $magento_db, $magento_image_directory, $cmd, $temp_site);
                            $site_migration->migrate_ran = date("Y-m-d G:i:s");
                            $site_migration->imported = 2;
                        } catch (\Exception $ex) {
                            try {
                                echo "Re-trying (2) ".$parent_product->stock_code;
                                echo $cmd ? "\n" : "<br/>";
                                ParentProduct::retail_migrate($parent_product, $site_migration, $site_stock_codes_prices, $magento_db, $magento_image_directory, $cmd, $temp_site);
                                $site_migration->migrate_ran = date("Y-m-d G:i:s");
                                $site_migration->imported = 2;
                            } catch (\Exception $ex) {
                                echo "Skipping ".$parent_product->stock_code." Site id: ".$valid_site->id;
                                echo $cmd ? "\n" : "<br/>";
                            }
                        }
                    }

                } else {
                    echo "- Already processed.";
                    echo $cmd ? "\n" : "<br/>";
                }

                $site_migration->save();
            }
        }
    }

    public function attributes($site_id = 0, $override = 0, $cmd = false)
    {
        $magento_db = DB::connection('mysql2');
        $attribute_table = "eav_attribute";

        $mappings = [
            'colour_attribute_id' => 'color',
            'manufacturer_attribute_id' => 'manufacturer',
            'size_attribute_id' => 'size',
            'visibility_attribute_id' => 'visibility',
            'search_terms_name_attribute_id' => 'name',
            'search_terms_sku_attribute_id' => 'sku',
            'search_terms_description_attribute_id' => 'description',
            'search_terms_manufacturer_attribute_id' => 'manufacturer',
            'image_attribute_ids' => 'image,small_image,thumbnail,swatch_image',
            'image_attribute_1_id' => 'image',
            'image_attribute_2_id' => 'media_gallery',
            'category_attribute_1_id' => 'is_active',
            'category_attribute_2_id' => 'is_anchor',
            'category_attribute_3_id' => 'include_in_menu',
            'manufacturer_name_attribute_id' => 'manufacturer_name'
        ];

        $defaults = [
            'entity_type_id' => 4,
            'default_attribute_set_id' => 3,
            'default_category' => 2,
            'super_attribute_colour_label' => 'Colour',
            'super_attribute_size_label' => 'Size',
            'manufacturer_category_name' => 'Brands',
        ];

//        $base_attributes_raw = $magento_db->table($attribute_table)->get();
//        $base_attributes = [];
//        foreach ($base_attributes_raw as $row) {
//            $base_attributes[$row->attribute_id] = $row->attribute_code;
//        }

        if ($site_id == 0) {
            $sites = Sites::where('active', '=', 1)->get();
        } else {
            $sites = Sites::where('active', '=', 1)->where('id', '=', $site_id)->get();
        }

        if (count($sites) == 0) {
            die("No site/s found. Please check if the site id is valid and the site is active.");
        }

        $exclude_columns = ['id', 'site_id'];

        foreach ($sites as $site) {

            echo "Site: ".$site->url;
            echo $cmd ? "\n" : "<br/>";
            echo "........................................................................";
            echo $cmd ? "\n" : "<br/>";

            $site_defaults = SiteDefaults::where('site_id', '=', $site->id)->first();
            if ($site_defaults == null) {
                $site_defaults = new SiteDefaults;
                $site_defaults->site_id = $site->id;
            }
            $columns = $site_defaults->getColumns();

            $site_db = DB::connection($site->db_connection);

            $site_attributes_raw = $site_db->table($site->prefix.$attribute_table)->get();
            $site_attributes = [];
            foreach ($site_attributes_raw as $row) {
                $site_attributes[$row->attribute_code] = $row->attribute_id;
            }

            foreach ($columns as $column) {
                if (in_array($column, $exclude_columns)) {
                    continue;
                }
                if (!isset($mappings[$column])) {
                    if (!isset($defaults[$column])) {
                        continue;
                    }
                    echo "Site: ".$site->url." - ".$column." = ".$defaults[$column];
                    if ($override == 1) {
                        $site_defaults->{$column} = $defaults[$column];
                        echo ": UPDATED";
                    } else {
                        echo ": NOT CHANGED";
                    }
                    echo $cmd ? "\n" : "<br/>";

                    continue;
                }

                if (strpos($mappings[$column], ",") > -1) {
                    $sub_columns = explode(",", $mappings[$column]);
                    $sub_attribute_vals = [];
                    foreach ($sub_columns as $sub_column) {
                        if (!isset($site_attributes[$sub_column])) {
                            echo "Site: ".$site->url." - ".$sub_column." = NO MAPPING FOR ".$mappings[$sub_column];
                            echo $cmd ? "\n" : "<br/>";
                            continue;
                        }
                        $sub_attribute_vals[] = $site_attributes[$sub_column];
                    }
                    if (!empty($sub_attribute_vals)) {
                        echo "Site: ".$site->url." - ".$column. " = ".implode(",", $sub_attribute_vals);
                        if ($override == 1) {
                            $site_defaults->{$column} = implode(",", $sub_attribute_vals);
                            echo ": UPDATED";
                        } else {
                            echo ": NOT CHANGED";
                        }
                        echo $cmd ? "\n" : "<br/>";
                    }

                } else {
                    if (!isset($site_attributes[$mappings[$column]])) {
                        echo "Site: ".$site->url." - ".$column." = NO MAPPING FOR ".$mappings[$column];
                        echo $cmd ? "\n" : "<br/>";
                        continue;
                    }

                    echo "Site: ".$site->url." - ".$column." = ".$site_attributes[$mappings[$column]];
                    if ($override == 1) {
                        $site_defaults->{$column} = $site_attributes[$mappings[$column]];
                        echo ": UPDATED";
                    } else {
                        echo ": NOT CHANGED";
                    }
                    echo $cmd ? "\n" : "<br/>";
                }
            }
            $site_defaults->save();
            echo "........................................................................";
            echo $cmd ? "\n" : "<br/>";
        }

    }

}
