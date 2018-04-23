@inject('coin', 'App\Coin')

@inject('snapshot', 'App\PriceSnapshot')

{{-- {{ $coin::byDailyPercentGain() }} --}}

@foreach($coin::rankWinners() as $coin)
    {{ $coin['percent_change_btc'] }}
@endforeach