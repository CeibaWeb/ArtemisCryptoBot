<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PriceSnapshot;

class Coin extends Model
{
    protected $guarded = [];

    public $primaryKey = 'ticker';

    public $incrementing = false;

    protected $casts = [
        'active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', '=', true);
    }

    public function isActive()
    {
        return $this->active;
    }

    public function scopeWithLastPriceSnapshot($query)
    {
        return $query->addSubSelect(
            'last_price_snapshot',
            PriceSnapshot::select('id')
                ->whereRaw('ticker = coins.ticker')
                ->orderBy('created_at', 'desc')
                ->limit(1)
        );
    }


    public static function byDailyPercentGain()
    {
        return static::active()
            ->withLastPriceSnapshot()
            ->limit(10)
            ->orderByDailyPercentGain()
            ->get();
    }

    public function scopeOrderByDailyPercentGain($query)
    {
        return $query
            ->orderBySubDesc(
                PriceSnapshot::select('percent_change_btc')
                    ->whereRaw('price_snapshots.ticker = coins.ticker')
                    ->orderBy('created_at', 'desc')
                    ->take(1)
            );
    }

    public static function byDailyPercentLoss()
    {
        return static::active()
            ->limit(10)
            ->withLastPriceSnapshot()
            ->orderByDailyPercentLoss()
            ->get();
    }

    public function scopeOrderByDailyPercentLoss($query)
    {
        return $query
            ->orderBySub(
                PriceSnapshot::select('percent_change_btc')
                    ->whereRaw('price_snapshots.ticker = coins.ticker')
                    ->orderBy('created_at', 'desc')
                    ->take(1)
            );
    }

    public static function activeTickers()
    {
        return static::active()->getTickers();
    }

    public function scopeGetTickers($query)
    {
        return $query->select('ticker')->pluck('ticker');
    }

    public static function activeTickersString()
    {
        return static::activeTickers()->implode(' ');
    }

    public function getTickerAttribute()
    {
        return strtoupper($this->attributes['ticker']);
    }

    public function priceSnapshots()
    {
        return $this->hasMany(PriceSnapshot::class, 'ticker', 'ticker');
    }

    public function hasLastPriceSnapshot()
    {
        return (bool)(gettype($this->lastPriceSnapshot) === "object");
    }

    public static function rankWinners()
    {
        return static::select('price_snapshots.percent_change_btc', 'coins.ticker', 'price_snapshots.btc_price', 'price_snapshots.usd_price')
            ->join('price_snapshots', function ($join) {
                $join->on('price_snapshots.ticker', '=', 'coins.ticker')->on('coins.updated_at', '=', 'price_snapshots.created_at');
            })
            ->orderByDesc('price_snapshots.percent_change_btc')
            ->limit(10)
            ->get();
    }

    public static function rankLosers()
    {
        return static::select('price_snapshots.percent_change_btc', 'coins.ticker', 'price_snapshots.btc_price', 'price_snapshots.usd_price')
            ->join('price_snapshots', function ($join) {
                $join->on('price_snapshots.ticker', '=', 'coins.ticker')->on('coins.updated_at', '=', 'price_snapshots.created_at');
            })
            ->orderBy('price_snapshots.percent_change_btc')
            ->limit(10)
            ->get();
    }
}
