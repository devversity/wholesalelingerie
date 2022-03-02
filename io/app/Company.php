<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $fillable = [
        'id',
        'company_code',
        'company_name',
        'other_ref',
        'web_site',
        'company_class',
        'company_type',
        'company_status',
        'source_code',
        'sale_source',
        'currency_code',
        'country',
        'country_code',
        'web_user',
        'web_password',
        'agent_name',
        'tax_reference',
        'hide_company',
        'hold_data',
        'mailing_status',
        'proforma',
        'sorder_locked',
        'customer_discount',
        'supplier',
        'ec_company',
        'pays_vat',
        'pocode_required',
        'reward_point_balance',
        'reward_points_updated',
        'payment_type',
        'earn_and_redeem_reward_points',
        'vat_relife_qualified',
        'credit_code',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

    public function addresses()
    {
        return $this->hasMany('App\CompanyAddress', 'id', 'company_id');
    }
}
