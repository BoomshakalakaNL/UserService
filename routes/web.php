<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'prefix' => 'api/v1',
    'namespace' => 'App\Http\Controllers\V1',
    'middleware' => ['api.throttle'],
    'limit' => 100,
    'expires' => 5
], function ($api) {
    $api->resource('users', 'UserController');
    $api->get('users/{user}/roles', 'UserRoleController@indexUser');
    $api->post('users/{user}/roles', 'UserRoleController@storeUser');
    $api->delete('users/{user}/roles/{role}', 'UserRoleController@destroyUser');

    $api->resource('roles', 'RoleController');  
    $api->get('roles/{role}/users', 'UserRoleController@indexRole');
    $api->post('roles/{role}/users', 'UserRoleController@storeRole');
    $api->delete('roles/{role}/users/{user}', 'UserRoleController@destroyRole');

    $api->post('users/login', 'UserController@login');
});


$router->get('/', function () use ($router) {
    return 'UserService.Verbeek';
});
