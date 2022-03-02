<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;

class Order_Payment extends Model
{
    protected $table = 'order_payment';

    protected $fillable = [
        'id',
        'site_id',
        'entity_id',
        'parent_id',
        'shipping_amount',
        'amount_paid',
        'amount_refunded',
        'shipping_refunded',
        'cc_exp_month',
        'cc_ss_start_year',
        'echeck_bank_name',
        'method',
        'cc_debug_request_body',
        'cc_secure_verify',
        'protection_eligibility',
        'cc_approval',
        'cc_last_4',
        'cc_status_description',
        'echeck_type',
        'row_total',
        'cc_ss_start_month',
        'echeck_account_type',
        'last_trans_id',
        'cc_cid_status',
        'cc_owner',
        'cc_type',
        'po_number',
        'cc_exp_year',
        'cc_status',
        'echeck_routing_number',
        'account_status',
        'anet_trans_method',
        'cc_ss_issue',
        'echeck_account_name',
        'cc_avs_status',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

}
