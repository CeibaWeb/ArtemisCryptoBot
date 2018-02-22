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

    public function scopeWithLastPriceSnapshot($query)
    {
        return $query->addSubSelect(
            'last_price_snapshot',
            PriceSnapshot::select('id')
                ->whereRaw('ticker = coins.ticker')
                ->latest()
        )
            ->with('lastPriceSnapshot');
    }

    public function lastPriceSnapshot()
    {
        return $this->hasOne(PriceSnapshot::class, 'id', 'last_price_snapshot');
    }

    public static function byDailyPercentGain()
    {
        return static::withLastPriceSnapshot()
            ->orderByDailyPercentGain()
            ->active()
            ->get();
    }

    public function scopeOrderByDailyPercentGain($query)
    {
        return $query
            ->orderBySubDesc(
                PriceSnapshot::select('percent_change_btc')
                    ->whereRaw('price_snapshots.ticker = coins.ticker')
                    ->latest()
            );
    }

    public static function byDailyPercentLoss()
    {
        return static::withLastPriceSnapshot()
            ->orderByDailyPercentLoss()
            ->active()
            ->get();
    }

    public function scopeOrderByDailyPercentLoss($query)
    {
        return $query
            ->orderBySub(
                PriceSnapshot::select('percent_change_btc')
                    ->whereRaw('price_snapshots.ticker = coins.ticker')
                    ->latest()
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
        return (bool) (gettype($this->lastPriceSnapshot) === "object");
    }
}
