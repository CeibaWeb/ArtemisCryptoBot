<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coin;

class RankController extends Controller
{
    public function show() {
        \Debugbar::enable();
        
        return view('winners');
    }
}
