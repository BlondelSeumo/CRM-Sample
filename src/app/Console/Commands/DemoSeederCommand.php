<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DemoSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This custom command will generate demo data for your application to test';

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
        \Artisan::call('db:seed', [
            '--class' => \Database\Seeders\CRM\DemoDataSeeder::class,
            '--force' => true
        ]);

        $this->info('Dummy data seeded successfully.');
    }
}
