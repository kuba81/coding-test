<?php

namespace App\Http\Controllers;

class CacheController extends Controller
{
    public function clear()
    {
        return response()->json([
            'error' => 1,
            'msg' => 'not implemented'
        ]);
    }
}
