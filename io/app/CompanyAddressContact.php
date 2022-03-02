<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyAddressContact extends Model
{
    protected $table = 'company_address_contact';

    protected $fillable = [
        'id',
        'address_id',
        'title',
        'forename',
        'surname',
        'jobtitle',
        'tel',
        'fax',
        'mobile',
        'email',
        'note',
        'emailsubscribe',
        'dob',
        'hide_contact',
        'hold_data',
        'is_preferred',
        'is_preferred_invoice',
        'is_preferred_delivery',
        'mailing_flag',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

}
