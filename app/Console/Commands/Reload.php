<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use UserSeeder;

class Reload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reload {--mode=dev}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        try{
            $this->call('migrate:reset');
        }catch (\Exception $exception){
        }
        $this->call('migrate');
        $this->call('db:seed');
        $this->info('Loading Original Information');
        $this->call('sync:original', ['--mode' => $this->option('mode')]);
        $this->info('Seeding test users');
        $this->call(UserSeeder::class);
    }
}

