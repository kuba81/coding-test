<?php

namespace App\Http\Controllers;

use App\Domain\CacheInterface;

class CacheController extends Controller
{
    public function clear(CacheInterface $cache)
    {
        $cache->purge();

        return response()->json([
            'error' => 0,
            'msg' => 'OK'
        ]);
    }
}
