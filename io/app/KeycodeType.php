<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeycodeType extends Model
{
    protected $table = 'keycode_types';

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
