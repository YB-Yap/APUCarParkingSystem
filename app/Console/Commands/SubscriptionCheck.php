<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscription valid till date, terminate if needed';

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
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // terminate expried subscription
        Subscription::where('is_active', true)->whereDate('valid_till', '=', $yesterday->toDateString())
            ->update(['is_active' => false, 'is_expired' => true]);

        // activate inactive subscription that is not expired
        Subscription::where('is_active', false)->whereDate('valid_at', '=', $today->toDateString())
            ->update(['is_active' => true]);
    }
}
