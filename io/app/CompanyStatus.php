<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyStatus extends Model
{
    protected $table = 'company_status';

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
