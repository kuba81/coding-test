<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends Controller
{
    public function info(): Response
    {
        return response()->json([
            'error' => 0,
            'msg' => 'API written by Kuba Stawiarski',
        ]);
    }
}
