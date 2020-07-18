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
        Route::get('junk-item-request-options', 'AjaxGetJunkItemRequestOptions')
            ->name('ajax.po.get.junk.item.request.options');
        Route::get('junk-item-request-detail-options', 'AjaxGetJunkItemRequestDetailOptions')
            ->name('ajax.po.get.junk.item.request.detail.options');
        Route::get('purchase-order-options', 'AjaxGetPurchaseOrderOptions')
            ->name('ajax.po.get.purchase.order.options');
        Route::get('purchase-order-item-options', 'AjaxGetPurchaseOrderItemOptions')
            ->name('ajax.po.get.purchase.order.item.options');
    });
});
