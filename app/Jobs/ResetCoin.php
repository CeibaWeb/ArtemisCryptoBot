<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Coin;
use Illuminate\Support\Facades\Log;

class ResetCoin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $coin;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Coin $coin)
    {
        $this->coin = $coin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->coin->recently_mooned = false;

        $this->coin->save();

        Log::info("Reset $this->coin->ticker at $this->coin->updated_at");

    }
}
