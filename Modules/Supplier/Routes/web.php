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
use Illuminate\Support\Facades\Route;

Route::prefix('supplier')->group(function () {

    // Supplier Route Group
    Route::get('/', 'Supplier\SupplierController@index')
        ->name('supplier.index');
    Route::post('/store', 'Supplier\SupplierController@store')
        ->name('supplier.store');
    Route::put('/update/{id}', 'Supplier\SupplierController@update')
        ->name('supplier.update');
    Route::put('/update-status/{id}', 'Supplier\SupplierController@updateStatus')
        ->name('supplier.update.status');

    Route::group(['prefix' => 'api/v1'], function () {
        Route::get('supplier', 'Supplier\AjaxGetSupplier')
            ->name('ajax.get.supplier');
        //Detail
        Route::get('/supplier-detail/{id}', 'Supplier\AjaxGetDetailSupplier')
            ->name('ajax.get.supplier.detail');
        //Options
        Route::get('supplier-options', 'Supplier\AjaxGetSupplierOptions')
            ->name('ajax.get.supplier.options');
        //Options
        Route::get('get-supplier-info', 'Supplier\SupplierController@getSupplierInfo')
            ->name('ajax.get.supplier.info');
        // Destroy
        Route::delete('destroy/{id}', 'Supplier\AjaxDestroySupplier')
        ->name('ajax.supplier.destroy');
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/', 'SupplierCategory\SupplierCategoryController@index')
            ->name('supplier.category.index');
        Route::post('/store', 'SupplierCategory\SupplierCategoryController@store')
            ->name('supplier.category.store');
        Route::put('/update/{code}', 'SupplierCategory\SupplierCategoryController@update')
            ->name('supplier.category.update');
        Route::put('/update-status/{code}', 'SupplierCategory\SupplierCategoryController@updateStatus')
            ->name('supplier.category.update.status');


        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('supplier-category', 'SupplierCategory\AjaxGetSupplierCategory')
                ->name('ajax.get.supplier.category');
            Route::delete('destroy/{code}', 'SupplierCategory\AjaxDestroySupplierCategory')
                ->name('ajax.destroy.supplier.category');

            // Check Unique
            Route::get('/code/exist', 'SupplierCategory\AjaxCheckPropertyExistController@isUniqueCode')
                ->name('ajax.stock.check.supplier.category.code.exist');
            Route::get('/name/exist', 'SupplierCategory\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.stock.check.supplier.category.name.exist');
        });
    });
});


