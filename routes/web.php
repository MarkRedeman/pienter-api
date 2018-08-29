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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'namespace' => '\App\PlusOne\Http',
    //    'middleware' => ['api'],
], function ($router) {
    $router->post('authenticate', 'AuthenticationController@post');

    $router->group(['middleware' => 'plus-one'], function ($router) {
        $router->get('members', 'MembersController@index');
        $router->post('members', 'MembersController@store');
        $router->put('members/{memberId}', 'MembersController@update');

        $router->get('products', 'ProductsController@index');
        $router->get('committees', 'CommitteesController@index');

        $router->get('orders', 'OrdersController@index');
        $router->post('orders', 'OrdersController@post');
        $router->delete('orders/{orderId}', 'OrdersController@remove');
    });
});
