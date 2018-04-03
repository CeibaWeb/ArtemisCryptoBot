<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coin;

class RankController extends Controller
{
    public function show() {
        return view('winners');
    }
}
