<?php

namespace App\Console\Commands;

use App\Controllers\Services\ExperienceController;
use App\Controllers\Services\HomeController;
use Illuminate\Console\Command;

class IcalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ical:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Ical from other chanel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->ical();
        update_opt('last_import_ical', time());
    }

    public function ical()
    {
        HomeController::get_inst()->_importIcal();
        ExperienceController::get_inst()->_importIcal();
    }
}
