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

    public static function activeTickers()
    {
        return static::active()->get()->map(function($item) {
            return $item->ticker;
        });
    }

    public function getTickerAttribute()
    {
        return strtoupper($this->attributes['ticker']);
    }

    public function priceSnapshots()
    {
        return $this->hasMany(PriceSnapshot::class, 'ticker', 'ticker');
    }
}
