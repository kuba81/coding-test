<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function clear()
    {
        Cache::forget(config('cache.exchange_rates.key'));

        return response()->json([
            'error' => 0,
            'msg' => 'OK'
        ]);
    }
}
