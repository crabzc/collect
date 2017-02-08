<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Collect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collect {fun}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集数据';

    /**
     * Constructor 
     */
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
        ini_set('memory_limit', '-1'); 
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $fun = $this->argument('fun');
            if(!empty($fun)) {
                $this->$fun();
            }
        } catch(Exception $e) {
            echo $e->getMessage()."\n";
        }
    }

    public function test()
    {
        echo 'This is a test'."\n";
    }
}
