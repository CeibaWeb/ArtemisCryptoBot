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
        )
            ->with('lastPriceSnapshot');
    }

    public function lastPriceSnapshot()
    {
        return $this->hasOne(PriceSnapshot::class, 'id', 'last_price_snapshot');
    }

    public static function byDailyPercentGain()
    {
        return static::active()
            ->limit(10)
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
}
