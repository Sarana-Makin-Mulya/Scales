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

Route::group(['prefix' => 'regional'], function () {

    /**
     * Route For AJAX request
     */
    Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function () {
        Route::get('/provinces-by-name', 'AjaxGetProvinceController@getProvinces')
            ->name('regional.ajax.get.province');
        Route::get('/regencies-by-province', 'AjaxGetRegencyController@getRegenciesByProvince')
            ->name('regional.ajax.get.regencies.by.province');
        Route::get('/district-by-regencies', 'AjaxGetDistrictController@getDistrictsByRegency')
            ->name('regional.ajax.get.district.by.regencies');
        Route::get('/villages-by-district', 'AjaxGetVillageController@getVillagesByDistrict')
            ->name('regional.ajax.get.villages.by.district');
    });
});
