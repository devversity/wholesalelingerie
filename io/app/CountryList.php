<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryList extends Model
{
    protected $table = 'country_list';

    protected $fillable = [
        'id',
        'country_name',
        'country_code2',
        'country_code3',
        'eu_member',
        'pays_vat',
        'currency_name',
        'currency_code',
        'zone',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }
}
