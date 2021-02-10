<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RetryFailedThirdPartyCallbacks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $failures = DB::table('failed_jobs')->where('queue', 'subscription_change_callbacks')->get();
        if (!$failures) {
            return;
        }

        $queues_identifiers = '';
        foreach ($failures as $failure) {
            $queues_identifiers .= $failure->id . ' ';
        }
        Artisan::call("queue:retry {$queues_identifiers}");
    }
}
