<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ExchangeController extends Controller
{
    public function info(): Response
    {
        return response()->json([
            'error' => 0,
            'msg' => 'API written by Kuba Stawiarski',
        ]);
    }

    public function exchange(Request $request): Response
    {
        $this->validateCurrencyOrThrow($request->route('from'));
        $this->validateCurrencyOrThrow($request->route('to'));

        $this->validateValueOrThrow($request->route('value'));

        return response()->json([
            'error' => 0,
            'amount' => (float) $request->route('value'),
            'fromCache' => 0,
        ]);
    }

    private function validateCurrencyOrThrow(string $currency): void
    {
        if (! in_array($currency, config('currencies.supported'))) {
            $errorTemplate = 'currency code %s not supported';

            throw new UnprocessableEntityHttpException(sprintf($errorTemplate, $currency));
        }
    }

    private function validateValueOrThrow(string $value): void
    {
        if (! is_numeric($value)) {
            throw new UnprocessableEntityHttpException('invalid value');
        }
    }
}
