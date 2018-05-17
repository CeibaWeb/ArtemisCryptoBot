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

    public function scopeWithLastSnapshot($query)
    {
        return $query
            ->select('price_snapshots.percent_change_btc', 'coins.ticker', 'price_snapshots.btc_price', 'price_snapshots.usd_price')
            ->join('price_snapshots', function ($join) {
                $join->on('price_snapshots.ticker', '=', 'coins.ticker')->on('coins.updated_at', '=', 'price_snapshots.created_at');
            });
    }
    
    public static function rankWinners()
    {
        return static::withLastSnapshot()
            ->orderByDesc('price_snapshots.percent_change_btc')
            ->limit(10)
            ->get();
    }

    public static function rankLosers()
    {
        return static::withLastSnapshot()
            ->orderBy('price_snapshots.percent_change_btc')
            ->limit(10)
            ->get();
    }
}
