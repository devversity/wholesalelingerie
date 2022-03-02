<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyList extends Model
{
    protected $table = 'company_list';

    protected $fillable = [
        'id',
        'company_code',
        'other_ref',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }
}
