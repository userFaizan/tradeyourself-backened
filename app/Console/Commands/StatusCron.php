<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Habit;
use App\Models\Todo;
class StatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:cron';

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
        \Log::info("Cron is working fine!");
     
        /*
           Write your database logic we bellow:
           Item::create(['name'=>'hello new']);
        */
        // Habit::where('status', '=', 1)->update(['status' => 0]);
        Habit::query()->update(  ['status' => 0] );
        Todo::query()->update(  ['status' => 0] );

    }
}
