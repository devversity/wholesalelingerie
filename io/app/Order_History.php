<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;

class Order_History extends Model
{
    protected $table = 'order_history';

    protected $fillable = [
        'id',
        'site_id',
        'entity_id',
        'comment',
        'status',
        'entity_name',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

}
