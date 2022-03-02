<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Magento\Bundle\Model\Product\Price;

use DateTime;

class PriceLists extends Model
{
    protected $table = 'price_lists';

    protected $fillable = [
        'id',
        'name',
    ];

    public static function migrate($db, $price_lists, $cmd = false)
    {
        $special_price_attribute_id = 75; // catalog_product_entity_decimal (attribute_id, store_id=0, entity_id, value = 00.00)
        $special_price_start_date_id = 76; // catalog_product_entity_datetime (attribute_id, store_id=0, entity_id, value = YYYY-MM-DD 00:00:00)
        $special_price_end_date_id = 77; // catalog_product_entity_datetime (attribute_id, store_id=0, entity_id, value = YYYY-MM-DD 00:00:00)

        $db->table('catalog_product_entity_decimal')->where('attribute_id', '=', $special_price_attribute_id)->delete();
        $db->table('catalog_product_entity_datetime')->where('attribute_id', '=', $special_price_start_date_id)->delete();
        $db->table('catalog_product_entity_datetime')->where('attribute_id', '=', $special_price_end_date_id)->delete();
        $db->table('catalog_product_entity_tier_price')->delete();

        // Import new price lists
        if ($price_lists == null) {
            return false;
        }

        foreach ($price_lists as $price_list) {

            $data = explode("-", $price_list->name);
            if (!isset($data[1])) {
                $date = "2099-01-01";
                $name = $data[0];
            } else {
                $name = $data[0];
                $date = substr($data[1], 4, 4)."-".substr($data[1], 2, 2)."-".substr($data[1], 0, 2);
                //$date = "2021-".substr($data[1], 2, 2)."-".substr($data[1], 0, 2);
            }

            echo "Importing Price List: ".$name." - ".$date;
            echo $cmd ? "\n" : "<br/>";

            if ($date < date("Y-m-d")) {
                echo "PRICE LIST HAS EXPIRED. SKIPPING";
                echo $cmd ? "\n" : "<br/>";
                continue;
            } elseif (strtotime($date) === false) {
                echo "PRICE LIST DATE IS INVALID. SKIPPING";
                echo $cmd ? "\n" : "<br/>";
                continue;
            }

            $price_list_items = PriceListStockItems::where('price_list', '=', $price_list->name)->get();
            if ($price_list_items == null) {
                return;
            }

            foreach ($price_list_items as $price_list_item) {

                $product = $db->table('catalog_product_entity')->where('sku', '=', $price_list_item->StockCode)->first();
                echo "- ".$price_list_item->StockCode;

                if (empty($product->entity_id)) {
                    echo " - Not found. Skipping.";
                    echo $cmd ? "\n" : "<br/>";
                    continue;
                }

                $new_price = $price_list_item->AmountValue;
                $product_id = $product->entity_id;

                echo " = ".$product_id;
                echo $cmd ? "\n" : "<br/>";

                //$special_price_attribute_id = 75; // catalog_product_entity_decimal (attribute_id, store_id=0, entity_id, value = 00.00)
                $special_price_start_date_id = 76; // catalog_product_entity_datetime (attribute_id, store_id=0, entity_id, value = YYYY-MM-DD 00:00:00)
                $special_price_end_date_id = 77; // catalog_product_entity_datetime (attribute_id, store_id=0, entity_id, value = YYYY-MM-DD 00:00:00)

//                $db->table('catalog_product_entity_decimal')
//                    ->updateOrInsert(
//                        [
//                            'attribute_id' => $special_price_attribute_id,
//                            'entity_id' => $product_id,
//                            'store_id' => 0,
//                        ],
//                        [
//                            'value' => $new_price,
//                        ]
//                    );

                if (
                    $new_price == 0 &&
                    $price_list_item->DiscountValue > 0
                ) {

                    $db->table('catalog_product_entity_tier_price')
                        ->updateOrInsert(
                            [
                                'entity_id' => $product_id,
                                'qty' => $price_list_item->QtyStart,
                                'website_id' => 0
                            ],
                            [
                                'all_groups' => 1,
                                'customer_group_id' => 0,
                                'percentage_value' => $price_list_item->DiscountValue,
                            ]
                        );
                } else {
                    $db->table('catalog_product_entity_tier_price')
                        ->updateOrInsert(
                            [
                                'entity_id' => $product_id,
                                'qty' => $price_list_item->QtyStart,
                                'website_id' => 0
                            ],
                            [
                                'all_groups' => 1,
                                'customer_group_id' => 0,
                                'value' => $new_price,
                            ]
                        );
                }



                $db->table('catalog_product_entity_datetime')
                    ->updateOrInsert(
                        [
                            'attribute_id' => $special_price_start_date_id,
                            'entity_id' => $product_id,
                            'store_id' => 0,
                        ],
                        [
                            'value' => date("Y-m-d 00:00:00"),
                        ]
                    );

                $db->table('catalog_product_entity_datetime')
                    ->updateOrInsert(
                        [
                            'attribute_id' => $special_price_end_date_id,
                            'entity_id' => $product_id,
                            'store_id' => 0,
                        ],
                        [
                            'value' => $date.' 23:59:59',
                        ]
                    );

                if (
                    $new_price == 0 &&
                    $price_list_item->DiscountValue > 0
                ) {
                    echo " - Added. New Discount is ".$price_list_item->DiscountValue."% (Qty: ".$price_list_item->QtyEnd.") with end date: ".$date;

                } else {
                    echo " - Added. New Price is £".$new_price." (Qty: ".$price_list_item->QtyStart.") with end date: ".$date;
                }

                echo $cmd ? "\n" : "<br/>";

            }
        }
    }

    public function items()
    {
        return $this->hasMany('App\PriceListStockItems', 'price_list', 'name');
    }

    public function getColumns()
    {
        return $this->fillable;
    }

}
