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

$router->get('states[/{id}]', 'StateController@show');
$router->get('states/{stateId}/cities', 'StateController@getCities');

$router->get('cities/{cityId}/neighborhoods', 'CityController@getNeighborhoods');

$router->get('neighborhoods/{neighborhoodId}/addresses', 'NeighborhoodController@getAddresses');

$router->get('neighborhoods/{neighborhoodId}/zipcodes', 'NeighborhoodController@getZipcodes');

$router->get('zipcodes/{zipcode}', 'ZipcodeController@findOrCreate');
