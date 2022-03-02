<?php

namespace App\Console\Commands;

use App\Images;
use App\ParentProduct;
use App\Product;
use App\StockLevel;
use Illuminate\Console\Command;
use App\KhaosControl;
use DB;

class MigrateCategories extends Command
{
    public $magento_db;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khaos:migrate_categories {all=standard}';

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
        $products = ParentProduct::all();
        $option = "categories";

        if (count($products)) {
            foreach ($products as $product) {
                echo "Updated categories for " . $product->stock_code . ".\n";
                ParentProduct::migrate($product, $this->magento_db, $attributes, $customer_groups, '../pub/media/catalog/product/khaos/', true, $option);
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
