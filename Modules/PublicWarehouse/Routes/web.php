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

    // Weighing
    Route::group(['prefix' => 'weighing'], function () {
        Route::get('/', 'Weighing\WeighingController@index')
            ->name('wh.weighing.index');
        Route::post('/store', 'Weighing\WeighingController@store')
            ->name('wh.weighing.store');
        Route::put('/update/{id}', 'Weighing\WeighingController@update')
            ->name('wh.weighing.update');
        Route::post('/download-weighing', 'Weighing\WeighingController@Download')
            ->name('po.weighing.download');

        Route::group(['prefix' => 'api/v1'], function () {
            // Get Data
            Route::get('weighing', 'Weighing\AjaxGetWeighing')
                ->name('ajax.wh.get.weighing');
            // Detail
            Route::get('detail-weighing/{id}', 'Weighing\AjaxGetDetailWeighing')
                ->name('ajax.wh.detail.weighing');
            // Check Validations
            Route::get('/weighing-category/name/exist', 'Weighing\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.stock.check.weighing.category.name.exist');
            // Delete
            Route::delete('destroy/{id}', 'Weighing\AjaxDestroyWeighing')
                 ->name('ajax.wh.destroy.weighing');
        });
    });

    // Category
    Route::group(['prefix' => 'weighing-category'], function () {
        Route::get('/', 'WeighingCategory\WeighingCategoryController@index')
            ->name('wh.weighing.category.index');
        Route::post('/store', 'WeighingCategory\WeighingCategoryController@store')
            ->name('wh.weighing.category.store');
        Route::put('/update/{id}', 'WeighingCategory\WeighingCategoryController@update')
            ->name('wh.weighing.category.update');

        Route::group(['prefix' => 'api/v1'], function () {
            // Get Data
            Route::get('weighing-category', 'WeighingCategory\AjaxGetWeighingCategory')
                ->name('ajax.wh.get.weighing.category');
            // Options
            Route::get('weighing-category-options', 'WeighingCategory\AjaxGetWeighingCategoryOptions')
                ->name('ajax.wh.get.weighing.category.options');
            // Check Validations
            Route::get('/weighing-category/name/exist', 'WeighingCategory\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.stock.check.weighing.category.name.exist');
            // Delete
            Route::delete('destroy-category/{id}', 'WeighingCategory\AjaxDestroyWeighingCategory')
                 ->name('ajax.wh.destroy.weighing.category');
        });
    });

    //Item
    Route::group(['prefix' => 'weighing-item'], function () {
        Route::get('/', 'WeighingItem\WeighingItemController@index')
            ->name('wh.weighing.item.index');
        Route::post('/store-item', 'WeighingItem\WeighingItemController@store')
            ->name('wh.weighing.item.store');
        Route::put('/update-item/{id}', 'WeighingItem\WeighingItemController@update')
            ->name('wh.weighing.item.update');

        Route::group(['prefix' => 'api/v1'], function () {
            // Get Data
            Route::get('weighing-item', 'WeighingItem\AjaxGetWeighingItem')
                ->name('ajax.wh.get.weighing.item');
            // Options
            Route::get('weighing-item-options', 'WeighingItem\AjaxGetWeighingItemOptions')
                ->name('ajax.wh.get.weighing.item.options');
            // Check Validations
            Route::get('/weighing-item/name/exist', 'WeighingItem\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.stock.check.weighing.item.name.exist');
            // Delete
            Route::delete('destroy-item/{id}', 'WeighingItem\AjaxDestroyWeighingItem')
                 ->name('ajax.wh.destroy.weighing.item');
        });
    });
});
