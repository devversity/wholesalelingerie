<?php

namespace App;

use App\Console\Commands\KhaosOrders;
use App\KhaosControl;
use Illuminate\Database\Eloquent\Model;
use Schema;

class ParentProduct extends Model
{
    protected $table = 'parent_product';

    protected $fillable = [
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
        'depth',
        'country_of_manufacture',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

    public function skus()
    {
        return $this->hasMany('App\Product', 'parent_stock_code', 'stock_code');
    }

    public function parent_images()
    {
        return $this->hasMany('App\Images', 'stock_code', 'stock_code');
    }

    public function parent_stock_levels()
    {
        return $this->hasMany('App\StockLevel', 'stock_code', 'stock_code');
    }

    public static function failures($cmd)
    {
        return;

        // Lets handle the failures.
        $failures = Failures::where('attempts', '<', 10)->get();

        $khaos_control = new KhaosControl;

        if (!empty($failures)) {
            foreach ($failures as $failure) {

                echo "Re-attempting ".$failure->stock_code.": Attempt ".($failure->attempts + 1);
                $current_failure = Failures::where('id', '=', $failure->id)->first();

                try {
                    $stock_potential = $khaos_control->get_stock_potential($failure->stock_code);
                    $stock_status_xml = $khaos_control->export_stock_status($failure->stock_code);

                    foreach ($stock_potential as $stock) {
                        $stock_level = StockLevel::where('stock_code', '=', $failure->stock_code)->first();
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

                    $stock_status = [];
                    if (!empty($stock_status_xml)) {
                        $stock_status = simplexml_load_string($stock_status_xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
                    }

                    foreach ($stock_status as $stock_status_row) {

                        $stock_status = StockStatus::where('stock_code', '=', $failure->stock_code)
                            ->where('site', '=', $stock_status_row->SITE)
                            ->first();

                        if ($stock_status == null) {
                            $stock_status = new StockStatus;
                        }

                        $stock_status->stock_code = $failure->stock_code;
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

                    $current_failure->attempts = 99;
                    echo " - Success.";
                    echo $cmd ? "\n" : "<br/>";
                } catch (\Exception $ex) {
                    $current_failure->attempts = $current_failure->attempts + 1;
                    $current_failure->error = $ex;
                    echo " - Fail.";
                    echo $cmd ? "\n" : "<br/>";
                }

                $current_failure->save();
            }
        }
    }

    public static function migrate($data, $db, $all_attributes, $all_customer_groups, $magento_image_directory = '', $cmd = false, $cut_off = false)
    {
        $khaos_control = new KhaosControl;

        $original_data = $data;
        $deleted = 0;
        $delete_entity_ids = [];
        $entity_ids = [];
        $delete_stock_codes = [];
        $stock_codes = [];
        $count = 0;

        if (isset($_GET['sku'])) {
            $rtz_products = Product::with('stock_levels')
                ->where('parent_stock_code', '=', $_GET['sku'])
                ->where('run_to_zero', '=', -1)->get();
        } else {
            $rtz_products = Product::with('stock_levels')
                ->where('run_to_zero', '=', -1)->get();
        }

        // Check for any Return to Zero stock which is zero.
        $rtz_stock_check_skus = [];
        $delete_rtz_stock_check_skus = [];
        foreach ($rtz_products as $rtz_product) {
            if (count($rtz_product->stock_levels) == 0) {
               $rtz_stock_check_skus[$rtz_product->stock_code] = $rtz_product->stock_code;
            }
        }

        // Double check these stock values with whats in Khaos
        if (!empty($rtz_stock_check_skus)) {
            echo $cmd ? "\n" : "<br/>";
            echo "------------------- RTZ Product Stock Check -------------------";
            echo $cmd ? "\n" : "<br/>";
            $delete_rtz_stock_check_skus = $rtz_stock_check_skus;
            $stock_recheck_count = 0;
            foreach (array_chunk($rtz_stock_check_skus, 100) as $rtz_stock_check_skus_chunk) {
                $csv_rtz_stock_check_skus = implode(", ", $rtz_stock_check_skus_chunk);

                try {
                    $stock_data = $khaos_control->get_stock_potential($csv_rtz_stock_check_skus);

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
                        if ($stock_level->available > 0) {
                            unset($delete_rtz_stock_check_skus[$stock->StockCode]);
                        }

                        $stock_recheck_count++;
                        echo $stock_recheck_count."/".count($rtz_stock_check_skus)." - RTZ product: ".$stock->StockCode." (Stock = ".$stock_level->available.")";
                        echo $cmd ? "\n" : "<br/>";
                    }
                } catch (\Exception $ex) {
//                    $failure = new Failures;
//                    $failure->stock_code = $csv_rtz_stock_check_skus;
//                    $failure->error = $ex;
//                    $failure->save();
                }
            }
            echo "------------------- RTZ Product Stock Check -------------------";
            echo $cmd ? "\n" : "<br/>";
        }

        // If we found stock in Khaos, elements of this array would have been removed.
        // Hence, we delete them from our database.
        if (!empty($delete_rtz_stock_check_skus)) {
            foreach ($delete_rtz_stock_check_skus as $delete_rtz_stock_check_sku) {
                Product::where('stock_code', '=', $delete_rtz_stock_check_sku)->delete();
                StockLevel::where('stock_code', '=', $delete_rtz_stock_check_sku)->delete();
                echo "RTZ Product deleted : ".$delete_rtz_stock_check_sku;
                echo $cmd ? "\n" : "<br/>";
            }
        }

        $fulltext_table_base = "catalogsearch_fulltext_scope";
        $defaultcategory_table_base = "catalog_category_product_index_store";

        if (empty($magento_image_directory)) {
            $magento_image_directory = "./../../pub/media/catalog/product/khaos/";
        }

        if (!is_dir($magento_image_directory)) {
            echo 'No Magento Image Directory: '.$magento_image_directory;exit;
        }

        $entity_type_id = 4;
        $colour_attribute_id = 90;
        $manufacturer_attribute_id = 80;
        $size_attribute_id = 137;

        $super_attribute_colour_id = $colour_attribute_id;
        $super_attribute_colour_label = "Colour";

        $super_attribute_size_id = $size_attribute_id;
        $super_attribute_size_label = "Size";

        $catalog_product_index_eav_attributes = [
            80 => 'size',
            90 => 'color',
            96 => 'visibility',
            137 => 'size'
        ];

        $manufacturers = [];
        $skus = [];
        $colours_by_parent_entity_colour_id = [];

        $migrate_parent_data_exclude = ['id', 'skus'];
        $migrate_child_data_exclude = ['id', 'stock_option', 'user_defined', 'images'];

        if (!empty($data->skus)) {
            foreach ($data->skus as $sku) {

                $images = [];
                if (count($sku->images)) {

                    foreach ($sku->images as $image) {
                        $images[$image->id] = [
                            'stock_code' => $image->stock_code,
                            'stock_image' => $image->stock_image,
                            'image_name' => $image->image_name,
                            'file_name' => $image->file_name,
                            'image_desc' => $image->image_desc,
                            'image_type' => $image->image_type,
                        ];
                    }
                }

                // Add color to eav_attribute_option table if doesnt exist.
                $exists = $db->table('eav_attribute_option_value')
                    ->where('value', $sku->colour)
                    ->Exists();

                if (!$exists) {
                    $option_id = $db->table('eav_attribute_option')
                        ->insertGetId([
                            'attribute_id' => $colour_attribute_id,
                            'sort_order' => 99
                        ]);
                    $db->table('eav_attribute_option_value')
                        ->insert([
                            'option_id' => $option_id,
                            'value' => $sku->colour
                        ]);
                    $colour_option_id = $option_id;
                } else {
                    $color_option = $db->table('eav_attribute_option_value')
                        ->where('value', $sku->colour)
                        ->first();
                    $colour_option_id = $color_option->option_id;
                }

                // Colour swatch
                $exists = $db->table('eav_attribute_option_swatch')
                    ->where('option_id', $colour_option_id)
                    ->where('store_id', 0)
                    ->Exists();

                if (!$exists) {
                    $db->table('eav_attribute_option_swatch')
                        ->updateOrInsert(
                            [
                                'option_id' => $colour_option_id,
                                'store_id' => 0,
                            ],
                            [
                                'type' => 1,
                                'value' => "#000"
                            ]
                        );
                }

                // Add size to eav_attribute_option table if doesnt exist.
                $exists = $db->table('eav_attribute_option_value')
                    ->where('value', $sku->size)
                    ->Exists();

                if (!$exists) {
                    $option_id = $db->table('eav_attribute_option')
                        ->insertGetId([
                            'attribute_id' => $size_attribute_id,
                            'sort_order' => 99
                        ]);
                    $db->table('eav_attribute_option_value')
                        ->insert([
                            'option_id' => $option_id,
                            'value' => $sku->size
                        ]);
                    $size_option_id = $option_id;
                } else {
                    $size_option = $db->table('eav_attribute_option_value')
                        ->where('value', $sku->size)
                        ->orderBy('option_id', 'desc')
                        ->first();
                    $size_option_id = $size_option->option_id;
                }

                // Size swatch
                $exists = $db->table('eav_attribute_option_swatch')
                    ->where('option_id', $size_option_id)
                    ->where('store_id', 0)
                    ->Exists();

                if (!$exists) {
                    $db->table('eav_attribute_option_swatch')
                        ->updateOrInsert(
                            [
                                'option_id' => $size_option_id,
                                'store_id' => 0,
                            ],
                            [
                                'type' => 0,
                                'value' => $sku->size
                            ]
                        );
                }

                $manufacturer_option_id = 0;
                if (!empty($sku->manufacturer)) {
                    // Add manufacturer to eav_attribute_option table if doesnt exist.
                    $exists = $db->table('eav_attribute_option_value')
                        ->where('value', $sku->manufacturer)
                        ->Exists();

                    if (!$exists) {
                        $option_id = $db->table('eav_attribute_option')
                            ->insertGetId([
                                'attribute_id' => $manufacturer_attribute_id,
                                'sort_order' => 99
                            ]);
                        $db->table('eav_attribute_option_value')
                            ->insert([
                                'option_id' => $option_id,
                                'value' => $sku->manufacturer
                            ]);
                        $manufacturer_option_id = $option_id;
                    } else {
                        $manufacturer_option = $db->table('eav_attribute_option_value')
                            ->where('value', $sku->manufacturer)
                            ->first();
                        $manufacturer_option_id = $manufacturer_option->option_id;
                    }
                    $manufacturers[$manufacturer_option_id] = $sku->manufacturer;
                }

                $skus[$sku->stock_code] = [
                    'id' => $sku->id,
                    'stock_code' => $sku->stock_code,
                    'options_container' => 'container2',
                    'msrp_display_actual_price' => 0,
                    //'name' => $sku->description,
                    'name' => $sku->stock_code,
                    'price' => $sku->sell_price,
                    'status' => 1,
                    'visibility' => 1,
                    'meta_title' => !empty($sku->meta_title) ? $sku->meta_title : $sku->stock_code,
                    'meta_description' => !empty($sku->meta_description) ? $sku->meta_description : $sku->stock_code,
                    'meta_keywords' => !empty($sku->meta_keywords) ? $sku->meta_keywords : $sku->stock_code,
                    'url_key' => strtolower(str_replace(" ", "-", $sku->stock_code)),
                    'quantity_and_stock_status' => 1,
                    'manufacturer' => $manufacturer_option_id,
                    'color' => $colour_option_id,
                    'size' => $size_option_id,
                    'tax_class_id' => 2,
                    'description' => $sku->description,
                    'weight' => round($sku->weight / 1000, 2),
                    'height' => $sku->height,
                    'width' => $sku->width,
                    'country_of_manufacture' => $sku->country_of_manufacture,
                    'stock_option' => json_decode($sku->stock_option),
                    'user_defined' => json_decode($sku->user_defined),
                    'images' => array_reverse($images),
                    'run_to_zero' => $sku->run_to_zero,
                    'deleted' => $sku->deleted,
                ];
            }
        }

        // Add manufacturer to eav_attribute_option table if doesnt exist.
        $exists = $db->table('eav_attribute_option_value')
            ->where('value', $data->manufacturer)
            ->Exists();

        if (!$exists) {
            $option_id = $db->table('eav_attribute_option')
                ->insertGetId([
                    'attribute_id' => $manufacturer_attribute_id,
                    'sort_order' => 99
                ]);
            $db->table('eav_attribute_option_value')
                ->insert([
                    'option_id' => $option_id,
                    'value' => $data->manufacturer
                ]);
            $manufacturer_option_id = $option_id;
        } else {
            $manufacturer_option = $db->table('eav_attribute_option_value')
                ->where('value', $data->manufacturer)
                ->first();
            $manufacturer_option_id = $manufacturer_option->option_id;
        }

        if ($data->sell_price > 0 && $data->deleted != -1) {
            $deleted = 0;
        }

        $migrate_data = [
            'id' => $data->id,
            'stock_code' => $data->stock_code,
            'name' => $data->stock_code,
            'short_description' => $data->stock_desc,
            'description' => $data->long_desc,
            'options_container' => 'container2',
            'msrp_display_actual_price' => 0,
            'price' => $data->sell_price,
            'special_price' => $data->sell_price,
            'status' => 1,
            'visibility' => 4,
            'meta_title' => !empty($data->meta_title) ? $data->meta_title : $data->stock_code." - ".(!empty($data->manufacturer) ? $data->manufacturer." " : "").(!empty($data->stock_type) ? $data->stock_type." " : "").(!empty($data->stock_ttype) ? $data->stock_ttype." " : ""),
            'meta_description' => !empty($data->meta_description) ? $data->meta_description : $data->stock_code." - ".(!empty($data->manufacturer) ? $data->manufacturer." " : "").(!empty($data->stock_type) ? $data->stock_type." " : "").(!empty($data->stock_ttype) ? $data->stock_ttype." " : ""),
            'meta_keywords' => !empty($data->meta_keywords) ? $data->meta_keywords : $data->stock_code,
            'country_of_manufacture' => 'GB',
            'url_key' => strtolower(str_replace(" ", "-", $data->stock_code)),
            'quantity_and_stock_status' => 1,
            'tax_class_id' => 2,
            'weight' => round($data->weight / 1000, 2),
            'height' => $data->height,
            'width' => $data->width,
            'manufacturer' => $manufacturer_option_id,
            'manufacturer_name' => $data->manufacturer,
            'stock_ttype' => $data->stock_ttype,
            'stock_type' => $data->stock_type,
            'stock_sub_type' => $data->stock_sub_type,
        ];

        if (isset($_GET['debug'])) {
            echo "<pre>";
            print_r($data);
            print_r($migrate_data);exit;
        }

        $attribute_key_to_id = [];
        $attributes = $db->table('eav_attribute')
            ->where('entity_type_id', '=', $entity_type_id)
            ->get();

        foreach ($attributes as $attribute) {
            $attribute_key_to_id[$attribute->backend_type][$attribute->attribute_code] = $attribute->attribute_id;
        }
        /** Attribute Table - End */

        /** Parent Product Base Tables - Start */
        if (empty($skus)) {
            $type_id = "simple";
        } else {
            $type_id = "configurable";
        }

        $db->table('catalog_product_entity')
            ->updateOrInsert(
                [
                    'sku' => $data->stock_code
                ],
                [
                    'attribute_set_id' => 4,
                    'type_id' => $type_id,
                    'has_options' => 1,
                    'required_options'=> 1
                ]
            );

        $entity = $db->table('catalog_product_entity')
            ->where('sku', '=', $data->stock_code)
            ->first();

        if (empty($entity->entity_id)) {
            return;
        } elseif ($data->sell_price == 0 || $data->deleted == -1) {
            $delete_entity_ids[] = $entity->entity_id;
            $delete_stock_codes[] = $data->stock_code;
        }
        $entity_ids[] = $entity->entity_id;
        $stock_codes[] = $data->stock_code;

        ParentProduct::migrate_categories($entity, $migrate_data, $db);

        if ($cut_off == "categories") {
            return $entity->entity_id;
        }

        // Catalog Product Index Price
        if (!empty($all_customer_groups)) {
            foreach ($all_customer_groups as $customer_group) {
                try {
                    $db->table('catalog_product_index_price')
                        ->updateOrInsert(
                            [
                                'entity_id' => $entity->entity_id,
                                'website_id' => 1,
                                'customer_group_id' => $customer_group->customer_group_id,
                                'tax_class_id' => $migrate_data['tax_class_id']
                            ],
                            [
                                'price' => $migrate_data['price'],
                                'final_price' => $migrate_data['price'],
                                'min_price' => $migrate_data['price'],
                                'max_price' => $migrate_data['price'],
                            ]
                        );
                } catch (Exception $ex) {

                }
            }
        }

        $search_terms = [
            'name' => [
                'attribute_id' => 70,
                'data_index' => $data->stock_code,
            ],
            'sku' => [
                'attribute_id' => 71,
                'data_index' => $data->stock_code,
            ],
            'description' => [
                'attribute_id' => 72,
                'data_index' => $data->long_desc,
            ],
            'manufacturer' => [
                'attribute_id' => 80,
                'data_index' => $data->manufacturer,
            ]
        ];

        // Website visibility
        $db->table('catalog_product_website')
            ->updateOrInsert(
                [
                    'website_id' => 1,
                    'product_id' => $entity->entity_id
                ]
            );

        $stock_level = StockLevel::where('stock_code', '=', $entity->sku)->first();
        $stock_status = StockStatus::where('stock_code', '=', $entity->sku)
            ->where('site', '=', 'Head Office')
            ->first();

        if (
            !is_array($stock_level) &&
            !is_object($stock_level)
        ) {
            $stock_level = [];
        }

        $qty = 0;
        if (isset($stock_level->available)) {
            // $qty = $stock_level->available;
        }

        if (isset($stock_status->level)) {
            $qty = $stock_status->level;
        }

        $backorders = 0;
        $use_config_backorders = 1;
        if ($data->run_to_zero == -1) {
            $backorders = 0;
            $use_config_backorders = 0;
        }

        // Stock
        $db->table('cataloginventory_stock_item')
            ->updateOrInsert(
                [
                    'stock_id' => 1,
                    'product_id' => $entity->entity_id
                ],
                [
                    'qty' => $qty,
                    'is_in_stock' => 1,
                    'min_sale_qty' => 1,
                    'max_sale_qty' => 1000,
                    'notify_stock_qty' => 1,
                    'manage_stock' => 1,
                    'backorders' => $backorders,
                    'use_config_backorders' => $use_config_backorders
                ]
            );
        $db->table('cataloginventory_stock_status')
            ->updateOrInsert(
                [
                    'stock_id' => 1,
                    'product_id' => $entity->entity_id,
                    'website_id' => 0
                ],
                [
                    'qty' => $qty,
                    'stock_status' => 1,
                ]
            );

        // Attributes
        $db->table('catalog_product_super_attribute')
            ->updateOrInsert(
                [
                    'product_id' => $entity->entity_id,
                    'attribute_id' => $super_attribute_colour_id,
                ],
                [
                    'position' => 0,
                ]
            );

        $db->table('catalog_product_super_attribute')
            ->updateOrInsert(
                [
                    'product_id' => $entity->entity_id,
                    'attribute_id' => $super_attribute_size_id,
                ],
                [
                    'position' => 0,
                ]
            );

        $super_attribute_colour = $db->table('catalog_product_super_attribute')
            ->where('product_id', '=', $entity->entity_id)
            ->where('attribute_id', '=', $super_attribute_colour_id)
            ->first();

        if (empty($super_attribute_colour->product_super_attribute_id)) {
            return;
        }

        $db->table('catalog_product_super_attribute_label')
            ->updateOrInsert(
                [
                    'product_super_attribute_id' => $super_attribute_colour->product_super_attribute_id
                ],
                [
                    'value' => $super_attribute_colour_label,
                    'store_id' => 0,
                    'use_default' => 0,
                ]
            );

        $super_attribute_size = $db->table('catalog_product_super_attribute')
            ->where('product_id', '=', $entity->entity_id)
            ->where('attribute_id', '=', $super_attribute_size_id)
            ->first();

        if (empty($super_attribute_size->product_super_attribute_id)) {
            return;
        }

        $db->table('catalog_product_super_attribute_label')
            ->updateOrInsert(
                [
                    'product_super_attribute_id' => $super_attribute_size->product_super_attribute_id
                ],
                [
                    'value' => $super_attribute_size_label,
                    'store_id' => 0,
                    'use_default' => 0,
                ]
            );
        /** Parent Product Base Tables - End */

        /** Parent Product Variable Tables - Start */
        if (!empty($attribute_key_to_id)) {
            foreach ($attribute_key_to_id as $type => $ids) {

                if (empty($ids)) {
                    continue;
                }

                $table = "";
                switch ($type) {
                    case "varchar":
                        $table = "catalog_product_entity_varchar";
                        break;
                    case "decimal":
                        $table = "catalog_product_entity_decimal";
                        break;
                    case "static":
                        continue;
                        break;
                    case "int":
                        $table = "catalog_product_entity_int";
                        break;
                    case "datetime":
                        $table = "catalog_product_entity_datetime";
                        break;
                    case "text":
                        $table = "catalog_product_entity_text";
                        break;
                    default:
                        continue;
                        break;
                }

                foreach ($ids as $attribute_code => $attribute_id) {
                    foreach ($migrate_data as $migrate_key => $migrate_value) {

                        if ($attribute_code != $migrate_key) {
                            continue;
                        }

                        if (isset($_GET[$type])) {
                            echo "KEY = ".$migrate_key;
                            echo $cmd ? "\n" : "<br/>";
                        }

                        // value_id, attribute_id, store_id, entity_id, value
//                        $db->table($table)
//                            ->updateOrInsert(
//                                [
//                                    'attribute_id' => $attribute_id,
//                                    'entity_id' => $entity->entity_id
//                                ],
//                                [
//                                    'store_id' => 0,
//                                    'value' => $migrate_value,
//                                ]
//                            );

                        $db->table($table)
                            ->updateOrInsert(
                                [
                                    'attribute_id' => $attribute_id,
                                    'entity_id' => $entity->entity_id,
                                    'store_id' => 0,
                                ],
                                [
                                    'value' => $migrate_value,
                                ]
                            );

                        //echo "Attribute Code: ".$attribute_code.", Entity Code: ".$entity->sku.", Value: ".$migrate_value."<br/>";
                    }
                }
            }
        }
        /** Parent Product Variable Tables - End */

        /** Product Variable + Link - Start */
        $db->table('catalog_product_super_link')->where('parent_id', '=', $entity->entity_id)->delete();

        $filename = "";

        $db->table('catalog_product_entity_media_gallery_value_to_entity')
            ->where('entity_id', '=', $entity->entity_id)
            ->delete();

        $db->table('catalog_product_entity_media_gallery_value')
            ->where('entity_id', '=', $entity->entity_id)
            ->delete();

        $image_attribute_ids = [84, 85, 86, 150];

        foreach ($image_attribute_ids as $image_attribute_id) {
            $db->table('catalog_product_entity_varchar')
                ->where('entity_id', '=', $entity->entity_id)
                ->where('attribute_id', '=', $image_attribute_id)
                ->delete();
        }

        if (!empty($skus)) {
            foreach ($skus as $data) {

                $db->table('catalog_product_entity')
                    ->updateOrInsert(
                        [
                            'sku' => $data['stock_code']
                        ],
                        [
                            'attribute_set_id' => 4,
                            'type_id' => 'simple',
                        ]
                    );

                $product = $db->table('catalog_product_entity')
                    ->where('sku', '=', $data['stock_code'])
                    ->first();

                if (empty($product->entity_id)) {
                    return;
                } elseif ($data['price'] == 0 || $data['deleted'] == -1) {
                    $delete_entity_ids[] = $product->entity_id;
                    $delete_stock_codes[] = $data['stock_code'];
                    echo $cmd ? "\n" : "<br/>";
                }

                $entity_ids[] = $product->entity_id;

                $db->table('catalog_product_entity_media_gallery_value_to_entity')
                    ->where('entity_id', '=', $product->entity_id)
                    ->delete();

                $db->table('catalog_product_entity_media_gallery_value')
                    ->where('entity_id', '=', $product->entity_id)
                    ->delete();

                $image_attribute_ids = [84, 85, 86, 150];
                foreach ($image_attribute_ids as $image_attribute_id) {
                    $db->table('catalog_product_entity_varchar')
                        ->where('entity_id', '=', $product->entity_id)
                        ->where('attribute_id', '=', $image_attribute_id)
                        ->delete();
                }

                if (isset($_GET['image_debug'])) {
                    echo "<pre>";
                    print_r($data['images']);
                    echo "</pre>";
                }

                if (!empty($data['images'])) {
                    foreach ($data['images'] as $image) {

                        $list = explode("\\", str_replace("P:\\Khaos\Images\\", "", $image['file_name']));

                        if (count($list) > 1) {
                            $folder = $list[0];
                            $tmp_filename = $list[1];
                        } elseif (!empty($image['image_name'])) {
                            $list = explode("\\", str_replace("P:\\Khaos\Images\\", "", $image['image_name']));
                            if (count($list) == 1) {
                                continue;
                            }
                            $folder = $list[0];
                            $tmp_filename = $list[1];
                        } else {
                            continue;
                        }

                        $ok = 0;

                        $exts = [
                            'png',
                            'jpg',
                            'jpeg',
                            'gif',
                            'tif',
                            'bmp',
                            'PNG',
                            'JPG',
                            'JPEG',
                            'GIF',
                            'TIF',
                            'BMP'
                        ];
                        $found_ext = 0;
                        foreach ($exts as $ext) {
                            if (strpos($tmp_filename, $ext) > -1) {
                                $found_ext = 1;
                                break;
                            }
                        }
                        if ($found_ext == 0) {
                            $tmp_filename = $tmp_filename.".jpg";
                        }

                        if (is_dir($magento_image_directory.$folder)) {
                            foreach (scandir($magento_image_directory.$folder) as $file) {

                                if (strtolower($tmp_filename) != strtolower($file)) {
                                    continue;
                                }

                                $filename = "khaos/".$folder."/".$file;

                                $ok = 1;
                                break;
                            }
                        }

                        if (!$ok) {
                            continue;
                        }

                        echo $product->entity_id." = ".$filename;
                        echo $cmd ? "\n" : "<br/>";

                        // Current filename
                        $current_filename = $db->table('catalog_product_entity_varchar')
                            ->where('attribute_id', 84)
                            ->where('entity_id', $product->entity_id)
                            ->first();

                        if (empty($current_filename) && !empty($filename)) {
                            $db->table('catalog_product_entity_varchar')
                                ->updateOrInsert(
                                    [
                                        'attribute_id' => 84,
                                        'store_id' => 0,
                                        'entity_id' => $product->entity_id
                                    ],
                                    [
                                        'value' => $filename,
                                    ]);

                        }

                        $attribute_ids = [84, 85, 86, 150];

                        foreach ($attribute_ids as $attribute_id) {

                            $db->table('catalog_product_entity_varchar')
                                ->updateOrInsert(
                                    [
                                        'attribute_id' => $attribute_id,
                                        'store_id' => 0,
                                        'entity_id' => $product->entity_id
                                    ],
                                    [
                                        'value' => $filename,
                                    ]
                                );
                        }

                        $db->table('catalog_product_entity_media_gallery')
                            ->updateOrInsert(
                                [
                                    'attribute_id' => 87,
                                    'media_type' => 'image',
                                    'value' => $filename
                                ],
                                [
                                    'disabled' => 0,
                                ]
                            );

                        $media_value = $db->table('catalog_product_entity_media_gallery')
                            ->where('value', '=', $filename)
                            ->first();

                        $db->table('catalog_product_entity_media_gallery_value_to_entity')
                            ->updateOrInsert(
                                [
                                    'value_id' => $media_value->value_id,
                                    'entity_id' => $product->entity_id,
                                ],
                                []
                            );

                        $db->table('catalog_product_entity_media_gallery_value')
                            ->updateOrInsert(
                                [
                                    'value_id' => $media_value->value_id,
                                    'entity_id' => $product->entity_id,
                                    'store_id' => 0
                                ],
                                [
                                    'label' => NULL,
                                    'position' => 0,
                                    'disabled' => 0
                                ]
                            );
                    }
                }

                // Website visibility
                $db->table('catalog_product_website')
                    ->updateOrInsert(
                        [
                            'website_id' => 1,
                            'product_id' => $product->entity_id
                        ]
                    );

                $stock_level = StockLevel::where('stock_code', '=', $product->sku)->first();

                if (
                    !is_array($stock_level) &&
                    !is_object($stock_level)
                ) {
                    $stock_level = [];
                }

                $qty = 0;
                if (isset($stock_level->available)) {
                    $qty = $stock_level->available;
                }

                $backorders = 0;
                $use_config_backorders = 1;
                if ($data['run_to_zero'] == -1) {
                    $backorders = 0;
                    $use_config_backorders = 0;
                }

                // Stock
                $db->table('cataloginventory_stock_item')
                    ->updateOrInsert(
                        [
                            'stock_id' => 1,
                            'product_id' => $product->entity_id
                        ],
                        [
                            'qty' => $qty,
                            'is_in_stock' => 1,
                            'min_sale_qty' => 1,
                            'backorders' => $backorders,
                            'use_config_backorders' => $use_config_backorders
                        ]
                    );

                $db->table('cataloginventory_stock_status')
                    ->updateOrInsert(
                        [
                            'stock_id' => 1,
                            'product_id' => $product->entity_id,
                            'website_id' => 0
                        ],
                        [
                            'qty' => $qty,
                            'stock_status' => 1,
                        ]
                    );

                // Link
                $db->table('catalog_product_super_link')
                    ->updateOrInsert(
                        [
                            'product_id' => $product->entity_id,
                            'parent_id' => $entity->entity_id
                        ]
                    );

                // Attributes
                if (!empty($attribute_key_to_id)) {
                    foreach ($attribute_key_to_id as $type => $ids) {

                        if (empty($ids)) {
                            continue;
                        }

                        $table = "";
                        switch ($type) {
                            case "varchar":
                                $table = "catalog_product_entity_varchar";
                                break;
                            case "decimal":
                                $table = "catalog_product_entity_decimal";
                                break;
                            case "static":
                                continue;
                                break;
                            case "int":
                                $table = "catalog_product_entity_int";
                                break;
                            case "datetime":
                                $table = "catalog_product_entity_datetime";
                                break;
                            case "text":
                                $table = "catalog_product_entity_text";
                                break;
                            default:
                                continue;
                                break;
                        }

                        //TODO-STU


                        if (!empty($data) && !empty($ids)) {
                            foreach ($ids as $attribute_code => $attribute_id) {
                                foreach ($data as $migrate_key => $migrate_value) {

                                    if (is_object($migrate_value) || is_array($migrate_value)) {
                                        continue;
                                    }
                                    if ($attribute_code != $migrate_key) {
                                        continue;
                                    }

                                    if (isset($_GET[$type])) {
                                        echo "KEY = ".$migrate_key;
                                        echo $cmd ? "\n" : "<br/>";
                                    }

                                    // value_id, attribute_id, store_id, entity_id, value
                                    $db->table($table)
                                        ->updateOrInsert(
                                            [
                                                'attribute_id' => $attribute_id,
                                                'entity_id' => $product->entity_id
                                            ],
                                            [
                                                'store_id' => 0,
                                                'value' => $migrate_value,
                                            ]
                                        );
                                    // echo "Attribute Code: ".$attribute_code.", Entity Code: ".$product->sku.", Value: ".$migrate_value."<br/>";

                                }
                            }
                        }

                    }
                }
            }
        }

        // Set parent images
        $parent_images = [];

        $attribute_ids = [84, 85, 86, 150];
        if (isset($original_data->parent_images) && count($original_data->parent_images) > 0) {
            foreach ($original_data->parent_images as $temp_img) {

                $list = explode("\\", str_replace("P:\\Khaos\Images\\", "", $temp_img->file_name));

                if (count($list) > 1) {
                    $folder = $list[0];
                    $tmp_filename = $list[1];
                } elseif (!empty($temp_img->file_name)) {
                    $list = explode("\\", str_replace("P:\\Khaos\Images\\", "", $temp_img->file_name));
                    if (count($list) == 1) {
                        continue;
                    }
                    $folder = $list[0];
                    $tmp_filename = $list[1];
                } else {
                    continue;
                }

                $ok = 0;

                $exts = ['png', 'jpg', 'jpeg', 'gif', 'tif', 'bmp'];
                $found_ext = 0;
                foreach ($exts as $ext) {
                    if (strpos($tmp_filename, $ext) > -1) {
                        $found_ext = 1;
                        break;
                    }
                }
                if ($found_ext == 0) {
                    $tmp_filename = $tmp_filename.".jpg";
                }

                if (is_dir($magento_image_directory.$folder)) {
                    foreach (scandir($magento_image_directory.$folder) as $file) {

                        if (strtolower($tmp_filename) != strtolower($file)) {
                            continue;
                        }
                        $filename = "khaos/".$folder."/".$file;
                        $ok = 1;
                        break;
                    }
                }

                if (!$ok) {
                    continue;
                }

                $parent_images[] = $filename;
            }
        }

        if (empty($parent_images) && !empty($filename)) {
            $parent_images[] = $filename;
        }

        if (isset($_GET['image_debug'])) {
            echo "<pre>";
            print_r($parent_images);
            echo "</pre>";
        }

        if (!empty($parent_images)) {
            $parent_images = array_reverse($parent_images);

            foreach ($parent_images as $filename) {
                echo "Parent Image: ".$entity->entity_id."=".$filename;
                echo $cmd ? "\n" : "<br/>";

                foreach ($attribute_ids as $attribute_id) {
                    $db->table('catalog_product_entity_varchar')
                        ->updateOrInsert(
                            [
                                'attribute_id' => $attribute_id,
                                'store_id' => 0,
                                'entity_id' => $entity->entity_id
                            ],
                            [
                                'value' => $filename,
                            ]
                        );
                }

                $db->table('catalog_product_entity_media_gallery')
                    ->updateOrInsert(
                        [
                            'attribute_id' => 87,
                            'media_type' => 'image',
                            'value' => $filename
                        ],
                        [
                            'disabled' => 0,
                        ]
                    );

                $media_value = $db->table('catalog_product_entity_media_gallery')
                    ->where('value', '=', $filename)
                    ->first();

                $db->table('catalog_product_entity_media_gallery_value_to_entity')
                    ->updateOrInsert(
                        [
                            'value_id' => $media_value->value_id,
                            'entity_id' => $entity->entity_id,
                        ],
                        []
                    );
                $db->table('catalog_product_entity_media_gallery_value')
                    ->updateOrInsert(
                        [
                            'value_id' => $media_value->value_id,
                            'entity_id' => $entity->entity_id,
                            'store_id' => 0
                        ],
                        [
                            'label' => NULL,
                            'position' => 0,
                            'disabled' => 0
                        ]
                    );
            }
        } else {
            foreach ($attribute_ids as $attribute_id) {
                $db->table('catalog_product_entity_varchar')
                    ->where('attribute_id', $attribute_id)
                    ->where('entity_id', $entity->entity_id)
                    ->delete();
            }
        }

        if (!empty($delete_entity_ids)) {

            if ($deleted == 1) {
                $delete_entity_ids = $entity_ids;
                $delete_stock_codes = $stock_codes;
            }

            $delete_tables_entity_ids = [
                'catalog_product_entity',
                'catalog_product_entity_text',
                'catalog_product_entity_varchar',
                'catalog_product_entity_datetime',
                'catalog_product_entity_decimal',
                'catalog_product_entity_int',
                'catalog_product_entity_media_gallery_value',
                'catalog_product_entity_media_gallery_value_to_entity',
            ];

            $delete_tables_product_ids = [
                'catalog_category_product',
                'catalog_product_super_attribute',
                'cataloginventory_stock_status',
                'cataloginventory_stock_item',
                'catalog_product_website',
                'catalog_product_super_link',
            ];

            echo "PRODUCTS (".implode(", ", $delete_entity_ids).") DELETED FROM MAGENTO / API DATABASE";
            echo $cmd ? "/n" : "<br/>";

            foreach ($delete_tables_entity_ids as $table) {
                foreach ($delete_entity_ids as $entity_id) {
                    $db->table($table)->where('entity_id', '=', $entity_id)->delete();
                }
            }
            foreach ($delete_tables_product_ids as $table) {
                foreach ($delete_entity_ids as $entity_id) {
                    $db->table($table)->where('product_id', '=', $entity_id)->delete();
                }
            }

            return array_unique($delete_stock_codes);
        }

        $db->table('catalog_product_entity_int')
            ->where('attribute_id', '=', 96)
            ->where('store_id', '=', 1)
            ->delete();

        return $entity->entity_id;
        /** Product Variable + Link - End */
    }

    public static function categories($db, $option)
    {
        $excluded_categories = [1, 2, 21];

        $categories = $db->table('catalog_category_entity')->get();

        switch ($option) {
            case "clear":
                foreach ($categories as $category) {
                    if (in_array($category->entity_id, $excluded_categories)) {
                        continue;
                    }

                    $temp = $db->table('catalog_category_entity')
                        ->where('parent_id', '=', $category->entity_id)
                        ->get();

                    if (count($temp) == 0) {
                        $temp_temp = $db->table('catalog_category_product')
                            ->where('category_id', '=', $category->entity_id)
                            ->get();
                        if (count($temp_temp) == 0) {
                            $db->table('catalog_category_entity')
                                ->where('entity_id', '=', $category->entity_id)
                                ->delete();
                            $db->table('catalog_category_entity_int')
                                ->where('entity_id', '=', $category->entity_id)
                                ->delete();
                            $db->table('catalog_category_entity_varchar')
                                ->where('entity_id', '=', $category->entity_id)
                                ->delete();

                            echo "Removed Category ID: ".$category->entity_id."<br/>";
                        }
                    }
                }
                break;
            case "remove":
                $excluded_categories = [1, 2, 21];

                foreach ($categories as $category) {
                    if (in_array($category->entity_id, $excluded_categories)) {
                        continue;
                    }

                    $db->table('catalog_category_entity')
                        ->where('entity_id', '=', $category->entity_id)
                        ->delete();
                    $db->table('catalog_category_entity_int')
                        ->where('entity_id', '=', $category->entity_id)
                        ->delete();
                    $db->table('catalog_category_entity_varchar')
                        ->where('entity_id', '=', $category->entity_id)
                        ->delete();
                    $db->table('catalog_category_entity')
                        ->where('entity_id', '=', $category->entity_id)
                        ->delete();
                    $db->table('catalog_category_entity_int')
                        ->where('entity_id', '=', $category->entity_id)
                        ->delete();
                    $db->table('catalog_category_product')
                        ->where('category_id', '=', $category->entity_id)
                        ->delete();
                }


                break;
        }
    }

    private static function migrate_categories($entity, $migrate_data, $db)
    {
        // Do category stuff
        // STOCK_TTYPE = 1, STOCK_TYPE = 2, STOCK_SUB_TYPE = 3
        $default_category_id = 2;
        $default_attribute_set_id = 3;
        $attribute_id = 42;
        $manufacturer_category_name = "Brands";
        $category_entity_table = "catalog_category_entity";
        $category_entity_varchar_table = "catalog_category_entity_varchar";
        $category_entity_int_table = "catalog_category_entity_int";
        $category_product_table = "catalog_category_product";
        $category_entity = [];
        $category_second_entity = [];
        $category_third_entity = [];
        $manufacturer_category_entity = [];
        $manufacturer_sub_type_category_entity = [];

//        $db->table($category_product_table)
//            ->where('product_id', $entity->entity_id)
//            ->delete();

        if (!empty($migrate_data['manufacturer'])) {

            $manufacturer_category_varchar_set = $db->table($category_entity_varchar_table)
                ->where('value', '=', 'Brands')
                ->where('attribute_id', '=', $attribute_id)
                ->get();
            foreach ($manufacturer_category_varchar_set as $key => $manufacturer_category_varchar) {
                $manufacturer_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $manufacturer_category_varchar->entity_id)
                    ->first();

                if (
                    !is_array($manufacturer_category_entity) &&
                    !is_object($manufacturer_category_entity)
                ) {
                    $manufacturer_category_entity = [];
                }

                if (
                    !is_array($manufacturer_category_entity) &&
                    !is_object($manufacturer_category_entity)
                ) {
                    $manufacturer_category_entity = [];
                }

                // Doesn't exist so delete varchar
                if (!isset($manufacturer_category_entity->entity_id)) {
                    $db->table($category_entity_varchar_table)
                        ->where('entity_id', '=', $manufacturer_category_varchar->entity_id)
                        ->delete();
                    $db->table($category_entity_int_table)
                        ->where('entity_id', '=', $manufacturer_category_varchar->entity_id)
                        ->delete();
                }
            }

            $manufacturer_category_entity = [];
            $manufacturer_category_varchar = $db->table($category_entity_varchar_table)
                ->where('value', '=', $manufacturer_category_name)
                ->where('attribute_id', '=', $attribute_id)
                ->first();

            if (isset($manufacturer_category_varchar)) {
                $manufacturer_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $manufacturer_category_varchar->entity_id)
                    ->first();
            }

            if (isset($manufacturer_category_entity->entity_id)) {

                $manufacturer_entity_category_varchar = $db->table($category_entity_varchar_table)
                    ->where('value', '=', $migrate_data['manufacturer_name'])
                    ->where('attribute_id', '=', $attribute_id)
                    ->get();

                $tmp_array = [];
                foreach ($manufacturer_entity_category_varchar as $temp) {
                    $manufacturer_sub_type_category_entity =
                        $db->table($category_entity_table)
                            ->where('entity_id', '=', $temp->entity_id)
                            ->where('parent_id', '=', $manufacturer_category_entity->entity_id)
                            ->first();

                    if (isset($manufacturer_sub_type_category_entity->entity_id)) {
                        break;
                    }
                }

                // Build category entity
                if (!isset($manufacturer_sub_type_category_entity->entity_id)) {

                    $manufacturer_sub_type_entity_id = $db->table($category_entity_table)
                        ->insertGetId([
                                'parent_id' => $manufacturer_category_entity->entity_id,
                                'path' => $manufacturer_category_entity->path,
                                'attribute_set_id' => $default_attribute_set_id,
                                'position' => 1,
                                'level' => 3,
                                'children_count' => 0
                            ]
                        );
                    $db->table($category_entity_table)
                        ->updateOrInsert(
                            [
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'path' => $manufacturer_category_entity->path . '/' . $manufacturer_sub_type_entity_id,
                            ]
                        );
                    $db->table($category_entity_varchar_table)
                        ->updateOrInsert(
                            [
                                'attribute_id' => $attribute_id,
                                'store_id' => 0,
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'value' => $migrate_data['manufacturer_name'],
                            ]
                        );
                    $db->table($category_entity_int_table)
                        ->updateOrInsert(
                            [
                                'attribute_id' => 43,
                                'store_id' => 0,
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'value' => 1,
                            ]
                        );
                    $db->table($category_entity_int_table)
                        ->updateOrInsert(
                            [
                                'attribute_id' => 51,
                                'store_id' => 0,
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'value' => 1,
                            ]
                        );
                    $db->table($category_entity_int_table)
                        ->updateOrInsert(
                            [
                                'attribute_id' => 66,
                                'store_id' => 0,
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'value' => 1,
                            ]
                        );

                    $manufacturer_sub_type_category_entity = $db->table($category_entity_table)
                        ->where('entity_id', '=', $manufacturer_sub_type_entity_id)
                        ->first();
                }

                $db->table($category_product_table)
                    ->updateOrInsert(
                        [
                            'category_id' => $manufacturer_sub_type_category_entity->entity_id,
                            'product_id' => $entity->entity_id,
                        ],
                        [
                            'position' => 0,
                        ]
                    );
            }

        }

        if (!empty($migrate_data['stock_ttype'])) {

            $primary_category_varchar = [];
            $primary_category_varchar_array = $db->table($category_entity_varchar_table)
                ->where('value', '=', $migrate_data['stock_ttype'])
                ->where('attribute_id', '=', $attribute_id)
                ->get();

            if (count($primary_category_varchar_array) > 1) {
                foreach ($primary_category_varchar_array as $row) {
                    $temp_entity = $db->table($category_entity_table)
                        ->where('entity_id', '=', $row->entity_id)
                        ->where('parent_id', '=', $default_category_id)
                        ->first();
                    if (!empty($temp_entity)) {
                        $primary_category_varchar = $db->table($category_entity_varchar_table)
                            ->where('value', '=', $migrate_data['stock_ttype'])
                            ->where('entity_id', '=', $temp_entity->entity_id)
                            ->first();
                    }
                }
            } elseif (count($primary_category_varchar_array) == 1) {
                $primary_category_varchar = $primary_category_varchar_array->first();
            }

            $category_entity = [];
            if (!empty($primary_category_varchar)) {
                $category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $primary_category_varchar->entity_id)
                    ->where('parent_id', '=', $default_category_id)
                    ->first();
            }

            if (
                !is_array($category_entity) &&
                !is_object($category_entity)
            ) {
                $category_entity = [];
            }

            // Build category entity
            if (!isset($category_entity->entity_id)) {

                $category_entity_id = $db->table($category_entity_table)
                    ->insertGetId([
                            'parent_id' => $default_category_id,
                            'path' => '1/2',
                            'attribute_set_id' => $default_attribute_set_id,
                            'position' => 1,
                            'level' => 2,
                            'children_count' => 0
                        ]
                    );
                $db->table($category_entity_table)
                    ->updateOrInsert(
                        [
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'path' => '1/2/' . $category_entity_id,
                        ]
                    );
                $db->table($category_entity_varchar_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $attribute_id,
                            'store_id' => 0,
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'value' => $migrate_data['stock_ttype'],
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 43,
                            'store_id' => 0,
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 51,
                            'store_id' => 0,
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 66,
                            'store_id' => 0,
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );

                $category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $category_entity_id)
                    ->first();
            }

            $db->table($category_product_table)
                ->updateOrInsert(
                    [
                        'category_id' => $category_entity->entity_id,
                        'product_id' => $entity->entity_id,
                    ],
                    [
                        'position' => 0,
                    ]
                );
        }

