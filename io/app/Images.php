<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'id',
        'stock_code',
        'stock_image',
        'image_name',
        'file_name',
        'image_desc',
        'image_type',
        'imported',
];

    public function getColumns()
    {
        return $this->fillable;
    }

}
