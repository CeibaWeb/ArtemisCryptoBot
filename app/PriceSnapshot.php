<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Coin;
use App\Events\SavePriceSnapshot;

class PriceSnapshot extends Model
{
    protected $guarded = [];

    protected $dispatchesEvents = [
        'saved' => SavePriceSnapshot::class
    ];

    protected $casts = [
        'recently_mooned' => 'boolean',
    ];

    public function getPercentChangeUsdAttribute()
    {
        return round($this->attributes['percent_change_usd'], 2);
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class, 'ticker', 'ticker');
    }

    public function getSatoshiPriceAttribute()
    {
        return $this->ticker === 'BTC' ? 100000000 : $this->btc_price * 100000000;
    }
}
