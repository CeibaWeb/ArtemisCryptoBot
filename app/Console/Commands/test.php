<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Coin;
use App\CoinApiSdk\Client;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:test {ticker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $ticker = strtoupper($this->argument('ticker'));
        // dd(array_keys($this->client->get('data/all/coinlist')['Data'][$ticker]));
    }
}