        // Second level
        if (!empty($category_entity) && !empty($migrate_data['stock_type'])) {

            // Clean Varchar Table
            $second_category_varchar_set = $db->table($category_entity_varchar_table)
                ->where('value', '=', $migrate_data['stock_type'])
                ->where('attribute_id', '=', $attribute_id)
                ->get();
            foreach ($second_category_varchar_set as $key => $second_category_varchar) {
                $second_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $second_category_varchar->entity_id)
                    ->first();

                if (
                    !is_array($second_category_entity) &&
                    !is_object($second_category_entity)
                ) {
                    $second_category_entity = [];
                }

                // Doesn't exist so delete varchar
                if (!isset($second_category_entity->entity_id)) {
                    $db->table($category_entity_varchar_table)
                        ->where('entity_id', '=', $second_category_varchar->entity_id)
                        ->delete();
                    $db->table($category_entity_int_table)
                        ->where('entity_id', '=', $second_category_varchar->entity_id)
                        ->delete();
                }
            }

            $second_category_varchar = [];

            $second_category_varchar_array = $db->table($category_entity_varchar_table)
                ->where('value', '=', $migrate_data['stock_type'])
                ->where('attribute_id', '=', $attribute_id)
                ->get();

            if (
                !is_array($second_category_varchar_array) &&
                !is_object($second_category_varchar_array)
            ) {
                $second_category_varchar_array = [];
            }

