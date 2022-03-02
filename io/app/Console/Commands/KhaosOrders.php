<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\KhaosControl;
use App\Order;
use DB;

class KhaosOrders extends Command
{

    public $magento_db;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khaos:orders {site_id=-1} {order_id=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export / Import order data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->magento_db = DB::connection('mysql2');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $o_id = $this->argument('order_id');
        $site_id = $this->argument('site_id');
        $dir = "./../io/public/khaos/out/";

        if (empty($o_id))  {
            $o_id = null;
        }

        if ($site_id > -1) {
            Order::retail_orders($site_id, $o_id, true, $dir);
        } else {
            Order::pull($this->magento_db, true);
            Order::clean($dir, true);
            Order::transform($dir, $o_id, true);
            Order::export($dir, $o_id, true);
            Order::import($dir, $o_id, true, $this->magento_db);
        }
    }


}
