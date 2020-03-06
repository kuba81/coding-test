<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

/** @var Router $router */
$router->get('/api/cache/clear', 'CacheController@clear');
$router->get('/api/exchange/info', 'ExchangeController@info');
$router->get('/api/exchange/{value}/{from}/{to}', 'ExchangeController@exchange');