            if (count($second_category_varchar_array) > 1) {
                foreach ($second_category_varchar_array as $row) {
                    $temp_entity = $db->table($category_entity_table)
                        ->where('entity_id', '=', $row->entity_id)
                        ->where('parent_id', '=', $category_entity->entity_id)
                        ->first();
                    if (!empty($temp_entity)) {
                        $second_category_varchar = $db->table($category_entity_varchar_table)
                            ->where('value', '=', $migrate_data['stock_type'])
                            ->where('entity_id', '=', $temp_entity->entity_id)
                            ->first();
                    }
                }
            } elseif (count($second_category_varchar_array) == 1) {
                $second_category_varchar = $second_category_varchar_array->first();
            }

            $second_category_entity = [];

            if (!empty($second_category_varchar)) {
                $second_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $second_category_varchar->entity_id)
                    ->where('parent_id', '=', $category_entity->entity_id)
                    ->first();
            }

            // Build category entity
            if (!isset($second_category_entity->entity_id)) {
                $second_category_entity_id = $db->table($category_entity_table)
                    ->insertGetId([
                            'parent_id' => $category_entity->entity_id,
                            'path' => $category_entity->path,
                            'attribute_set_id' => $default_attribute_set_id,
                            'position' => 1,
                            'level' => 3,
                            'children_count' => 0
                        ]
                    );
                $db->table($category_entity_table)
                    ->updateOrInsert(
                        [
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'path' => $category_entity->path . '/' . $second_category_entity_id,
                        ]
                    );
                $db->table($category_entity_varchar_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $attribute_id,
                            'store_id' => 0,
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'value' => $migrate_data['stock_type'],
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 43,
                            'store_id' => 0,
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 51,
                            'store_id' => 0,
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 66,
                            'store_id' => 0,
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );

                $second_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $second_category_entity_id)
                    ->first();
            }


            $db->table($category_product_table)
                ->updateOrInsert(
                    [
                        'category_id' => $second_category_entity->entity_id,
                        'product_id' => $entity->entity_id,
                    ],
                    [
                        'position' => 0,
                    ]
                );
        }

        // Third level
        if (!empty($second_category_entity) && !empty($migrate_data['stock_sub_type'])) {

            $third_category_varchar_array = $db->table($category_entity_varchar_table)
                ->where('value', '=', $migrate_data['stock_sub_type'])
                ->where('attribute_id', '=', $attribute_id)
                ->get();

            $third_category_varchar = [];

            if (count($third_category_varchar_array) > 1) {
                foreach ($third_category_varchar_array as $row) {
                    $temp_entity = $db->table($category_entity_table)
                        ->where('entity_id', '=', $row->entity_id)
                        ->where('parent_id', '=', $second_category_entity->entity_id)
                        ->first();
                    if (!empty($temp_entity)) {
                        $third_category_varchar = $db->table($category_entity_varchar_table)
                            ->where('value', '=', $migrate_data['stock_sub_type'])
                            ->where('entity_id', '=', $temp_entity->entity_id)
                            ->first();
                    }
                }
            } elseif (count($third_category_varchar_array) == 1) {
                $third_category_varchar = $third_category_varchar_array->first();
            }

            $third_category_entity = [];

            if (!empty($third_category_varchar)) {
                $third_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $third_category_varchar->entity_id)
                    ->where('parent_id', '=', $second_category_entity->entity_id)
                    ->first();
            }

            // Build category entity
            if (!isset($third_category_entity->entity_id)) {

                $third_category_entity_id = $db->table($category_entity_table)
                    ->insertGetId([
                            'parent_id' => $second_category_entity->entity_id,
                            'path' => $second_category_entity->path,
                            'attribute_set_id' => $default_attribute_set_id,
                            'position' => 1,
                            'level' => 4,
                            'children_count' => 0
                        ]
                    );
                $db->table($category_entity_table)
                    ->updateOrInsert(
                        [
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'path' => $second_category_entity->path . '/' . $third_category_entity_id,
                        ]
                    );
                $db->table($category_entity_varchar_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $attribute_id,
                            'store_id' => 0,
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'value' => $migrate_data['stock_sub_type'],
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 43,
                            'store_id' => 0,
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 51,
                            'store_id' => 0,
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => 66,
                            'store_id' => 0,
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );

                $third_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $third_category_entity_id)
                    ->first();
            }

            $db->table($category_product_table)
                ->updateOrInsert(
                    [
                        'category_id' => $third_category_entity->entity_id,
                        'product_id' => $entity->entity_id,
                    ],
                    [
                        'position' => 0,
                    ]
                );

        }
    }

    public static function delete_magento_product($db, $entity_id, $stock_code, $cmd = false)
    {
        $delete_tables_entity_ids = [
            'catalog_product_entity',
            'catalog_product_entity_text',
            'catalog_product_entity_varchar',
            'catalog_product_entity_datetime',
            'catalog_product_entity_decimal',
            'catalog_product_entity_int',
            'catalog_product_entity_media_gallery_value',
            'catalog_product_entity_media_gallery_value_to_entity',
        ];

        $delete_tables_product_ids = [
            'catalog_category_product',
            'catalog_product_super_attribute',
            'cataloginventory_stock_status',
            'cataloginventory_stock_item',
            'catalog_product_website',
            'catalog_product_super_link',
        ];

        echo "PRODUCT (".$stock_code." / ".$entity_id.") DELETED FROM MAGENTO / API DATABASE";
        echo $cmd ? "/n" : "<br/>";

        foreach ($delete_tables_entity_ids as $table) {
            $db->table($table)->where('entity_id', '=', $entity_id)->delete();
        }
        foreach ($delete_tables_product_ids as $table) {
            $db->table($table)->where('product_id', '=', $entity_id)->delete();
        }
    }

    public static function retail_migrate($data, $site_migration, $site_stock_codes_prices, $db, $magento_image_directory = '', $cmd = false, $site = [])
    {
        if (!isset($site->prefix)) {
            $site->prefix = "";
        }

        $site_defaults = [];
        $site_defaults_raw = SiteDefaults::all();
        if (count($site_defaults_raw) > 0) {
            foreach ($site_defaults_raw as $site_default) {
                $site_defaults[$site_default->site_id] = $site_default;
            }
        }

        $original_data = $data;
        $deleted = 0;
        $delete_entity_ids = [];
        $entity_ids = [];
        $delete_stock_codes = [];
        $stock_codes = [];
        $count = 0;

        $all_customer_groups = $db->table($site->prefix.'customer_group')->get();

        $fulltext_table_base = $site->prefix."catalogsearch_fulltext_scope";
        $defaultcategory_table_base = $site->prefix."catalog_category_product_index_store";

        if (empty($magento_image_directory)) {
            $magento_image_directory = "./../../pub/media/catalog/product/khaos/";
        }

        if (!is_dir($magento_image_directory)) {
            echo 'No Magento Image Directory: '.$magento_image_directory;exit;
        }

        $entity_type_id = isset($site_defaults[$site->id]->entity_type_id) ? $site_defaults[$site->id]->entity_type_id : 0;
        $colour_attribute_id = isset($site_defaults[$site->id]->colour_attribute_id) ? $site_defaults[$site->id]->colour_attribute_id : 0;
        $manufacturer_attribute_id = isset($site_defaults[$site->id]->manufacturer_attribute_id) ? $site_defaults[$site->id]->manufacturer_attribute_id : 0;
        $size_attribute_id = isset($site_defaults[$site->id]->size_attribute_id) ? $site_defaults[$site->id]->size_attribute_id : 0;
        $visibility_attribute_id = isset($site_defaults[$site->id]->visibility_attribute_id) ? $site_defaults[$site->id]->visibility_attribute_id : 0;
        $image_attribute_1_id = isset($site_defaults[$site->id]->image_attribute_1_id) ? $site_defaults[$site->id]->image_attribute_1_id : 0;
        $image_attribute_2_id = isset($site_defaults[$site->id]->image_attribute_2_id) ? $site_defaults[$site->id]->image_attribute_2_id : 0;

        $super_attribute_colour_id = $colour_attribute_id;
        $super_attribute_colour_label = isset($site_defaults[$site->id]->super_attribute_colour_label) ? $site_defaults[$site->id]->super_attribute_colour_label : "";

        $super_attribute_size_id = $size_attribute_id;
        $super_attribute_size_label = isset($site_defaults[$site->id]->super_attribute_size_label) ? $site_defaults[$site->id]->super_attribute_size_label : "";

        $catalog_product_index_eav_attributes = [
            $colour_attribute_id => 'color',
            $visibility_attribute_id => 'visibility',
            $size_attribute_id => 'size'
        ];

        $manufacturers = [];
        $skus = [];
        $colours_by_parent_entity_colour_id = [];

        $migrate_parent_data_exclude = ['id', 'skus'];
        $migrate_child_data_exclude = ['id', 'stock_option', 'user_defined', 'images'];

        if (!empty($data->skus)) {
            foreach ($data->skus as $sku) {

                $images = [];
                if (count($sku->images)) {

                    foreach ($sku->images as $image) {
                        $images[$image->id] = [
                            'stock_code' => $image->stock_code,
                            'stock_image' => $image->stock_image,
                            'image_name' => $image->image_name,
                            'file_name' => $image->file_name,
                            'image_desc' => $image->image_desc,
                            'image_type' => $image->image_type,
                        ];
                    }
                }

                // Add color to eav_attribute_option table if doesnt exist.
                $exists = $db->table($site->prefix.'eav_attribute_option_value')
                    ->where('value', $sku->colour)
                    ->Exists();

                if (!$exists) {
                    $option_id = $db->table($site->prefix.'eav_attribute_option')
                        ->insertGetId([
                            'attribute_id' => $colour_attribute_id,
                            'sort_order' => 99
                        ]);
                    $db->table($site->prefix.'eav_attribute_option_value')
                        ->insert([
                            'option_id' => $option_id,
                            'value' => $sku->colour
                        ]);
                    $colour_option_id = $option_id;
                } else {
                    $color_option = $db->table($site->prefix.'eav_attribute_option_value')
                        ->where('value', $sku->colour)
                        ->first();
                    $colour_option_id = $color_option->option_id;
                }

                // Colour swatch
                $exists = $db->table($site->prefix.'eav_attribute_option_swatch')
                    ->where('option_id', $colour_option_id)
                    ->where('store_id', 0)
                    ->Exists();

                if (!$exists) {
                    $db->table($site->prefix.'eav_attribute_option_swatch')
                        ->updateOrInsert(
                            [
                                'option_id' => $colour_option_id,
                                'store_id' => 0,
                            ],
                            [
                                'type' => 1,
                                'value' => "#000"
                            ]
                        );
                }

                // Add size to eav_attribute_option table if doesnt exist.
                $exists = $db->table($site->prefix.'eav_attribute_option_value')
                    ->where('value', $sku->size)
                    ->Exists();

                if (!$exists) {
                    $option_id = $db->table($site->prefix.'eav_attribute_option')
                        ->insertGetId([
                            'attribute_id' => $size_attribute_id,
                            'sort_order' => 99
                        ]);
                    $db->table($site->prefix.'eav_attribute_option_value')
                        ->insert([
                            'option_id' => $option_id,
                            'value' => $sku->size
                        ]);
                    $size_option_id = $option_id;
                } else {
                    $size_option = $db->table($site->prefix.'eav_attribute_option_value')
                        ->where('value', $sku->size)
                        ->orderBy('option_id', 'desc')
                        ->first();
                    $size_option_id = $size_option->option_id;
                }

                // Size swatch
                $exists = $db->table($site->prefix.'eav_attribute_option_swatch')
                    ->where('option_id', $size_option_id)
                    ->where('store_id', 0)
                    ->Exists();

                if (!$exists) {
                    $db->table($site->prefix.'eav_attribute_option_swatch')
                        ->updateOrInsert(
                            [
                                'option_id' => $size_option_id,
                                'store_id' => 0,
                            ],
                            [
                                'type' => 0,
                                'value' => $sku->size
                            ]
                        );
                }

                $manufacturer_option_id = 0;
                if (!empty($sku->manufacturer)) {
                    // Add manufacturer to eav_attribute_option table if doesnt exist.
                    $exists = $db->table($site->prefix.'eav_attribute_option_value')
                        ->where('value', $sku->manufacturer)
                        ->Exists();

                    if (!$exists) {
                        $option_id = $db->table($site->prefix.'eav_attribute_option')
                            ->insertGetId([
                                'attribute_id' => $manufacturer_attribute_id,
                                'sort_order' => 99
                            ]);
                        $db->table($site->prefix.'eav_attribute_option_value')
                            ->insert([
                                'option_id' => $option_id,
                                'value' => $sku->manufacturer
                            ]);
                        $manufacturer_option_id = $option_id;
                    } else {
                        $manufacturer_option = $db->table($site->prefix.'eav_attribute_option_value')
                            ->where('value', $sku->manufacturer)
                            ->first();
                        $manufacturer_option_id = $manufacturer_option->option_id;
                    }
                    $manufacturers[$manufacturer_option_id] = $sku->manufacturer;
                }

                // Wholesale price.
                $price = $sku->sell_price;
                // Parent price list price.
                if ($site_migration->price > 0) {
                    $price = $site_migration->price;
                }
                // Child price list price.
                if (isset($site_stock_codes_prices[$sku->stock_code])) {
                    $price = $site_stock_codes_prices[$sku->stock_code];
                }

                $skus[$sku->stock_code] = [
                    'id' => $sku->id,
                    'stock_code' => $sku->stock_code,
                    'options_container' => 'container2',
                    'msrp_display_actual_price' => 0,
                    //'name' => $sku->description,
                    'name' => $sku->stock_code,
                    'price' => $price,
                    'status' => 1,
                    'visibility' => 1,
                    'meta_title' => !empty($sku->meta_title) ? $sku->meta_title : $sku->stock_code,
                    'meta_description' => !empty($sku->meta_description) ? $sku->meta_description : $sku->stock_code,
                    'meta_keywords' => !empty($sku->meta_keywords) ? $sku->meta_keywords : $sku->stock_code,
                    'url_key' => strtolower(str_replace(" ", "-", $sku->stock_code)),
                    'quantity_and_stock_status' => 1,
                    'manufacturer' => $manufacturer_option_id,
                    'color' => $colour_option_id,
                    'size' => $size_option_id,
                    'tax_class_id' => 2,
                    'description' => $sku->description,
                    'weight' => round($sku->weight / 1000, 2),
                    'height' => $sku->height,
                    'width' => $sku->width,
                    'country_of_manufacture' => $sku->country_of_manufacture,
                    'stock_option' => json_decode($sku->stock_option),
                    'user_defined' => json_decode($sku->user_defined),
                    'images' => array_reverse($images),
                    'run_to_zero' => $sku->run_to_zero,
                    'deleted' => $sku->deleted,
                ];
            }
        }

        // Add manufacturer to eav_attribute_option table if doesnt exist.
        $exists = $db->table($site->prefix.'eav_attribute_option_value')
            ->where('value', $data->manufacturer)
            ->Exists();

        if (!$exists) {
            $option_id = $db->table($site->prefix.'eav_attribute_option')
                ->insertGetId([
                    'attribute_id' => $manufacturer_attribute_id,
                    'sort_order' => 99
                ]);
            $db->table($site->prefix.'eav_attribute_option_value')
                ->insert([
                    'option_id' => $option_id,
                    'value' => $data->manufacturer
                ]);
            $manufacturer_option_id = $option_id;
        } else {
            $manufacturer_option = $db->table($site->prefix.'eav_attribute_option_value')
                ->where('value', $data->manufacturer)
                ->first();
            $manufacturer_option_id = $manufacturer_option->option_id;
        }

        $price = $data->sell_price;
        if ($site_migration->price > 0) {
            $price = $site_migration->price;
        }

        if ($price > 0 && $data->deleted != -1) {
            $deleted = 0;
        }

        $migrate_data = [
            'id' => $data->id,
            'stock_code' => $data->stock_code,
            'name' => $data->stock_code,
            'short_description' => $data->stock_desc,
            'description' => $data->long_desc,
            'options_container' => 'container2',
            'msrp_display_actual_price' => 0,
            'price' => $price,
            'special_price' => $price,
            'status' => 1,
            'visibility' => 4,
            'meta_title' => !empty($data->meta_title) ? $data->meta_title : $data->stock_code." - ".(!empty($data->manufacturer) ? $data->manufacturer." " : "").(!empty($data->stock_type) ? $data->stock_type." " : "").(!empty($data->stock_ttype) ? $data->stock_ttype." " : ""),
            'meta_description' => !empty($data->meta_description) ? $data->meta_description : $data->stock_code." - ".(!empty($data->manufacturer) ? $data->manufacturer." " : "").(!empty($data->stock_type) ? $data->stock_type." " : "").(!empty($data->stock_ttype) ? $data->stock_ttype." " : ""),
            'meta_keywords' => !empty($data->meta_keywords) ? $data->meta_keywords : $data->stock_code,
            'country_of_manufacture' => 'GB',
            'url_key' => strtolower(str_replace(" ", "-", $data->stock_code)),
            'quantity_and_stock_status' => 1,
            'tax_class_id' => 2,
            'weight' => round($data->weight / 1000, 2),
            'height' => $data->height,
            'width' => $data->width,
            'manufacturer' => $manufacturer_option_id,
            'manufacturer_name' => $data->manufacturer,
            'stock_ttype' => $data->stock_ttype,
            'stock_type' => $data->stock_type,
            'stock_sub_type' => $data->stock_sub_type,
        ];

        if (isset($_GET['debug'])) {
            echo "<pre>";
            print_r($data);
            print_r($migrate_data);exit;
        }

        $attribute_key_to_id = [];
        $attributes = $db->table($site->prefix.'eav_attribute')
            ->where('entity_type_id', '=', $entity_type_id)
            ->get();

        foreach ($attributes as $attribute) {
            $attribute_key_to_id[$attribute->backend_type][$attribute->attribute_code] = $attribute->attribute_id;
        }
        /** Attribute Table - End */

        /** Parent Product Base Tables - Start */
        if (empty($skus)) {
            $type_id = "simple";
        } else {
            $type_id = "configurable";
        }

        $db->table($site->prefix.'catalog_product_entity')
            ->updateOrInsert(
                [
                    'sku' => $data->stock_code
                ],
                [
                    'attribute_set_id' => isset($site_defaults[$site->id]->entity_type_id) ? $site_defaults[$site->id]->entity_type_id : 0,
                    'type_id' => $type_id,
                    'has_options' => 1,
                    'required_options'=> 1
                ]
            );

        $entity = $db->table($site->prefix.'catalog_product_entity')
            ->where('sku', '=', $data->stock_code)
            ->first();

        if (empty($entity->entity_id)) {
            return;
        } elseif ($data->sell_price == 0 || $data->deleted == -1) {
            $delete_entity_ids[] = $entity->entity_id;
            $delete_stock_codes[] = $data->stock_code;
        }
        $entity_ids[] = $entity->entity_id;
        $stock_codes[] = $data->stock_code;

        ParentProduct::migrate_retail_categories($entity, $migrate_data, $db, $site, $site_defaults);

        // Catalog Product Index Price
        if (!empty($all_customer_groups)) {
            foreach ($all_customer_groups as $customer_group) {
                try {
                    $db->table($site->prefix.'catalog_product_index_price')
                        ->updateOrInsert(
                            [
                                'entity_id' => $entity->entity_id,
                                'website_id' => 1,
                                'customer_group_id' => $customer_group->customer_group_id,
                                'tax_class_id' => $migrate_data['tax_class_id']
                            ],
                            [
                                'price' => $migrate_data['price'],
                                'final_price' => $migrate_data['price'],
                                'min_price' => $migrate_data['price'],
                                'max_price' => $migrate_data['price'],
                            ]
                        );
                } catch (Exception $ex) {

                }
            }
        }

        $search_terms = [
            'name' => [
                'attribute_id' => isset($site_defaults[$site->id]->search_terms_name_attribute_id) ? $site_defaults[$site->id]->search_terms_name_attribute_id : 0,
                'data_index' => $data->stock_code,
            ],
            'sku' => [
                'attribute_id' => isset($site_defaults[$site->id]->search_terms_sku_attribute_id) ? $site_defaults[$site->id]->search_terms_sku_attribute_id : 0,
                'data_index' => $data->stock_code,
            ],
            'description' => [
                'attribute_id' => isset($site_defaults[$site->id]->search_terms_description_attribute_id) ? $site_defaults[$site->id]->search_terms_description_attribute_id : 0,
                'data_index' => $data->long_desc,
            ],
            'manufacturer' => [
                'attribute_id' => isset($site_defaults[$site->id]->search_terms_manufacturer_attribute_id) ? $site_defaults[$site->id]->search_terms_manufacturer_attribute_id : 0,
                'data_index' => $data->manufacturer,
            ]
        ];

        // Website visibility
        $db->table($site->prefix.'catalog_product_website')
            ->updateOrInsert(
                [
                    'website_id' => 1,
                    'product_id' => $entity->entity_id
                ]
            );

        $stock_level = StockLevel::where('stock_code', '=', $entity->sku)->first();
        $stock_status = StockStatus::where('stock_code', '=', $entity->sku)
            ->where('site', '=', 'Head Office')
            ->first();

        if (
            !is_array($stock_level) &&
            !is_object($stock_level)
        ) {
            $stock_level = [];
        }

        $qty = 0;
        if (isset($stock_level->available)) {
            // $qty = $stock_level->available;
        }

        if (isset($stock_status->level)) {
            $qty = $stock_status->level;
        }

        $backorders = 0;
        $use_config_backorders = 1;
        if ($data->run_to_zero == -1) {
            $backorders = 0;
            $use_config_backorders = 0;
        }

        // Stock
        $db->table($site->prefix.'cataloginventory_stock_item')
            ->updateOrInsert(
                [
                    'stock_id' => 1,
                    'product_id' => $entity->entity_id
                ],
                [
                    'qty' => $qty,
                    'is_in_stock' => 1,
                    'min_sale_qty' => 1,
                    'max_sale_qty' => 1000,
                    'notify_stock_qty' => 1,
                    'manage_stock' => 1,
                    'backorders' => $backorders,
                    'use_config_backorders' => $use_config_backorders
                ]
            );
        $db->table($site->prefix.'cataloginventory_stock_status')
            ->updateOrInsert(
                [
                    'stock_id' => 1,
                    'product_id' => $entity->entity_id,
                    'website_id' => 0
                ],
                [
                    'qty' => $qty,
                    'stock_status' => 1,
                ]
            );

        // Attributes
        $db->table($site->prefix.'catalog_product_super_attribute')
            ->updateOrInsert(
                [
                    'product_id' => $entity->entity_id,
                    'attribute_id' => $super_attribute_colour_id,
                ],
                [
                    'position' => 0,
                ]
            );

        $db->table($site->prefix.'catalog_product_super_attribute')
            ->updateOrInsert(
                [
                    'product_id' => $entity->entity_id,
                    'attribute_id' => $super_attribute_size_id,
                ],
                [
                    'position' => 0,
                ]
            );

        $super_attribute_colour = $db->table($site->prefix.'catalog_product_super_attribute')
            ->where('product_id', '=', $entity->entity_id)
            ->where('attribute_id', '=', $super_attribute_colour_id)
            ->first();

        if (empty($super_attribute_colour->product_super_attribute_id)) {
            return;
        }

        $db->table($site->prefix.'catalog_product_super_attribute_label')
            ->updateOrInsert(
                [
                    'product_super_attribute_id' => $super_attribute_colour->product_super_attribute_id
                ],
                [
                    'value' => $super_attribute_colour_label,
                    'store_id' => 0,
                    'use_default' => 0,
                ]
            );

        $super_attribute_size = $db->table($site->prefix.'catalog_product_super_attribute')
            ->where('product_id', '=', $entity->entity_id)
            ->where('attribute_id', '=', $super_attribute_size_id)
            ->first();

        if (empty($super_attribute_size->product_super_attribute_id)) {
            return;
        }

        $db->table($site->prefix.'catalog_product_super_attribute_label')
            ->updateOrInsert(
                [
                    'product_super_attribute_id' => $super_attribute_size->product_super_attribute_id
                ],
                [
                    'value' => $super_attribute_size_label,
                    'store_id' => 0,
                    'use_default' => 0,
                ]
            );
        /** Parent Product Base Tables - End */

        /** Parent Product Variable Tables - Start */
        if (!empty($attribute_key_to_id)) {
            foreach ($attribute_key_to_id as $type => $ids) {

                if (empty($ids)) {
                    continue;
                }

                $table = "";
                switch ($type) {
                    case "varchar":
                        $table = $site->prefix."catalog_product_entity_varchar";
                        break;
                    case "decimal":
                        $table = $site->prefix."catalog_product_entity_decimal";
                        break;
                    case "static":
                        continue;
                        break;
                    case "int":
                        $table = $site->prefix."catalog_product_entity_int";
                        break;
                    case "datetime":
                        $table = $site->prefix."catalog_product_entity_datetime";
                        break;
                    case "text":
                        $table = $site->prefix."catalog_product_entity_text";
                        break;
                    default:
                        continue;
                        break;
                }

                foreach ($ids as $attribute_code => $attribute_id) {
                    foreach ($migrate_data as $migrate_key => $migrate_value) {

                        if ($attribute_code != $migrate_key) {
                            continue;
                        }

                        if (isset($_GET[$type])) {
                            echo "KEY = ".$migrate_key;
                            echo $cmd ? "\n" : "<br/>";
                        }


                        $db->table($table)
                            ->updateOrInsert(
                                [
                                    'attribute_id' => $attribute_id,
                                    'entity_id' => $entity->entity_id,
                                    'store_id' => 0,
                                ],
                                [
                                    'value' => $migrate_value,
                                ]
                            );

                        //echo "Attribute Code: ".$attribute_code.", Entity Code: ".$entity->sku.", Value: ".$migrate_value."<br/>";
                    }
                }
            }
        }
        /** Parent Product Variable Tables - End */

        /** Product Variable + Link - Start */
        $db->table($site->prefix.'catalog_product_super_link')->where('parent_id', '=', $entity->entity_id)->delete();

        $filename = "";

        $db->table($site->prefix.'catalog_product_entity_media_gallery_value_to_entity')
            ->where('entity_id', '=', $entity->entity_id)
            ->delete();

        $db->table($site->prefix.'catalog_product_entity_media_gallery_value')
            ->where('entity_id', '=', $entity->entity_id)
            ->delete();

        $image_attribute_ids = isset($site_defaults[$site->id]->image_attribute_ids) ? explode(",", $site_defaults[$site->id]->image_attribute_ids) : [];
        foreach ($image_attribute_ids as $image_attribute_id) {
            $db->table($site->prefix.'catalog_product_entity_varchar')
                ->where('entity_id', '=', $entity->entity_id)
                ->where('attribute_id', '=', $image_attribute_id)
                ->delete();
        }

        if (!empty($skus)) {
            foreach ($skus as $data) {

                $db->table($site->prefix.'catalog_product_entity')
                    ->updateOrInsert(
                        [
                            'sku' => $data['stock_code']
                        ],
                        [
                            'attribute_set_id' => 4,
                            'type_id' => 'simple',
                        ]
                    );

                $product = $db->table($site->prefix.'catalog_product_entity')
                    ->where('sku', '=', $data['stock_code'])
                    ->first();

                if (empty($product->entity_id)) {
                    return;
                } elseif ($data['price'] == 0 || $data['deleted'] == -1) {
                    $delete_entity_ids[] = $product->entity_id;
                    $delete_stock_codes[] = $data['stock_code'];
                    echo $cmd ? "\n" : "<br/>";
                }

                $entity_ids[] = $product->entity_id;

                $db->table($site->prefix.'catalog_product_entity_media_gallery_value_to_entity')
                    ->where('entity_id', '=', $product->entity_id)
                    ->delete();

                $db->table($site->prefix.'catalog_product_entity_media_gallery_value')
                    ->where('entity_id', '=', $product->entity_id)
                    ->delete();

                $image_attribute_ids = isset($site_defaults[$site->id]->image_attribute_ids) ? explode(",", $site_defaults[$site->id]->image_attribute_ids) : [];
                foreach ($image_attribute_ids as $image_attribute_id) {
                    $db->table($site->prefix.'catalog_product_entity_varchar')
                        ->where('entity_id', '=', $product->entity_id)
                        ->where('attribute_id', '=', $image_attribute_id)
                        ->delete();
                }

                if (isset($_GET['image_debug'])) {
                    echo "<pre>";
                    print_r($data['images']);
                    echo "</pre>";
                }

                if (!empty($data['images'])) {
                    foreach ($data['images'] as $image) {

                        $list = explode("\\", str_replace("P:\\Khaos\Images\\", "", $image['file_name']));

                        if (count($list) > 1) {
                            $folder = $list[0];
                            $tmp_filename = $list[1];
                        } elseif (!empty($image['image_name'])) {
                            $list = explode("\\", str_replace("P:\\Khaos\Images\\", "", $image['image_name']));
                            if (count($list) == 1) {
                                continue;
                            }
                            $folder = $list[0];
                            $tmp_filename = $list[1];
                        } else {
                            continue;
                        }

                        $ok = 0;

                        $exts = [
                            'png',
                            'jpg',
                            'jpeg',
                            'gif',
                            'tif',
                            'bmp',
                            'PNG',
                            'JPG',
                            'JPEG',
                            'GIF',
                            'TIF',
                            'BMP'
                        ];
                        $found_ext = 0;
                        foreach ($exts as $ext) {
                            if (strpos($tmp_filename, $ext) > -1) {
                                $found_ext = 1;
                                break;
                            }
                        }
                        if ($found_ext == 0) {
                            $tmp_filename = $tmp_filename.".jpg";
                        }

                        if (is_dir($magento_image_directory.$folder)) {
                            foreach (scandir($magento_image_directory.$folder) as $file) {

                                if (strtolower($tmp_filename) != strtolower($file)) {
                                    continue;
                                }

                                $filename = "khaos/".$folder."/".$file;

                                $ok = 1;
                                break;
                            }
                        }

                        if (!$ok) {
                            continue;
                        }

                        echo $product->entity_id." = ".$filename;
                        echo $cmd ? "\n" : "<br/>";

                        // Current filename
                        $current_filename = $db->table($site->prefix.'catalog_product_entity_varchar')
                            ->where('attribute_id', 84)
                            ->where('entity_id', $product->entity_id)
                            ->first();

                        if (empty($current_filename) && !empty($filename)) {
                            $db->table($site->prefix.'catalog_product_entity_varchar')
                                ->updateOrInsert(
                                    [
                                        'attribute_id' => $image_attribute_1_id,
                                        'store_id' => 0,
                                        'entity_id' => $product->entity_id
                                    ],
                                    [
                                        'value' => $filename,
                                    ]);

                        }

                        $attribute_ids = isset($site_defaults[$site->id]->image_attribute_ids) ? explode(",", $site_defaults[$site->id]->image_attribute_ids) : [];
                        foreach ($attribute_ids as $attribute_id) {

                            $db->table($site->prefix.'catalog_product_entity_varchar')
                                ->updateOrInsert(
                                    [
                                        'attribute_id' => $attribute_id,
                                        'store_id' => 0,
                                        'entity_id' => $product->entity_id
                                    ],
                                    [
                                        'value' => $filename,
                                    ]
                                );
                        }

                        $db->table($site->prefix.'catalog_product_entity_media_gallery')
                            ->updateOrInsert(
                                [
                                    'attribute_id' => $image_attribute_2_id,
                                    'media_type' => 'image',
                                    'value' => $filename
                                ],
                                [
                                    'disabled' => 0,
                                ]
                            );

                        $media_value = $db->table($site->prefix.'catalog_product_entity_media_gallery')
                            ->where('value', '=', $filename)
                            ->first();

                        $db->table($site->prefix.'catalog_product_entity_media_gallery_value_to_entity')
                            ->updateOrInsert(
                                [
                                    'value_id' => $media_value->value_id,
                                    'entity_id' => $product->entity_id,
                                ],
                                []
                            );

                        $db->table($site->prefix.'catalog_product_entity_media_gallery_value')
                            ->updateOrInsert(
                                [
                                    'value_id' => $media_value->value_id,
                                    'entity_id' => $product->entity_id,
                                    'store_id' => 0
                                ],
                                [
                                    'label' => NULL,
                                    'position' => 0,
                                    'disabled' => 0
                                ]
                            );
                    }
                }

                // Website visibility
                $db->table($site->prefix.'catalog_product_website')
                    ->updateOrInsert(
                        [
                            'website_id' => 1,
                            'product_id' => $product->entity_id
                        ]
                    );

                $stock_level = StockLevel::where('stock_code', '=', $product->sku)->first();

                if (
                    !is_array($stock_level) &&
                    !is_object($stock_level)
                ) {
                    $stock_level = [];
                }

                $qty = 0;
                if (isset($stock_level->available)) {
                    $qty = $stock_level->available;
                }

                $backorders = 0;
                $use_config_backorders = 1;
                if ($data['run_to_zero'] == -1) {
                    $backorders = 0;
                    $use_config_backorders = 0;
                }

                // Stock
                $db->table($site->prefix.'cataloginventory_stock_item')
                    ->updateOrInsert(
                        [
                            'stock_id' => 1,
                            'product_id' => $product->entity_id
                        ],
                        [
                            'qty' => $qty,
                            'is_in_stock' => 1,
                            'min_sale_qty' => 1,
                            'backorders' => $backorders,
                            'use_config_backorders' => $use_config_backorders
                        ]
                    );

                $db->table($site->prefix.'cataloginventory_stock_status')
                    ->updateOrInsert(
                        [
                            'stock_id' => 1,
                            'product_id' => $product->entity_id,
                            'website_id' => 0
                        ],
                        [
                            'qty' => $qty,
                            'stock_status' => 1,
                        ]
                    );

                // Link
                $db->table($site->prefix.'catalog_product_super_link')
                    ->updateOrInsert(
                        [
                            'product_id' => $product->entity_id,
                            'parent_id' => $entity->entity_id
                        ]
                    );

                // Attributes
                if (!empty($attribute_key_to_id)) {
                    foreach ($attribute_key_to_id as $type => $ids) {

                        if (empty($ids)) {
                            continue;
                        }

                        $table = "";
                        switch ($type) {
                            case "varchar":
                                $table = $site->prefix."catalog_product_entity_varchar";
                                break;
                            case "decimal":
                                $table = $site->prefix."catalog_product_entity_decimal";
                                break;
                            case "static":
                                continue;
                                break;
                            case "int":
                                $table = $site->prefix."catalog_product_entity_int";
                                break;
                            case "datetime":
                                $table = $site->prefix."catalog_product_entity_datetime";
                                break;
                            case "text":
                                $table = $site->prefix."catalog_product_entity_text";
                                break;
                            default:
                                continue;
                                break;
                        }

                        if (!empty($data) && !empty($ids)) {
                            foreach ($ids as $attribute_code => $attribute_id) {
                                foreach ($data as $migrate_key => $migrate_value) {

                                    if (is_object($migrate_value) || is_array($migrate_value)) {
                                        continue;
                                    }
                                    if ($attribute_code != $migrate_key) {
                                        continue;
                                    }

                                    if (isset($_GET[$type])) {
                                        echo "KEY = ".$migrate_key;
                                        echo $cmd ? "\n" : "<br/>";
                                    }

                                    // value_id, attribute_id, store_id, entity_id, value
                                    $db->table($table)
                                        ->updateOrInsert(
                                            [
                                                'attribute_id' => $attribute_id,
                                                'entity_id' => $product->entity_id
                                            ],
                                            [
                                                'store_id' => 0,
                                                'value' => $migrate_value,
                                            ]
                                        );
                                    // echo "Attribute Code: ".$attribute_code.", Entity Code: ".$product->sku.", Value: ".$migrate_value."<br/>";

                                }
                            }
                        }

                    }
                }
            }
        }

        // Set parent images
        $parent_images = [];
        $attribute_ids = isset($site_defaults[$site->id]->image_attribute_ids) ? explode(",", $site_defaults[$site->id]->image_attribute_ids) : [];

        if (isset($original_data->parent_images) && count($original_data->parent_images) > 0) {
            foreach ($original_data->parent_images as $temp_img) {

                $list = explode("\\", str_replace("P:\\Khaos\Images\\", "", $temp_img->file_name));

                if (count($list) > 1) {
                    $folder = $list[0];
                    $tmp_filename = $list[1];
                } elseif (!empty($temp_img->file_name)) {
                    $list = explode("\\", str_replace("P:\\Khaos\Images\\", "", $temp_img->file_name));
                    if (count($list) == 1) {
                        continue;
                    }
                    $folder = $list[0];
                    $tmp_filename = $list[1];
                } else {
                    continue;
                }

                $ok = 0;

                $exts = ['png', 'jpg', 'jpeg', 'gif', 'tif', 'bmp'];
                $found_ext = 0;
                foreach ($exts as $ext) {
                    if (strpos($tmp_filename, $ext) > -1) {
                        $found_ext = 1;
                        break;
                    }
                }
                if ($found_ext == 0) {
                    $tmp_filename = $tmp_filename.".jpg";
                }

                if (is_dir($magento_image_directory.$folder)) {
                    foreach (scandir($magento_image_directory.$folder) as $file) {

                        if (strtolower($tmp_filename) != strtolower($file)) {
                            continue;
                        }
                        $filename = "khaos/".$folder."/".$file;
                        $ok = 1;
                        break;
                    }
                }

                if (!$ok) {
                    continue;
                }

                $parent_images[] = $filename;
            }
        }

        if (empty($parent_images) && !empty($filename)) {
            $parent_images[] = $filename;
        }

        if (isset($_GET['image_debug'])) {
            echo "<pre>";
            print_r($parent_images);
            echo "</pre>";
        }

        if (!empty($parent_images)) {
            $parent_images = array_reverse($parent_images);

            foreach ($parent_images as $filename) {
//
//                $file_chunks = explode("/", $filename);
//                $temp_img = array_pop($file_chunks);
//
//                echo $magento_image_directory;exit;
//
//                print_r($file_chunks);exit;
//
//                file_put_contents($temp_img, file_get_contents($image_url.$filename));


                echo "Parent Image: ".$entity->entity_id."=".$filename;
                echo $cmd ? "\n" : "<br/>";

                foreach ($attribute_ids as $attribute_id) {
                    $db->table($site->prefix.'catalog_product_entity_varchar')
                        ->updateOrInsert(
                            [
                                'attribute_id' => $attribute_id,
                                'store_id' => 0,
                                'entity_id' => $entity->entity_id
                            ],
                            [
                                'value' => $filename,
                            ]
                        );
                }

                $db->table($site->prefix.'catalog_product_entity_media_gallery')
                    ->updateOrInsert(
                        [
                            'attribute_id' => $image_attribute_2_id,
                            'media_type' => 'image',
                            'value' => $filename
                        ],
                        [
                            'disabled' => 0,
                        ]
                    );

                $media_value = $db->table($site->prefix.'catalog_product_entity_media_gallery')
                    ->where('value', '=', $filename)
                    ->first();

                $db->table($site->prefix.'catalog_product_entity_media_gallery_value_to_entity')
                    ->updateOrInsert(
                        [
                            'value_id' => $media_value->value_id,
                            'entity_id' => $entity->entity_id,
                        ],
                        []
                    );
                $db->table($site->prefix.'catalog_product_entity_media_gallery_value')
                    ->updateOrInsert(
                        [
                            'value_id' => $media_value->value_id,
                            'entity_id' => $entity->entity_id,
                            'store_id' => 0
                        ],
                        [
                            'label' => NULL,
                            'position' => 0,
                            'disabled' => 0
                        ]
                    );
            }
        } else {
            foreach ($attribute_ids as $attribute_id) {
                $db->table($site->prefix.'catalog_product_entity_varchar')
                    ->where('attribute_id', $attribute_id)
                    ->where('entity_id', $entity->entity_id)
                    ->delete();
            }
        }

        if (!empty($delete_entity_ids)) {

            if ($deleted == 1) {
                $delete_entity_ids = $entity_ids;
                $delete_stock_codes = $stock_codes;
            }

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

            echo "PRODUCTS (".implode(", ", $delete_entity_ids).") DELETED FROM MAGENTO / API DATABASE";
            echo $cmd ? "/n" : "<br/>";

            foreach ($delete_tables_entity_ids as $table) {
                foreach ($delete_entity_ids as $entity_id) {
                    $db->table($table)->where('entity_id', '=', $entity_id)->delete();
                }
            }
            foreach ($delete_tables_product_ids as $table) {
                foreach ($delete_entity_ids as $entity_id) {
                    $db->table($table)->where('product_id', '=', $entity_id)->delete();
                }
            }

            return array_unique($delete_stock_codes);
        }

        $db->table($site->prefix.'catalog_product_entity_int')
            ->where('attribute_id', '=', $visibility_attribute_id)
            ->where('store_id', '=', 1)
            ->delete();

        return $entity->entity_id;
        /** Product Variable + Link - End */
    }

    private static function migrate_retail_categories($entity, $migrate_data, $db, $site, $site_defaults)
    {
        // Do category stuff
        // STOCK_TTYPE = 1, STOCK_TYPE = 2, STOCK_SUB_TYPE = 3
        $default_category_id = isset($site_defaults[$site->id]->default_category_id) ? $site_defaults[$site->id]->default_category_id : 0;

        $default_attribute_set_id = isset($site_defaults[$site->id]->default_attribute_set_id) ? $site_defaults[$site->id]->default_attribute_set_id : 0;
        $attribute_id = isset($site_defaults[$site->id]->manufacturer_name_attribute_id) ? $site_defaults[$site->id]->manufacturer_name_attribute_id : 0;
        $manufacturer_category_name = isset($site_defaults[$site->id]->manufacturer_category_name) ? $site_defaults[$site->id]->manufacturer_category_name : "";

        $category_attribute_1_id = isset($site_defaults[$site->id]->category_attribute_1_id) ? $site_defaults[$site->id]->category_attribute_1_id : 0;
        $category_attribute_2_id = isset($site_defaults[$site->id]->category_attribute_2_id) ? $site_defaults[$site->id]->category_attribute_2_id : 0;
        $category_attribute_3_id = isset($site_defaults[$site->id]->category_attribute_3_id) ? $site_defaults[$site->id]->category_attribute_3_id : 0;

        $category_entity_table = $site->prefix."catalog_category_entity";
        $category_entity_varchar_table = $site->prefix."catalog_category_entity_varchar";
        $category_entity_int_table = $site->prefix."catalog_category_entity_int";
        $category_product_table = $site->prefix."catalog_category_product";
        $category_entity = [];
        $category_second_entity = [];
        $category_third_entity = [];
        $manufacturer_category_entity = [];
        $manufacturer_sub_type_category_entity = [];

        $db->table($category_product_table)
            ->where('product_id', $entity->entity_id)
            ->delete();

        if (!empty($migrate_data['manufacturer'])) {

            $manufacturer_category_varchar_set = $db->table($category_entity_varchar_table)
                ->where('value', '=', $manufacturer_category_name)
                ->where('attribute_id', '=', $attribute_id)
                ->get();
            foreach ($manufacturer_category_varchar_set as $key => $manufacturer_category_varchar) {
                $manufacturer_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $manufacturer_category_varchar->entity_id)
                    ->first();

                if (
                    !is_array($manufacturer_category_entity) &&
                    !is_object($manufacturer_category_entity)
                ) {
                    $manufacturer_category_entity = [];
                }

                if (
                    !is_array($manufacturer_category_entity) &&
                    !is_object($manufacturer_category_entity)
                ) {
                    $manufacturer_category_entity = [];
                }

                // Doesn't exist so delete varchar
                if (!isset($manufacturer_category_entity->entity_id)) {
                    $db->table($category_entity_varchar_table)
                        ->where('entity_id', '=', $manufacturer_category_varchar->entity_id)
                        ->delete();
                    $db->table($category_entity_int_table)
                        ->where('entity_id', '=', $manufacturer_category_varchar->entity_id)
                        ->delete();
                }
            }

            $manufacturer_category_entity = [];
            $manufacturer_category_varchar = $db->table($category_entity_varchar_table)
                ->where('value', '=', $manufacturer_category_name)
                ->where('attribute_id', '=', $attribute_id)
                ->first();

            if (isset($manufacturer_category_varchar)) {
                $manufacturer_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $manufacturer_category_varchar->entity_id)
                    ->first();
            }

            if (isset($manufacturer_category_entity->entity_id)) {

                $manufacturer_entity_category_varchar = $db->table($category_entity_varchar_table)
                    ->where('value', '=', $migrate_data['manufacturer_name'])
                    ->where('attribute_id', '=', $attribute_id)
                    ->get();

                $tmp_array = [];
                foreach ($manufacturer_entity_category_varchar as $temp) {
                    $manufacturer_sub_type_category_entity =
                        $db->table($category_entity_table)
                            ->where('entity_id', '=', $temp->entity_id)
                            ->where('parent_id', '=', $manufacturer_category_entity->entity_id)
                            ->first();

                    if (isset($manufacturer_sub_type_category_entity->entity_id)) {
                        break;
                    }
                }

                // Build category entity
                if (!isset($manufacturer_sub_type_category_entity->entity_id)) {

                    $manufacturer_sub_type_entity_id = $db->table($category_entity_table)
                        ->insertGetId([
                                'parent_id' => $manufacturer_category_entity->entity_id,
                                'path' => $manufacturer_category_entity->path,
                                'attribute_set_id' => $default_attribute_set_id,
                                'position' => 1,
                                'level' => 3,
                                'children_count' => 0
                            ]
                        );
                    $db->table($category_entity_table)
                        ->updateOrInsert(
                            [
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'path' => $manufacturer_category_entity->path . '/' . $manufacturer_sub_type_entity_id,
                            ]
                        );
                    $db->table($category_entity_varchar_table)
                        ->updateOrInsert(
                            [
                                'attribute_id' => $attribute_id,
                                'store_id' => 0,
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'value' => $migrate_data['manufacturer_name'],
                            ]
                        );
                    $db->table($category_entity_int_table)
                        ->updateOrInsert(
                            [
                                'attribute_id' => $category_attribute_1_id,
                                'store_id' => 0,
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'value' => 1,
                            ]
                        );
                    $db->table($category_entity_int_table)
                        ->updateOrInsert(
                            [
                                'attribute_id' => $category_attribute_2_id,
                                'store_id' => 0,
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'value' => 1,
                            ]
                        );
                    $db->table($category_entity_int_table)
                        ->updateOrInsert(
                            [
                                'attribute_id' => $category_attribute_3_id,
                                'store_id' => 0,
                                'entity_id' => $manufacturer_sub_type_entity_id,
                            ],
                            [
                                'value' => 1,
                            ]
                        );

                    $manufacturer_sub_type_category_entity = $db->table($category_entity_table)
                        ->where('entity_id', '=', $manufacturer_sub_type_entity_id)
                        ->first();
                }

                $db->table($category_product_table)
                    ->updateOrInsert(
                        [
                            'category_id' => $manufacturer_sub_type_category_entity->entity_id,
                            'product_id' => $entity->entity_id,
                        ],
                        [
                            'position' => 0,
                        ]
                    );
            }

        }

        if (!empty($migrate_data['stock_ttype'])) {

            $primary_category_varchar = [];
            $primary_category_varchar_array = $db->table($category_entity_varchar_table)
                ->where('value', '=', $migrate_data['stock_ttype'])
                ->where('attribute_id', '=', $attribute_id)
                ->get();

            if (count($primary_category_varchar_array) > 1) {
                foreach ($primary_category_varchar_array as $row) {
                    $temp_entity = $db->table($category_entity_table)
                        ->where('entity_id', '=', $row->entity_id)
                        ->where('parent_id', '=', $default_category_id)
                        ->first();
                    if (!empty($temp_entity)) {
                        $primary_category_varchar = $db->table($category_entity_varchar_table)
                            ->where('value', '=', $migrate_data['stock_ttype'])
                            ->where('entity_id', '=', $temp_entity->entity_id)
                            ->first();
                    }
                }
            } elseif (count($primary_category_varchar_array) == 1) {
                $primary_category_varchar = $primary_category_varchar_array->first();
            }

            $category_entity = [];
            if (!empty($primary_category_varchar)) {
                $category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $primary_category_varchar->entity_id)
                    ->where('parent_id', '=', $default_category_id)
                    ->first();
            }

            if (
                !is_array($category_entity) &&
                !is_object($category_entity)
            ) {
                $category_entity = [];
            }

            // Build category entity
            if (!isset($category_entity->entity_id)) {

                $category_entity_id = $db->table($category_entity_table)
                    ->insertGetId([
                            'parent_id' => $default_category_id,
                            'path' => '1/2',
                            'attribute_set_id' => $default_attribute_set_id,
                            'position' => 1,
                            'level' => 2,
                            'children_count' => 0
                        ]
                    );
                $db->table($category_entity_table)
                    ->updateOrInsert(
                        [
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'path' => '1/2/' . $category_entity_id,
                        ]
                    );
                $db->table($category_entity_varchar_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $attribute_id,
                            'store_id' => 0,
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'value' => $migrate_data['stock_ttype'],
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_1_id,
                            'store_id' => 0,
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_2_id,
                            'store_id' => 0,
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_3_id,
                            'store_id' => 0,
                            'entity_id' => $category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );

                $category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $category_entity_id)
                    ->first();
            }

            $db->table($category_product_table)
                ->updateOrInsert(
                    [
                        'category_id' => $category_entity->entity_id,
                        'product_id' => $entity->entity_id,
                    ],
                    [
                        'position' => 0,
                    ]
                );
        }

        // Second level
        if (!empty($category_entity) && !empty($migrate_data['stock_type'])) {

            // Clean Varchar Table
            $second_category_varchar_set = $db->table($category_entity_varchar_table)
                ->where('value', '=', $migrate_data['stock_type'])
                ->where('attribute_id', '=', $attribute_id)
                ->get();
            foreach ($second_category_varchar_set as $key => $second_category_varchar) {
                $second_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $second_category_varchar->entity_id)
                    ->first();

                if (
                    !is_array($second_category_entity) &&
                    !is_object($second_category_entity)
                ) {
                    $second_category_entity = [];
                }

                // Doesn't exist so delete varchar
                if (!isset($second_category_entity->entity_id)) {
                    $db->table($category_entity_varchar_table)
                        ->where('entity_id', '=', $second_category_varchar->entity_id)
                        ->delete();
                    $db->table($category_entity_int_table)
                        ->where('entity_id', '=', $second_category_varchar->entity_id)
                        ->delete();
                }
            }

            $second_category_varchar = [];

            $second_category_varchar_array = $db->table($category_entity_varchar_table)
                ->where('value', '=', $migrate_data['stock_type'])
                ->where('attribute_id', '=', $attribute_id)
                ->get();

            if (
                !is_array($second_category_varchar_array) &&
                !is_object($second_category_varchar_array)
            ) {
                $second_category_varchar_array = [];
            }

            if (count($second_category_varchar_array) > 1) {
                foreach ($second_category_varchar_array as $row) {
                    $temp_entity = $db->table($category_entity_table)
                        ->where('entity_id', '=', $row->entity_id)
                        ->where('parent_id', '=', $category_entity->entity_id)
                        ->first();
                    if (!empty($temp_entity)) {
                        $second_category_varchar = $db->table($category_entity_varchar_table)
                            ->where('value', '=', $migrate_data['stock_type'])
                            ->where('entity_id', '=', $temp_entity->entity_id)
                            ->first();
                    }
                }
            } elseif (count($second_category_varchar_array) == 1) {
                $second_category_varchar = $second_category_varchar_array->first();
            }

            $second_category_entity = [];

            if (!empty($second_category_varchar)) {
                $second_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $second_category_varchar->entity_id)
                    ->where('parent_id', '=', $category_entity->entity_id)
                    ->first();
            }

            // Build category entity
            if (!isset($second_category_entity->entity_id)) {
                $second_category_entity_id = $db->table($category_entity_table)
                    ->insertGetId([
                            'parent_id' => $category_entity->entity_id,
                            'path' => $category_entity->path,
                            'attribute_set_id' => $default_attribute_set_id,
                            'position' => 1,
                            'level' => 3,
                            'children_count' => 0
                        ]
                    );
                $db->table($category_entity_table)
                    ->updateOrInsert(
                        [
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'path' => $category_entity->path . '/' . $second_category_entity_id,
                        ]
                    );
                $db->table($category_entity_varchar_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $attribute_id,
                            'store_id' => 0,
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'value' => $migrate_data['stock_type'],
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_1_id,
                            'store_id' => 0,
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_2_id,
                            'store_id' => 0,
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_3_id,
                            'store_id' => 0,
                            'entity_id' => $second_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );

                $second_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $second_category_entity_id)
                    ->first();
            }


            $db->table($category_product_table)
                ->updateOrInsert(
                    [
                        'category_id' => $second_category_entity->entity_id,
                        'product_id' => $entity->entity_id,
                    ],
                    [
                        'position' => 0,
                    ]
                );
        }

        // Third level
        if (!empty($second_category_entity) && !empty($migrate_data['stock_sub_type'])) {

            $third_category_varchar_array = $db->table($category_entity_varchar_table)
                ->where('value', '=', $migrate_data['stock_sub_type'])
                ->where('attribute_id', '=', $attribute_id)
                ->get();

            $third_category_varchar = [];

            if (count($third_category_varchar_array) > 1) {
                foreach ($third_category_varchar_array as $row) {
                    $temp_entity = $db->table($category_entity_table)
                        ->where('entity_id', '=', $row->entity_id)
                        ->where('parent_id', '=', $second_category_entity->entity_id)
                        ->first();
                    if (!empty($temp_entity)) {
                        $third_category_varchar = $db->table($category_entity_varchar_table)
                            ->where('value', '=', $migrate_data['stock_sub_type'])
                            ->where('entity_id', '=', $temp_entity->entity_id)
                            ->first();
                    }
                }
            } elseif (count($third_category_varchar_array) == 1) {
                $third_category_varchar = $third_category_varchar_array->first();
            }

            $third_category_entity = [];

            if (!empty($third_category_varchar)) {
                $third_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $third_category_varchar->entity_id)
                    ->where('parent_id', '=', $second_category_entity->entity_id)
                    ->first();
            }

            // Build category entity
            if (!isset($third_category_entity->entity_id)) {

                $third_category_entity_id = $db->table($category_entity_table)
                    ->insertGetId([
                            'parent_id' => $second_category_entity->entity_id,
                            'path' => $second_category_entity->path,
                            'attribute_set_id' => $default_attribute_set_id,
                            'position' => 1,
                            'level' => 4,
                            'children_count' => 0
                        ]
                    );
                $db->table($category_entity_table)
                    ->updateOrInsert(
                        [
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'path' => $second_category_entity->path . '/' . $third_category_entity_id,
                        ]
                    );
                $db->table($category_entity_varchar_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $attribute_id,
                            'store_id' => 0,
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'value' => $migrate_data['stock_sub_type'],
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_1_id,
                            'store_id' => 0,
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_2_id,
                            'store_id' => 0,
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );
                $db->table($category_entity_int_table)
                    ->updateOrInsert(
                        [
                            'attribute_id' => $category_attribute_3_id,
                            'store_id' => 0,
                            'entity_id' => $third_category_entity_id,
                        ],
                        [
                            'value' => 1,
                        ]
                    );

                $third_category_entity = $db->table($category_entity_table)
                    ->where('entity_id', '=', $third_category_entity_id)
                    ->first();
            }

            $db->table($category_product_table)
                ->updateOrInsert(
                    [
                        'category_id' => $third_category_entity->entity_id,
                        'product_id' => $entity->entity_id,
                    ],
                    [
                        'position' => 0,
                    ]
                );

        }
    }
}
