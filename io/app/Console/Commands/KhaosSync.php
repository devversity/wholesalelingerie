<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\KhaosControl;
use App\ParentProduct;

class KhaosSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khaos:sync {stock_code=0} {hours=24}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all Khaos data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->khaos_web_service = new KhaosControl();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = 'GetStockList';
        $stock_code = $this->argument('stock_code');
        $hours = $this->argument('hours');

        if (!empty($stock_code))  {
            echo "Syncing '".$request."' for ".$stock_code.".\n";
            $this->khaos_web_service->request($request, 0, null, ['stock_code' => $stock_code], null, null, false, true);
        } else {
            $parent_products = ParentProduct::whereDate('updated_at','<=', date("Y-m-d G:i:s", time()-(3600*$hours)))->get();

            $parent_product_skus = [];
            foreach ($parent_products as $parent_product) {
                $parent_product_skus[$parent_product->stock_code] = $parent_product->stock_code;
            }

            if (!empty($parent_product_skus)) {
                echo "Syncing '".$request."'";
                $this->khaos_web_service->request($request, 0, null, ['stock_code' => $parent_product_skus], null, null, false, true);
                $parent_product->imported = 0;
                $parent_product->save();
            }
        }

    }


}
