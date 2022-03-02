<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultBillingAddress extends Model
{
    protected $table = 'default_billing_address';

    protected $fillable = [
        'id',
        'customer_id',
        'first_name',
        'surname',
        'company',
        'street',
        'city',
        'region',
        'postcode',
        'country_code',
        'telephone',
        'email',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

}
