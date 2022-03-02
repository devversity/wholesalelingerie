<?php

namespace App\Console\Commands;

use App\Images;
use App\ParentProduct;
use App\Product;
use App\StockLevel;
use Illuminate\Console\Command;
use App\KhaosControl;
use DB;

class MigrateProducts extends Command
{
    public $magento_db;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khaos:migrate_products {all=standard}';

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

        $this->magento_db = DB::connection('mysql2');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mode = $this->argument('all');

        echo "RUNNING IN ".ucwords($mode)." MODE\n";

        $attributes = $this->attributes();
        $customer_groups = $this->magento_db->table('customer_group')->get();

        $option = "products";

        // Handle failures
        ParentProduct::failures(true);

        switch ($option) {
            case "products":

                // Determine existing products.
                $existing_products = [];
                foreach ($this->magento_db->table('catalog_product_entity')->get() as $row) {
                    $existing_products[$row->sku] = $row;
                }
                $skus = array_keys($existing_products);

                $limit = 6000;
                if (isset($_GET['limit'])) {
                    $limit = $_GET['limit'];
                }

                if ($mode == "all") {
                    $total = ParentProduct::count();
                } else {
                    $total = ParentProduct::where('imported', '=', 0)->count();
                }

                if ($total == 0) {
                    die("No products to migrate.\n");
                }

                $chunk_size = 500;
                $loops = ceil($total / $chunk_size);

                for ($i = 0; $i<$loops; $i++) {
                    echo "Processing Chunk ".($i+1)."/".$loops.". Offset: ".($i*$chunk_size)."\n";

                    if ($mode != "standard") {
                        $products = ParentProduct::with('skus', 'skus.images', 'parent_images')
                            ->offset($i * $chunk_size)
                            ->take($chunk_size)
                            ->get();
                    } else {
                        $products = ParentProduct::with('skus', 'skus.images', 'parent_images')
                            ->offset($i * $chunk_size)
                            ->where('imported', '=', 0)
                            ->take($chunk_size)
                            ->get();
                    }

                    if (count($products)) {
                        foreach ($products as $product) {

                            if (isset($_GET['sku'])) {
                                if ($product->stock_code != $_GET['sku']) {
                                    continue;
                                }
                            }

                            echo "Migrating ".$product->stock_code." ";

                            $exists = 0;
                            if (in_array($product->stock_code, $skus)) {
                                $exists = 1;
                                echo " (Update) ";
                            } else {
                                echo " (Create) ";
                            }

                            $entity_id = ParentProduct::migrate($product, $this->magento_db, $attributes, $customer_groups, '../pub/media/catalog/product/khaos/', true);

                            if (!is_array($entity_id)) { // If not marked as deleted
                                $parent_product = ParentProduct::where('stock_code', '=', $product->stock_code)->first();
                                $parent_product->imported = 2;
                                $parent_product->entity_id = $entity_id;
                                $parent_product->save();
                            } else { // If marked as deleted
                                $stock_codes = [];
                                foreach ($entity_id as $stock_code) {
                                    $parent_products = ParentProduct::where('stock_code', '=', $stock_code)->get();
                                    $products = Product::where('stock_code', '=', $stock_code)->get();
                                    foreach ($parent_products as $p) {
                                        $stock_codes[] = $p->stock_code;
                                    }
                                    foreach ($products as $p) {
                                        $stock_codes[] = $p->stock_code;
                                    }
                                }

                                foreach ($stock_codes as $stock_code) {
                                    Images::where('stock_code', '=', $stock_code)->delete();
                                    StockLevel::where('stock_code', '=', $stock_code)->delete();
                                    ParentProduct::where('stock_code', '=', $stock_code)->delete();
                                    Product::where('stock_code', '=', $stock_code)->delete();
                                }
                            }

                            echo "\n";
                        }
                    }
                }
        }
    }

    private function attributes()
    {
        $attribute_option_values = [];
        foreach ($this->magento_db->table('eav_attribute_option_value')->get() as $row) {
            $attribute_option_values[$row->option_id][$row->value_id] = $row;
        }

        $attribute_option_swatches = [];
        foreach ($this->magento_db->table('eav_attribute_option_swatch')->get() as $row) {
            $attribute_option_swatches[$row->option_id][$row->swatch_id] = $row;
        }

        $attribute_options = [];
        foreach ($this->magento_db->table('eav_attribute_option')->get() as $row) {
            $attribute_options[$row->attribute_id][$row->option_id] = $row;
        }

        $attribute_groups = [];
        foreach ($this->magento_db->table('eav_attribute_group')->get() as $row) {
            $attribute_groups[$row->attribute_group_id] = $row;
        }

        $attribute_labels = [];
        foreach ($this->magento_db->table('eav_attribute_label')->get() as $row) {
            $attribute_labels[$row->attribute_id][$row->attribute_label_id] = $row;
        }

        $this->attributes = [];
        foreach ($this->magento_db->table('eav_attribute')->get() as $row) {
            $this->attributes[$row->attribute_id] = $row;

            if (isset($attribute_labels[$row->attribute_id])) {
                $this->attributes[$row->attribute_id]->labels = $attribute_labels[$row->attribute_id];
            }
            if (isset($attribute_options[$row->attribute_id])) {
                $this->attributes[$row->attribute_id]->options = $attribute_options[$row->attribute_id];
                foreach ($this->attributes[$row->attribute_id]->options as &$option) {
                    if (isset($attribute_option_swatches[$option->option_id])) {
                        $option->swatches = $attribute_option_swatches[$option->option_id];
                    }
                    if (isset($attribute_option_values[$option->option_id])) {
                        $option->values = $attribute_option_values[$option->option_id];
                    }
                }
            }
        }

        return $this->attributes;
    }
}
