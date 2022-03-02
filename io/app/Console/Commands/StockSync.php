<?php

namespace App\Console\Commands;

use App\ParentProduct;
use App\Product;
use App\StockLevel;
use App\StockStatus;
use Illuminate\Console\Command;
use DB;
use SoapClient;

class StockSync extends Command
{
    public $magento_db;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khaos:stock_sync {mode=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Data into Magento';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->khaos_endpoint = 'https://77.107.185.72:4433/khaoskev.exe/wsdl/IKosWeb';
        $this->endpoint = $this->khaos_endpoint;

        $opts = [
            'ssl' => [

                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ];

        $params = [
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => SOAP_1_2,
            'trace' => 1,
            'exceptions' => 1,
            'connection_timeout' => 3600,
            'stream_context' => stream_context_create($opts)
        ];

        $this->cmd = true;
        $this->soap_client = new SoapClient($this->endpoint, $params);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mode = $this->argument('mode');

        if ($mode == "reset") {
            DB::table('stock_levels')->update(['imported' => 0]);
            die("Reset all stock for sync.");
        }
        $sku = $mode;

        $already_done_stock_codes = [];
        if (!empty($sku)) {
            $parent_products = ParentProduct::where('stock_code', '=', $sku)->get();
            $products = Product::where('parent_stock_code', '=', $sku)->get();
        } else {
            $already_done_stock_codes = StockLevel::where('imported','=', 1)->get();
            $parent_products = ParentProduct::all();
            $products = Product::all();
        }

        $already_done = [];
        $stock_codes = [];

        foreach ($already_done_stock_codes as $stock) {
            $already_done[$stock->stock_code] = $stock->stock_code;
        }

        foreach ($parent_products as $product) {
            if (!in_array($product->stock_code, $already_done)) {
                $stock_codes[$product->stock_code] = $product->stock_code;
            }
        }

        foreach ($products as $product) {
            if (!in_array($product->stock_code, $already_done)) {
                $stock_codes[$product->stock_code] = $product->stock_code;
            }
        }

        $parents = [];

        $loop_count = 0;
        if (!empty($stock_codes)) {
            $chunked_child_stock_codes = array_chunk($stock_codes, 500);

            foreach ($chunked_child_stock_codes as $key => $child_stock_codes) {
                echo "Fetching stock: " . ($key + 1) . "/" . count($chunked_child_stock_codes);
                echo $this->cmd ? "\n" : "<br/>";

                try {
                    $stock_status = $this->soap_client->ExportStockStatus(implode(",", $child_stock_codes), 1);
                    $stock_status = simplexml_load_string($stock_status, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

                    $stock_data = $this->soap_client->GetStockPotential(implode(",", $child_stock_codes), 1);
                } catch (\Exception $ex) {
                    print_r($ex);exit;
                }

                if (!empty($stock_data) && !empty($stock_status)) {

                    foreach ($stock_status as $stock_status_row) {

                        $row_code = reset($stock_status_row->attributes()->CODE);
                        $stock_status = StockStatus::where('stock_code', '=', $row_code)
                            ->where('site', '=', $stock_status_row->SITE)
                            ->first();

                        if ($stock_status == null) {
                            $stock_status = new StockStatus;
                        }

                        $stock_status->stock_code = $row_code;
                        $stock_status->level = $stock_status_row->LEVEL;
                        $stock_status->site = $stock_status_row->SITE;
                        $stock_status->on_order_level = $stock_status_row->ON_ORDER_LEVEL;
                        $stock_status->max_bo_qty = $stock_status_row->MAX_BO_QTY;
                        $stock_status->buy_price = $stock_status_row->BUY_PRICE;
                        $stock_status->sell_price = $stock_status_row->SELL_PRICE;
                        $stock_status->websell_price = $stock_status_row->WEBSELL_PRICE;
                        $stock_status->bundle_price = $stock_status_row->BUNDLE_PRICE;
                        $stock_status->safe_level = $stock_status_row->SAFE_LEVEL;
                        $stock_status->min_level = $stock_status_row->MIN_LEVEL;
                        $stock_status->status = $stock_status_row->STATUS;
                        $stock_status->save();

                        echo "Updated stock status: ". $row_code." (".$stock_status_row->LEVEL."/".$stock_status_row->SITE.")";
                        echo $this->cmd ? "\n" : "<br/>";
                    }

                    foreach ($stock_data as $stock) {

                        $stock_level = StockLevel::where('stock_code', '=', $stock->StockCode)->first();

                        if ($stock_level == null) {
                            $stock_level = new StockLevel;
                        }

                        $product = Product::where('stock_code', '=', $stock->StockCode)->first();

                        if (!empty($product->parent_stock_code)) {
                            $parents[$product->parent_stock_code] = $product->parent_stock_code;
                        }

                        $stock_level->stock_code = $stock->StockCode;
                        $stock_level->other_ref = $stock->OtherRef;
                        $stock_level->stock_desc = $stock->StockDesc;
                        $stock_level->available = $stock->Available;
                        $stock_level->on_order = $stock->OnOrder;
                        $stock_level->back_order = $stock->BackOrder;
                        $stock_level->potential = $stock->Potential;
                        $stock_level->imported = 1;
                        $stock_level->save();

                        echo "Updated stock level: ". $stock->StockCode." (".$stock_level->available.")";
                        echo $this->cmd ? "\n" : "<br/>";
                    }
                }
                $loop_count++;

                if ($loop_count > 15) {
                    break;
                }
            }
        }

        if (!empty($parents)) {
            foreach ($parents as $stock_code) {

                $parent_product = ParentProduct::where('stock_code', '=', $stock_code)->first();
                $parent_product->imported = 0;
                $parent_product->save();
            }
        }
    }
}
