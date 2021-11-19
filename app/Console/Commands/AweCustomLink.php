<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AweCustomLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'awe:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "public/awe-custom" to "awe-custom"';

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
        if (file_exists(public_path('awe-custom'))) {
            return $this->error('The "public/awe-custom" directory already exists.');
        }

        $this->laravel->make('files')->link(
            app_path('awe-custom'), public_path('awe-custom')
        );

        $this->info('The [public/awe-custom] directory has been linked.');
    }
}
