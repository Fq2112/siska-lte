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

$router->group(['prefix' => 'api/vacancies', 'namespace' => 'Api'], function ($router) {

    $router->get('search', [
        'uses' => 'VacancyController@getSearchResult',
        'as' => 'get.search.vacancy'
    ]);

});
