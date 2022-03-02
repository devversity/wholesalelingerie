<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;

class Order_Item extends Model
{
    protected $table = 'order_item';

    protected $fillable = [
        'id',
        'site_id',
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

    public function getColumns()
    {
        return $this->fillable;
    }

}
