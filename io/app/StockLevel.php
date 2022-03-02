<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockLevel extends Model
{
    protected $table = 'stock_levels';

    protected $fillable = [
        'id',
        'stock_code',
        'other_ref',
        'stock_desc',
        'available',
        'on_order',
        'back_order',
        'potential',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }
}
