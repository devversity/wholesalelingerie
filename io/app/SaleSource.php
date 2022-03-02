<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleSource extends Model
{
    protected $table = 'sale_source';

    protected $fillable = [
        'id',
        'name',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }
}
