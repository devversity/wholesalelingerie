<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockStatus extends Model
{
    protected $table = 'stock_statuses';

    protected $fillable = [
        'id',
        'level',
        'on_order_level',
        'max_bo_qty',
        'buy_price',
        'sell_price',
        'websell_price',
        'bundle_price',
        'safe_level',
        'min_level',
        'site',
        'status',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }
}
