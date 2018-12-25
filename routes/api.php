<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['prefix' => 'api', 'namespace' => 'Api'], function ($router) {

    $router->get('vacancies/sync', [
        'uses' => 'APIController@syncVacancies',
        'as' => 'sync.vacancy'
    ]);

    $router->get('vacancies/search', [
        'uses' => 'APIController@getSearchResult',
        'as' => 'get.search.vacancy'
    ]);

    // please, don't make any changes to this code!!
    $router->get('credentials', [
        'uses' => 'APIController@getCredentials',
        'as' => 'get.credentials'
    ]);

});
