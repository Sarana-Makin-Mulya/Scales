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

Route::group(['prefix' => 'po', 'middleware' => 'auth'], function () {

    Route::group(['prefix' => 'api/v1'], function () {
        Route::get('user-data', 'AjaxGetUserData')
            ->name('ajax.po.get.user.data');
        Route::get('get-supplier', 'AjaxGetSupplier')
            ->name('ajax.po.get.supplier');
        Route::get('junk-item-spk-options', 'AjaxGetJunkItemSpkOptions')
            ->name('ajax.po.get.junk.item.spk.options');
        Route::get('junk-item-spk-detail-options', 'AjaxGetJunkItemSpkDetailOptions')
            ->name('ajax.po.get.junk.item.spk.detail.options');
        Route::get('purchase-order-options', 'AjaxGetPurchaseOrderOptions')
            ->name('ajax.po.get.purchase.order.options');
        Route::get('purchase-order-item-options', 'AjaxGetPurchaseOrderItemOptions')
            ->name('ajax.po.get.purchase.order.item.options');
    });
});
