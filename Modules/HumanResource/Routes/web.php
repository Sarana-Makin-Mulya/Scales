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


Route::group(['prefix' => 'hr'], function () {
     // Employee
     Route::group(['prefix' => 'employee'], function () {
        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('find-employee', 'Employee\FindHREmployee')
                ->name('hr.ajax.find.employee');
            Route::get('employee-options', 'Employee\AjaxGetHREmployeeOptions')
                ->name('hr.ajax.employee.options');

            Route::get('employee-detail', 'Employee\AjaxGetHREmployeeDetail')
                ->name('ajax.hr.get.employee.detail');
        });
    });

    // Department
     Route::group(['prefix' => 'department'], function () {
        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('department-options', 'Department\AjaxGetHRDepartmentOptions')
                ->name('hr.ajax.department.options');
         });
    });

    // Autorization
    Route::group(['prefix' => 'autorization'], function () {
        Route::get('/', 'Autorization\HREmployeeAutorizationController@index')
            ->name('hr.autorization.index');
        Route::post('/store', 'Autorization\HREmployeeAutorizationController@store')
            ->name('hr.autorization.store');
        Route::put('/update/{id}', 'Autorization\HREmployeeAutorizationController@update')
            ->name('hr.autorization.update');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('employee', 'Autorization\AjaxGetHREmployeeAutorization')
                ->name('ajax.hr.get.autorization');
            // Detail
            Route::get('autorization-detail/{id}', 'Autorization\AjaxGetHREmployeeAutorizationDetail')
                ->name('ajax.hr.get.autorization.detail');
            // Destroy
            Route::delete('destroy/{id}', 'Autorization\AjaxDestroyHREmployeeAutorization')
                ->name('ajax.hr.destroy.autorization');
            // Check Unique
            Route::get('/autorization/name/exist', 'Autorization\AjaxCheckPropertyExist@isUniqueName')
                ->name('ajax.hr.check.autorization.name.exist');
            Route::get('/autorization/code/exist', 'Autorization\AjaxCheckPropertyExist@isUniqueCode')
                ->name('ajax.hr.check.autorization.code.exist');
        });
    });
});
