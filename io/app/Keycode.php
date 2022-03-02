<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keycode extends Model
{
    protected $table = 'keycodes';

    protected $fillable = [
        'id',
        'keycode_code',
        'keycode_type_desc',
        'source_code',
        'web_use',
        'motouse',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }
}
