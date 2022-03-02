<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\KhaosControl;

class KhaosStockList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khaos:stocklist {minutes=60}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Khaos data via Stock List';

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
        $minutes = $this->argument('minutes');
        $request = 'GetStockList';

        $interval = $minutes * 60;
        echo "Calling '".$request."' -  ".$minutes." minute interval changes.\n";
        $this->khaos_web_service->request($request, $interval, null, null, null, null, false, true);
    }


}
