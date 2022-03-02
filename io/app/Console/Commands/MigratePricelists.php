<?php

namespace App\Console\Commands;

use App\PriceLists;
use Illuminate\Console\Command;
use DB;

class MigratePricelists extends Command
{
    public $magento_db;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khaos:migrate_pricelists';

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
        PriceLists::migrate($this->magento_db, PriceLists::all(), true);
    }

}
