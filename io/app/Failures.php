<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Failures extends Model
{
    protected $table = 'failures';

    protected $fillable = [
        'stock_code',
        'error',
        'attempts',
        'updated_at',
        'created_at',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }
}
