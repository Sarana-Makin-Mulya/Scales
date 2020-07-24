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

Route::group(['prefix' => 'stock', 'middleware' => 'auth'], function () {

    Route::get('/', 'Stock\StockController@index')
        ->name('stock.goods.main');

    Route::get('/quarantine', 'Stock\StockController@Quarantine')
        ->name('stock.goods.index.quarantine');

    Route::get('/dead', 'Stock\StockController@DeadStock')
        ->name('stock.goods.index.dead');

    Route::get('/buffer', 'Stock\StockController@BufferStock')
        ->name('stock.goods.index.buffer');


    Route::group(['prefix' => 'quarantine'], function () {
        Route::post('/store', 'StockQuarantine\StockQuarantineController@store')
            ->name('stock.quarantine.store');
        Route::put('/update/{id}', 'StockQuarantine\StockQuarantineController@update')
            ->name('stock.quarantine.update');
        Route::post('/return-to-stock', 'StockQuarantine\StockQuarantineController@returnToStock')
            ->name('stock.quarantine.return.to.stock');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('/quarantine', 'StockQuarantine\AjaxGetStockQuarantine')
                ->name('ajax.stock.get.quarantine');

            Route::get('/filter-dead-stock', 'StockQuarantine\AjaxGetFilterDeadStock')
                ->name('ajax.stock.get.filter.dead.stock');

            Route::get('/check-quarantine', 'StockQuarantine\AjaxGetCheckStockQuarantine')
                ->name('ajax.stock.get.quarantine.check');
        });
    });

    Route::group(['prefix' => 'goods'], function () {

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('/index', 'Stock\AjaxGetStockIndex')
                ->name('ajax.stock.goods.index');
            Route::get('/history', 'Stock\AjaxGetStockHistory')
                ->name('ajax.stock.goods.history');
            Route::get('/borrowed', 'Stock\AjaxGetStockBorrowed')
                ->name('ajax.stock.goods.borrowed');
            Route::get('/buffer', 'Stock\AjaxGetStockBuffer')
                ->name('ajax.stock.goods.buffer');

            Route::get('item-stock-value', 'Stock\AjaxGetItemStockValue')
                ->name('ajax.stock.item.value');

            Route::get('/history-item', 'Stock\AjaxGetDetailStockHistory')
                ->name('ajax.stock.goods.history.item');
            Route::get('/stock-last-item', 'Stock\AjaxGetDetailStockLast')
                ->name('ajax.stock.goods.history.item.last.stock');
            Route::get('/history-borrow-item', 'Stock\AjaxGetDetailStockBorrowedHistory')
                ->name('ajax.stock.goods.history.item.borrow');
        });
    });

    Route::group(['prefix' => 'closing'], function () {
        Route::get('/', 'StockClosing\StockClosingController@index')
            ->name('stock.closing.index');
        Route::post('/store', 'StockClosing\StockClosingController@store')
            ->name('stock.closing.store');
        Route::put('/update/{id}', 'StockClosing\StockClosingController@update')
            ->name('stock.closing.update');
        Route::put('/cancel/{id}', 'StockClosing\StockClosingController@cancel')
            ->name('stock.closing.cancel');
        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('/stock-closing', 'StockClosing\AjaxGetStockClosing')
                ->name('ajax.stock.get.stock.closing');
            Route::get('/stock-closing-status', 'StockClosing\AjaxGetStockClosingStatus')
                ->name('ajax.stock.get.stock.closing.status');
        });
    });

    Route::group(['prefix' => 'adjustment'], function () {
        Route::get('/', 'StockAdjustment\StockAdjustmentController@index')
            ->name('stock.adjustment.index');
        Route::get('/approvals', 'StockAdjustment\StockAdjustmentController@approvals')
            ->name('stock.adjustment.approvals');
        Route::get('/approvals-per-item', 'StockAdjustment\StockAdjustmentController@approvalsPerItem')
            ->name('stock.adjustment.approvals.per.item');
        Route::put('/update-status/{id}', 'StockAdjustment\StockAdjustmentController@updateStatus')
            ->name('stock.adjustment.update.status');
        Route::post('/store', 'StockAdjustment\StockAdjustmentController@store')
            ->name('stock.adjustment.store');
        Route::post('/store-dead-stock', 'StockAdjustment\StockAdjustmentController@storeDeadStock')
            ->name('stock.adjustment.store.dead.stock');
        Route::put('/update/{id}', 'StockAdjustment\StockAdjustmentController@update')
            ->name('stock.adjustment.update');
        Route::put('/report/{id}', 'StockAdjustment\StockAdjustmentController@report')
            ->name('stock.adjustment.report');
        Route::put('/approvals/{id}', 'StockAdjustment\StockAdjustmentController@approvalsUpdate')
            ->name('stock.adjustment.approvals.update');
        Route::put('/approvals-per-item/{id}', 'StockAdjustment\StockAdjustmentController@approvalsPerItemUpdate')
            ->name('stock.adjustment.approvals.per.item.update');
        Route::post('/approvals-multi-per-item', 'StockAdjustment\StockAdjustmentController@approvalsMultiPerItemUpdate')
            ->name('stock.adjustment.approvals.multi.per.item.update');

        Route::get('/export-pdf-preivew', 'StockAdjustment\StockAdjustmentController@exportPdf')
            ->name('stock.adjustment.export.pdf');

        Route::group(['prefix' => 'api/v1'], function () {
            // Per Code
            Route::get('/stock-adjustment', 'StockAdjustment\AjaxGetStockAdjustment')
                ->name('ajax.stock.get.adjustment');
            Route::get('/stock-adjustment-excel', 'StockAdjustment\AjaxGetStockAdjustmentExcel')
                ->name('ajax.stock.get.adjustment.excel');
            Route::get('/stock-adjustment-pdf', 'StockAdjustment\AjaxGetStockAdjustmentPdf')
                ->name('ajax.stock.get.adjustment.pdf');

            // Per Item
            Route::get('/stock-adjustment-per-item', 'StockAdjustment\AjaxGetStockAdjustmentPerItem')
                ->name('ajax.stock.get.adjustment.per.item');
            // Adjustment Options
            Route::get('/adjustment-category', 'StockAdjustment\AjaxGetStockAdjustmentCategory')
                ->name('ajax.stock.get.adjustment.category');
            // Detail
            Route::get('detail/{id}', 'StockAdjustment\AjaxGetDetailStockAdjustment')
                ->name('ajax.stock.detail.adjustment');
            // Edit
            Route::get('edit/{id}', 'StockAdjustment\AjaxEditStockAdjustment')
                ->name('ajax.stock.edit.adjustment');
            // Destroy
            Route::delete('destroy/{id}', 'StockAdjustment\AjaxDestroyStockAdjustment')
                ->name('ajax.stock.destroy.adjustment');
            // Total Pending
            Route::get('approvals-pending', 'StockAdjustment\AjaxGetRowStockAdjustmentApprovalsPending')
                ->name('ajax.stock.approvals.pending.adjustment');
            // Total Pending per item
            Route::get('approvals-pending-per-item', 'StockAdjustment\AjaxGetRowStockAdjustmentApprovalsPendingPerItem')
                ->name('ajax.stock.approvals.pending.adjustment.per.item');
        });
    });

    Route::group(['prefix' => 'opname'], function () {
        Route::get('/', 'StockOpname\StockOpnameController@index')
            ->name('stock.opname.main');

        Route::get('/daily', 'StockOpname\StockOpnameController@daily')
            ->name('stock.opname.index.daily');

        Route::get('/period', 'StockOpname\StockOpnameController@period')
            ->name('stock.opname.index.period');

        Route::get('/stock-opname-daily', 'StockOpname\StockOpnameController@generateStockOpnameDaily')
            ->name('stock.opname.generate.daily');
        Route::post('/store-stock-opname-daily', 'StockOpname\StockOpnameController@storeStockOpnameDaily')
            ->name('stock.opname.store.daily');

        Route::post('/store', 'StockOpname\StockOpnameController@store')
            ->name('stock.opname.store');
        Route::put('/update/{id}', 'StockOpname\StockOpnameController@update')
            ->name('stock.opname.update');

        Route::post('/store-adjustment', 'StockOpname\StockOpnameController@storeAdjustment')
            ->name('stock.opname.store.adjustment');
        Route::put('/update-adjustment/{id}', 'StockOpname\StockOpnameController@updateAdjustment')
            ->name('stock.opname.update.adjustment');

        Route::post('/import-excel', 'StockOpname\StockOpnameController@importExcel')
            ->name('stock.opname.import.excel');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('/stock-opname', 'StockOpname\AjaxGetStockOpname')
                ->name('ajax.stock.get.stock.opname');
            Route::get('/stock-opname-export-excel', 'StockOpname\AjaxGetItemStockOpnameExcel')
                ->name('ajax.stock.get.stock.opname.export.excel');
            Route::get('/stock-opname-daily-export-excel', 'StockOpname\AjaxGetItemStockOpnameDailyExcel')
                ->name('ajax.stock.get.stock.opname.daily.export.excel');

            // Destroy
            Route::delete('destroy/{id}', 'StockOpname\AjaxDestroyStockOpname')
              ->name('ajax.stock.destroy.stock.opname');
        });
    });

    Route::get('import', 'Import\ImportItem')
            ->name('stock.import');

    //Item Route Group

    Route::group(['prefix' => 'item'], function () {
        Route::get('/', 'Item\ItemController@index')
            ->name('stock.item.index');
        Route::post('/store', 'Item\ItemController@store')
            ->name('stock.item.store');
        Route::put('/update/{id}', 'Item\ItemController@update')
            ->name('stock.item.update');
        Route::put('/update-status/{id}', 'Item\ItemController@updateStatus')
            ->name('stock.item.update.status');
        Route::put('/update-status-stock/{id}', 'Item\ItemController@updateStatusStock')
            ->name('stock.item.update.status.stock');
        Route::get('/export-pdf', 'Item\ItemController@exportPdf')
            ->name('stock.item.export.pdf');
        Route::post('/update-all-typo-item', 'Item\ItemController@updateTypoItem')
            ->name('stock.item.all.typo.item');
        Route::post('/import-item', 'Item\ItemController@importItem')
            ->name('stock.item.import');
        Route::post('/import-item-stock', 'Item\ItemController@importItemStock')
            ->name('stock.item.stock.import');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('item', 'Item\AjaxGetItem')
                ->name('ajax.stock.get.item');
            Route::get('item-excel', 'Item\AjaxGetItemExcel')
                ->name('ajax.stock.get.item.excel');

            Route::get('detail/{id}', 'Item\AjaxGetDetailItem')
                ->name('ajax.stock.get.item.detail');
            Route::get('detail-first-stock-old-app/{id}', 'Item\AjaxGetDetailItemStockOldApp')
                ->name('ajax.stock.get.item.stock.old.app.detail');
            Route::get('item-type-options', 'Item\AjaxGetItemTypeOption')
                ->name('ajax.stock.get.item.type.options');
            Route::get('item-size-options', 'Item\AjaxGetItemSizeOption')
                ->name('ajax.stock.get.item.size.options');
            Route::get('item-color-options', 'Item\AjaxGetItemColorOption')
                ->name('ajax.stock.get.item.color.options');

            Route::get('unit-name', 'Item\AjaxGetUnitName')
                ->name('ajax.stock.get.unit.name');
            Route::get('item-options', 'Item\AjaxGetItemOptions')
                ->name('ajax.stock.get.item.options');
            Route::get('item-borrow-options', 'Item\AjaxGetItemBorrowOptions')
                ->name('ajax.stock.get.item.borrow.options');
            Route::get('find-item', 'Item\AjaxGetFindItem')
                ->name('ajax.stock.get.find.item');
            Route::get('find-item-info', 'Item\AjaxGetFindItemInformation')
                ->name('ajax.stock.get.find.item.info');
            Route::get('find-item-borrows', 'Item\AjaxGetFindItemBorrow')
                ->name('ajax.stock.get.find.item.borrow');

            // Destroy
            Route::delete('destroy/{id}', 'Item\AjaxDestroyItem')
            ->name('ajax.stock.item.destroy.item');
        });

        // Item Brand Route Group
        Route::group(['prefix' => 'brand'], function () {
            Route::get('/', 'Brand\BrandController@index')
                ->name('stock.item.brand.index');
            Route::post('/store', 'Brand\BrandController@store')
                ->name('stock.item.brand.store');
            Route::put('/update/{id}', 'Brand\BrandController@update')
                ->name('stock.item.brand.update');
            Route::put('/update-status/{id}', 'Brand\BrandController@updateStatus')
                ->name('stock.item.brand.update.status');
            Route::get('/export-pdf', 'Brand\BrandController@exportPdf')
                ->name('stock.item.brand.export.pdf');

            Route::group(['prefix' => 'api/v1'], function () {
                Route::get('list', 'Brand\AjaxGetBrand')
                    ->name('ajax.stock.item.get.brand');
                // Options
                Route::get('brand-options', 'Brand\AjaxGetBrandOptions')
                    ->name('ajax.stock.get.brand.options');
                // Destroy brand
                Route::delete('destroy/{id}', 'Brand\AjaxDestroyBrand')
                    ->name('ajax.stock.item.destroy.brand');
                // Check Unique
                Route::get('/unit/name/exist', 'Brand\AjaxBrandCheckNameExist')
                    ->name('ajax.stock.item.check.brand.name.exist');
            });
        });

        //Item Category Route Group
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', 'Category\CategoryController@index')
                ->name('stock.item.category.index');
            Route::post('/store', 'Category\CategoryController@store')
                ->name('stock.item.category.store');
            Route::put('/update/{id}', 'Category\CategoryController@update')
                ->name('stock.item.category.update');
            Route::put('/update-status/{id}', 'Category\CategoryController@updateStatus')
                ->name('stock.item.category.update.status');
            Route::get('/export-pdf', 'Category\CategoryController@exportPdf')
                ->name('stock.item.category.export.pdf');

            Route::group(['prefix' => 'api/v1'], function () {
                Route::get('category', 'Category\AjaxGetCategory')
                    ->name('ajax.stock.get.item.category');
                // Options
                Route::get('category-options', 'Category\AjaxGetCategoryOptions')
                    ->name('ajax.stock.get.category.options');
                // Check Unique
                Route::get('/category/code/exist', 'Category\AjaxCheckPropertyExistController@isUniqueCode')
                    ->name('ajax.stock.check.item.category.code.exist');
                Route::get('/category/name/exist', 'Category\AjaxCheckPropertyExistController@isUniqueName')
                    ->name('ajax.stock.check.item.category.name.exist');
                // Destroy
                Route::delete('destroy/{id}', 'Category\AjaxDestroyCategory')
                    ->name('ajax.stock.item.destroy.category');
            });
        });

        //Stock Transaction Category Route Group
        Route::group(['prefix' => 'transactioncategory'], function () {
            Route::get('/', 'TransactionCategory\StockTransactionCategoryController@index')
                ->name('stock.transaction.category.index');
            Route::post('/store', 'TransactionCategory\StockTransactionCategoryController@store')
                ->name('stock.transaction.category.store');
            Route::put('/update/{id}', 'TransactionCategory\StockTransactionCategoryController@update')
                ->name('stock.transaction.category.update');
            Route::put('/update-status/{id}', 'TransactionCategory\StockTransactionCategoryController@updateStatus')
                ->name('stock.transaction.category.update.status');

            Route::group(['prefix' => 'api/v1'], function () {
                Route::get('transactioncategory', 'TransactionCategory\AjaxGetStockTransactionCategory')
                    ->name('ajax.stock.get.transaction.category');

            // Check Unique
            Route::get('/transactioncategory/name/exist', 'TransactionCategory\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.stock.check.transaction.category.name.exist');
            });

            // Destroy
            Route::delete('destroy/{id}', 'TransactionCategory\AjaxDestroyStockTransactionCategory')
                ->name('ajax.stock.destroy.transaction.category');
        });
    });
});
