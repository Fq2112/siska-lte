<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@postReset')->name('password.reset');

Auth::routes();

Route::group(['namespace' => 'Auth', 'prefix' => 'account'], function () {

    Route::get('/', [
        'uses' => 'LoginController@showLoginForm',
        'as' => 'show.login.form'
    ]);

    Route::post('login', [
        'uses' => 'LoginController@login',
        'as' => 'login'
    ]);

    Route::post('logout', [
        'uses' => 'LoginController@logout',
        'as' => 'logout'
    ]);

    Route::get('activate', [
        'uses' => 'ActivationController@activate',
        'as' => 'activate'
    ]);

    Route::get('login/{provider}', [
        'uses' => 'SocialAuthController@redirectToProvider',
        'as' => 'redirect'
    ]);

    Route::get('login/{provider}/callback', [
        'uses' => 'SocialAuthController@handleProviderCallback',
        'as' => 'callback'
    ]);

});

Route::group(['namespace' => 'Admins', 'prefix' => 'admin', 'middleware' => 'admin'], function () {

    Route::get('/', [
        'uses' => 'AdminController@index',
        'as' => 'home-admin'
    ]);

    Route::put('profile/update', [
        'uses' => 'AdminController@updateProfile',
        'as' => 'admin.update.profile'
    ]);

    Route::put('account/update', [
        'uses' => 'AdminController@updateAccount',
        'as' => 'admin.update.account'
    ]);

    Route::group(['prefix' => 'agencies'], function () {

        Route::get('/', [
            'uses' => 'AgencyController@showAgenciesTable',
            'as' => 'table.agencies'
        ]);

        Route::post('create', [
            'uses' => 'AgencyController@createAgencies',
            'as' => 'create.agencies'
        ]);

        Route::get('edit/{id}', [
            'uses' => 'AgencyController@editAgencies',
            'as' => 'edit.agencies'
        ]);

        Route::put('{id}/update', [
            'uses' => 'AgencyController@updateAgencies',
            'as' => 'update.agencies'
        ]);

        Route::get('{id}/delete', [
            'uses' => 'AgencyController@deleteAgencies',
            'as' => 'delete.agencies'
        ]);

    });

    Route::group(['prefix' => 'vacancies'], function () {

        Route::get('/', [
            'uses' => 'AgencyController@showVacanciesTable',
            'as' => 'table.vacancies'
        ]);

        Route::post('create', [
            'uses' => 'AgencyController@createVacancies',
            'as' => 'create.vacancies'
        ]);

        Route::get('edit/{id}', [
            'uses' => 'AgencyController@editVacancies',
            'as' => 'edit.vacancies'
        ]);

        Route::put('update/{id}', [
            'uses' => 'AgencyController@updateVacancies',
            'as' => 'update.vacancies'
        ]);

        Route::get('{id}/delete', [
            'uses' => 'AgencyController@deleteVacancies',
            'as' => 'delete.vacancies'
        ]);

    });

    Route::group(['prefix' => 'tables'], function () {

        Route::group(['namespace' => 'DataMaster'], function () {

            Route::group(['prefix' => 'accounts'], function () {

                Route::group(['prefix' => 'admins'], function () {

                    Route::get('/', [
                        'uses' => 'AccountsController@showAdminsTable',
                        'as' => 'table.admins'
                    ]);

                    Route::post('create', [
                        'uses' => 'AccountsController@createAdmins',
                        'as' => 'create.admins'
                    ]);

                    Route::put('{id}/update/profile', [
                        'uses' => 'AccountsController@updateProfileAdmins',
                        'as' => 'update.profile.admins'
                    ]);

                    Route::put('{id}/update/account', [
                        'uses' => 'AccountsController@updateAccountAdmins',
                        'as' => 'update.account.admins'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'AccountsController@deleteAdmins',
                        'as' => 'delete.admins'
                    ]);

                });

                Route::group(['prefix' => 'users'], function () {

                    Route::get('/', [
                        'uses' => 'AccountsController@showUsersTable',
                        'as' => 'table.users'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'AccountsController@deleteUsers',
                        'as' => 'delete.users'
                    ]);

                });

            });

            Route::group(['prefix' => 'requirements'], function () {

                Route::group(['prefix' => 'degrees'], function () {

                    Route::get('/', [
                        'uses' => 'RequirementsController@showDegreesTable',
                        'as' => 'table.degrees'
                    ]);

                    Route::post('create', [
                        'uses' => 'RequirementsController@createDegrees',
                        'as' => 'create.degrees'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'RequirementsController@updateDegrees',
                        'as' => 'update.degrees'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'RequirementsController@deleteDegrees',
                        'as' => 'delete.degrees'
                    ]);

                });

                Route::group(['prefix' => 'majors'], function () {

                    Route::get('/', [
                        'uses' => 'RequirementsController@showMajorsTable',
                        'as' => 'table.majors'
                    ]);

                    Route::post('create', [
                        'uses' => 'RequirementsController@createMajors',
                        'as' => 'create.majors'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'RequirementsController@updateMajors',
                        'as' => 'update.majors'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'RequirementsController@deleteMajors',
                        'as' => 'delete.majors'
                    ]);

                });

                Route::group(['prefix' => 'industries'], function () {

                    Route::get('/', [
                        'uses' => 'RequirementsController@showIndustriesTable',
                        'as' => 'table.industries'
                    ]);

                    Route::post('create', [
                        'uses' => 'RequirementsController@createIndustries',
                        'as' => 'create.industries'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'RequirementsController@updateIndustries',
                        'as' => 'update.industries'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'RequirementsController@deleteIndustries',
                        'as' => 'delete.industries'
                    ]);

                });

                Route::group(['prefix' => 'job_functions'], function () {

                    Route::get('/', [
                        'uses' => 'RequirementsController@showJobFunctionsTable',
                        'as' => 'table.JobFunctions'
                    ]);

                    Route::post('create', [
                        'uses' => 'RequirementsController@createJobFunctions',
                        'as' => 'create.JobFunctions'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'RequirementsController@updateJobFunctions',
                        'as' => 'update.JobFunctions'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'RequirementsController@deleteJobFunctions',
                        'as' => 'delete.JobFunctions'
                    ]);

                });

                Route::group(['prefix' => 'job_levels'], function () {

                    Route::get('/', [
                        'uses' => 'RequirementsController@showJobLevelsTable',
                        'as' => 'table.JobLevels'
                    ]);

                    Route::post('create', [
                        'uses' => 'RequirementsController@createJobLevels',
                        'as' => 'create.JobLevels'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'RequirementsController@updateJobLevels',
                        'as' => 'update.JobLevels'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'RequirementsController@deleteJobLevels',
                        'as' => 'delete.JobLevels'
                    ]);

                });

                Route::group(['prefix' => 'job_types'], function () {

                    Route::get('/', [
                        'uses' => 'RequirementsController@showJobTypesTable',
                        'as' => 'table.JobTypes'
                    ]);

                    Route::post('create', [
                        'uses' => 'RequirementsController@createJobTypes',
                        'as' => 'create.JobTypes'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'RequirementsController@updateJobTypes',
                        'as' => 'update.JobTypes'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'RequirementsController@deleteJobTypes',
                        'as' => 'delete.JobTypes'
                    ]);

                });

                Route::group(['prefix' => 'salaries'], function () {

                    Route::get('/', [
                        'uses' => 'RequirementsController@showSalariesTable',
                        'as' => 'table.salaries'
                    ]);

                    Route::post('create', [
                        'uses' => 'RequirementsController@createSalaries',
                        'as' => 'create.salaries'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'RequirementsController@updateSalaries',
                        'as' => 'update.salaries'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'RequirementsController@deleteSalaries',
                        'as' => 'delete.salaries'
                    ]);

                });

            });

            Route::group(['prefix' => 'web_contents'], function () {

                Route::group(['prefix' => 'nations'], function () {

                    Route::get('/', [
                        'uses' => 'WebContentsController@showNationsTable',
                        'as' => 'table.nations'
                    ]);

                    Route::post('create', [
                        'uses' => 'WebContentsController@createNations',
                        'as' => 'create.nations'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'WebContentsController@updateNations',
                        'as' => 'update.nations'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'WebContentsController@deleteNations',
                        'as' => 'delete.nations'
                    ]);

                });

                Route::group(['prefix' => 'provinces'], function () {

                    Route::get('/', [
                        'uses' => 'WebContentsController@showProvincesTable',
                        'as' => 'table.provinces'
                    ]);

                    Route::post('create', [
                        'uses' => 'WebContentsController@createProvinces',
                        'as' => 'create.provinces'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'WebContentsController@updateProvinces',
                        'as' => 'update.provinces'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'WebContentsController@deleteProvinces',
                        'as' => 'delete.provinces'
                    ]);

                });

                Route::group(['prefix' => 'cities'], function () {

                    Route::get('/', [
                        'uses' => 'WebContentsController@showCitiesTable',
                        'as' => 'table.cities'
                    ]);

                    Route::post('create', [
                        'uses' => 'WebContentsController@createCities',
                        'as' => 'create.cities'
                    ]);

                    Route::put('{id}/update', [
                        'uses' => 'WebContentsController@updateCities',
                        'as' => 'update.cities'
                    ]);

                    Route::get('{id}/delete', [
                        'uses' => 'WebContentsController@deleteCities',
                        'as' => 'delete.cities'
                    ]);

                });

            });

        });

        Route::group(['namespace' => 'DataTransaction'], function () {

            Route::group(['prefix' => 'seekers'], function () {

                Route::group(['prefix' => 'applications'], function () {

                    Route::get('/', [
                        'uses' => 'TransactionSeekerController@showApplicationsTable',
                        'as' => 'table.applications'
                    ]);

                    Route::post('pdf', [
                        'uses' => 'TransactionSeekerController@massGeneratePDF',
                        'as' => 'table.applications.massPDF'
                    ]);

                    Route::post('delete', [
                        'uses' => 'TransactionSeekerController@massDeleteApplications',
                        'as' => 'table.applications.massDelete'
                    ]);

                });

            });

        });

    });

    /**
     * Mohon tidak melakukan perubahan apapun pada
     * kedua synchronize route berikut!
     * Terimakasih :)
     */
    Route::group(['prefix' => 'synchronize'], function () {

        Route::get('/', [
            'uses' => 'AdminController@showSynchronize',
            'as' => 'show.synchronize'
        ]);

        Route::post('submit', [
            'uses' => 'AdminController@submitSynchronize',
            'as' => 'submit.synchronize'
        ]);

    });

});
