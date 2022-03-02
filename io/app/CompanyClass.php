<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyClass extends Model
{
    protected $table = 'company_class';

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
