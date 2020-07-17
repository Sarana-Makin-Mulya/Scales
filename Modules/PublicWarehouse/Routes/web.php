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

Route::group(['prefix' => 'wh', 'middleware' => 'auth'], function () {

    //Weighing
    Route::group(['prefix' => 'weighing'], function () {
        Route::get('/', 'Weighing\WeighingController@index')
            ->name('wh.weighing.index');
        Route::post('/store', 'Weighing\WeighingController@store')
            ->name('wh.weighing.store');
        Route::put('/update/{id}', 'Weighing\WeighingController@update')
            ->name('wh.weighing.update');
        Route::post('/download-weighing', 'Weighing\WeighingController@Download')
            ->name('po.weighing.download');


        Route::get('/category', 'Weighing\WeighingCategoryController@index')
            ->name('wh.weighing.category.index');
        Route::post('/store-category', 'Weighing\WeighingCategoryController@store')
            ->name('wh.weighing.category.store');
        Route::put('/update-category/{id}', 'Weighing\WeighingCategoryController@update')
            ->name('wh.weighing.category.update');

        Route::group(['prefix' => 'api/v1'], function () {
            // Get Data
            Route::get('weighing', 'Weighing\AjaxGetWeighing')
                ->name('ajax.wh.get.weighing');
            Route::get('weighing-category', 'Weighing\AjaxGetWeighingCategory')
                ->name('ajax.wh.get.weighing.category');
            // Detail
            Route::get('detail-weighing/{id}', 'Weighing\AjaxGetDetailWeighing')
                ->name('ajax.wh.detail.weighing');
            // Options
            Route::get('weighing-category-options', 'Weighing\AjaxGetWeighingCategoryOptions')
                ->name('ajax.wh.get.weighing.category.options');
            // Check Validations
            Route::get('/weighing-category/name/exist', 'Weighing\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.stock.check.weighing.category.name.exist');
            // Delete
            Route::delete('destroy/{id}', 'Weighing\AjaxDestroyWeighing')
                 ->name('ajax.wh.destroy.weighing');
            Route::delete('destroy-category/{id}', 'Weighing\AjaxDestroyWeighingCategory')
                 ->name('ajax.wh.destroy.weighing.category');
        });
    });
});
