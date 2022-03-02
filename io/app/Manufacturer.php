<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $table = 'manufacturer';

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
