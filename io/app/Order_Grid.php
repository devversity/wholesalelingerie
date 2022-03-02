<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;

class Order_Grid extends Model
{
    protected $table = 'order_grid';

    protected $fillable = [
        'id',
        'site_id',
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

    public function getColumns()
    {
        return $this->fillable;
    }

}
