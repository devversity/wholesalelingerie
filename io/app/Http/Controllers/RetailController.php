<?php

namespace App\Http\Controllers;

use DB;
use Redirect;
use Schema;
use App\Sites;
use App\Order;

class RetailController extends Controller
{
    public function __construct()
    {
        $this->sites = new Sites;
    }

    public function attributes($site_id = 0, $override = 0)
    {
        $this->sites->attributes($site_id, $override,false);
    }

    public function refresh($site_id = 0)
    {
        $this->sites->refresh($site_id, false);
    }

    public function migrate($site_id = 0, $hours = 24, $sku = 0)
    {
        $this->sites->refresh($site_id, false);
        $this->sites->migrate($site_id, false, $hours, $sku);
    }

    /**
     * Pull orders from Magento
     */
    public function orders($site_id = null, $o_id = null, $cmd = false)
    {
        Order::retail_orders($site_id, $o_id, $cmd);
    }

    public function clear($site_id = 0)
    {
        $this->sites->clear($site_id, false);
    }

}
