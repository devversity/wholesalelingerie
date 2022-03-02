<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Sites;
use App\Order;

class SitesCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khaos:sites {mode=refresh} {site_id=0} {hours=1} {delete_mode=1} {sku=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate site data from API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->sites = new Sites;
        $this->orders = new Order;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mode = $this->argument('mode');
        $site_id = $this->argument('site_id');
        $hours = $this->argument('hours');
        $sku = $this->argument('sku');
        $delete_mode = $this->argument('delete_mode');

        echo "Calling 'site ".$mode."' - site id: ".$site_id."\n";

        switch($mode) {
            case "migrate":
                //$this->sites->refresh($site_id, true);
                $this->sites->migrate($site_id, true, $hours, $sku, $delete_mode);
                break;
            case "refresh":
                $this->sites->refresh($site_id, true);
                break;
            case "orders":
                $this->orders->retail_orders($site_id, 0, true);
                break;
        }
    }


}
