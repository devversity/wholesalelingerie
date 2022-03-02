<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailingStatus extends Model
{
    protected $table = 'mailing_status';

    protected $fillable = [
        'id',
        'description',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }
}
