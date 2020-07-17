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

Route::get('login', 'LoginController@showLoginForm')
    ->name('login');
Route::post('login', 'LoginController@login')
    ->name('login');
Route::post('logout', 'LoginController@logout')
    ->name('logout');


Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {

    Route::get('control-level', 'User\LevelUserController@setLevel')
    ->name('control-user');
    //User Manage
    Route::group(['prefix' => 'manage'], function () {
        Route::get('/', 'User\UserController@index')
            ->name('user.manage.index');
        Route::post('/store', 'User\UserController@store')
            ->name('user.manage.store');
        Route::put('/update/{id}', 'User\UserController@update')
            ->name('user.manage.update');
        Route::put('/update-status/{id}', 'User\UserController@updateStatus')
            ->name('user.manage.update.status');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('manage', 'User\AjaxGetUser')
                ->name('ajax.user.get.manage');
            Route::get('username', 'User\AjaxCheckPropertyExistController@isUniqueUserName')
                ->name('ajax.user.get.manage.username.exist');
            Route::get('email', 'User\AjaxCheckPropertyExistController@isUniqueEmail')
                ->name('ajax.user.get.manage.email.exist');
            // Destroy
            Route::delete('destroy/{id}', 'User\AjaxDestroyUser')
            ->name('ajax.user.destroy.manage');
        });

    });

    //User Group
    Route::group(['prefix' => 'group'], function () {
        Route::get('/', 'Group\UserGroupController@index')
            ->name('user.group.index');
        Route::post('/store', 'Group\UserGroupController@store')
            ->name('user.group.store');
        Route::put('/update/{id}', 'Group\UserGroupController@update')
            ->name('user.group.update');
        Route::put('/update-status/{id}', 'Group\UserGroupController@updateStatus')
            ->name('user.group.update.status');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('group', 'Group\AjaxGetUserGroup')
                ->name('ajax.user.get.group');
            Route::get('group-name', 'Group\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.user.get.group.name.exist');
            Route::get('group-options', 'Group\AjaxGetUserGroupOptions')
                ->name('ajax.user.get.level.group.options');

            // Destroy
            Route::delete('destroy/{id}', 'Group\AjaxDestroyUserGroup')
            ->name('ajax.user.destroy.group');
        });

    });
});
