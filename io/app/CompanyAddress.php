<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    protected $table = 'company_address';

    protected $fillable = [
        'id',
        'site_id',
        'entity_id',
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
        'imported'
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

    public function contacts()
    {
        return $this->hasMany('App\CompanyAddressContact', 'id', 'address_id');
    }

}
