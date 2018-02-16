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

    public function getPercentChangeUsdAttribute()
    {
        return round($this->attributes['percent_change_usd'], 2);
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class, 'ticker', 'ticker');
    }
}
