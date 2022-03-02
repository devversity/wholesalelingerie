<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;

class Order_Address extends Model
{
    protected $table = 'order_address';

    protected $fillable = [
        'id',
        'site_id',
        'entity_id',
        'customer_firstname',
        'customer_surname',
        'street',
        'city',
        'region',
        'postcode',
        'country',
        'email',
        'telephone',
        'company',
        'address_type',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

}
