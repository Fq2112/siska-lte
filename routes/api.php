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

    $router->get('vacancies/search', [
        'uses' => 'APIController@getSearchResult',
        'as' => 'get.search.vacancy'
    ]);

    $router->group(['prefix' => 'SISKA', 'middleware' => 'partner'], function ($router) {

        $router->group(['prefix' => 'seekers'], function ($router) {
            $router->post('create', 'APIController@createSeekers');
            $router->post('{provider}', 'APIController@seekersSocialite');
            $router->put('update', 'APIController@updateSeekers');
            $router->delete('delete', 'APIController@deleteSeekers');
        });

        $router->group(['prefix' => 'vacancies'], function ($router) {
            $router->post('create', 'APIController@createVacancies');
            $router->put('update', 'APIController@updateVacancies');
            $router->delete('delete', 'APIController@deleteVacancies');
        });

    });

    /**
     * Mohon tidak melakukan perubahan apapun pada
     * routing berikut! Terimakasih :)
     */
    $router->get('credentials', [
        'uses' => 'APIController@getCredentials',
        'as' => 'get.credentials'
    ]);

});
