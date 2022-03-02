<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site_Migration extends Model
{
    protected $table = 'site_migration';

    protected $fillable = [
        'id',
        'site_id',
        'sku',
        'price',
        'migrate_ran',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

}
