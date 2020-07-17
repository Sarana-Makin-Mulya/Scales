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

Route::group(['prefix' => 'general', 'middleware' => 'auth'], function () {
    // socket notification group
    Route::group(['prefix' => 'socket'], function () {
        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('notification-row', 'Socket\AjaxGetNotificationRow')
                ->name('ajax.general.get.socket.notification.row');
            Route::get('notification', 'Socket\AjaxGetNotification')
                ->name('ajax.general.get.socket.notification');
            Route::get('notification-popup', 'Socket\AjaxGetNotificationPopUp')
                ->name('ajax.general.get.notification.popup');
            Route::get('item-stock', 'Socket\AjaxGetItemStock')
                ->name('ajax.general.get.item.stock');
        });
    });

    // notification page froup
    Route::group(['prefix' => 'notification'], function () {
        Route::get('/', 'Notification\NotificationController@index')
            ->name('general.notification.index');

        Route::post('/update-notif', 'Notification\NotificationController@updateNotif')
            ->name('general.notification.update');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('notification', 'Notification\AjaxGetNotification')
                ->name('ajax.general.get.notification');

        });
    });

    // Dashboard Group
    Route::group(['prefix' => 'dashboard'], function () {
        Route::group(['prefix' => 'api/v1'], function () {
            Route::post('statistict', 'Dashboard\AjaxGetDashboardStatistict')
                ->name('ajax.general.get.dashboard.Statistict');
            Route::get('history-stock', 'Dashboard\AjaxGetDashboardHistoryStock')
                ->name('ajax.general.get.dashboard.history.stock');
            Route::get('history-purchase-order', 'Dashboard\AjaxGetDashboardHistoryPurchaseOrder')
                ->name('ajax.general.get.dashboard.history.purchase.order');
            Route::get('panel-notification', 'Dashboard\AjaxGetDashboardPanelNotification')
                ->name('ajax.general.get.dashboard.panel.notification');
            Route::get('panel-reminder', 'Dashboard\AjaxGetDashboardPanelReminder')
                ->name('ajax.general.get.dashboard.panel.reminder');
            Route::get('panel-kpi', 'Dashboard\AjaxGetDashboardPanelKPI')
                ->name('ajax.general.get.dashboard.panel.kpi');
        });
    });

    // Unit Route Group
    Route::group(['prefix' => 'unit'], function () {
        Route::get('/', 'Unit\UnitController@index')
            ->name('general.unit.index');
        Route::post('/store', 'Unit\UnitController@store')
            ->name('general.unit.store');
        Route::put('/update/{id}', 'Unit\UnitController@update')
            ->name('general.unit.update');
        Route::put('/update-status/{id}', 'Unit\UnitController@updateStatus')
            ->name('general.unit.update.status');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('unit', 'Unit\AjaxGetUnit')
                ->name('ajax.general.get.unit');
            // Options
            Route::get('unit-options', 'Unit\AjaxGetUnitOptions')
                ->name('ajax.general.get.unit.options');
            // Destroy Unit
            Route::delete('destroy/{id}', 'Unit\AjaxDestroyUnit')
                ->name('ajax.general.destroy.unit');
            // Check Unique
            Route::get('/unit/name/exist', 'Unit\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.general.check.unit.name.exist');
            Route::get('/unit/symbol/exist', 'Unit\AjaxCheckPropertyExistController@isUniqueSymbol')
                ->name('ajax.general.check.unit.symbol.exist');
        });
    });

    // Machnine Route Group
    Route::group(['prefix' => 'machine'], function () {
        Route::get('/', 'Machine\MachineController@index')
            ->name('general.machine.index');
        Route::post('/store', 'Machine\MachineController@store')
            ->name('general.machine.store');
        Route::put('/update/{id}', 'Machine\MachineController@update')
            ->name('general.machine.update');
        Route::put('/update-status/{id}', 'Machine\MachineController@updateStatus')
            ->name('general.machine.update.status');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('machine', 'Machine\AjaxGetMachine')
                ->name('ajax.general.get.machine');
            // Options
            Route::get('machine-options', 'Machine\AjaxGetMachineOptions')
                ->name('ajax.general.get.machine.options');
            // Destroy
            Route::delete('destroy/{id}', 'Machine\AjaxDestroyMachine')
                ->name('ajax.general.destroy.machine');
            // Check Unique
            Route::get('/machine/name/exist', 'Machine\AjaxCheckPropertyExistController@isUniqueName')
                ->name('ajax.general.check.machine.name.exist');
        });
    });

    //Currency Route Group
    Route::group(['prefix' => 'currency'], function () {
        Route::get('/', 'Currency\CurrencyController@index')
            ->name('general.currency.index');
        Route::post('/store', 'Currency\CurrencyController@store')
            ->name('general.currency.store');
        Route::put('/update/{id}', 'Currency\CurrencyController@update')
            ->name('general.currency.update');
        Route::put('/update-status/{id}', 'Currency\CurrencyController@updateStatus')
            ->name('general.currency.update.status');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('currency', 'Currency\AjaxGetCurrency')
                ->name('ajax.general.get.currency');
            Route::get('get-currency', 'Currency\CurrencyController@getCurrency')
                ->name('ajax.general.get.currency.detail');
            Route::get('currency-options', 'Currency\AjaxGetCurrencyOptions')
                ->name('ajax.general.get.currency.options');
            // Destroy
            Route::delete('destroy/{id}', 'Currency\AjaxDestroyCurrency')
                ->name('ajax.general.destroy.currency');
            // Check Unique
            Route::get('/currency/name/exist', 'Currency\AjaxCheckPropertyExist@isUniqueName')
                ->name('ajax.general.check.currency.name.exist');
            Route::get('/currency/symbol/exist', 'Currency\AjaxCheckPropertyExist@isUniqueSymbol')
                ->name('ajax.general.check.currency.symbol.exist');
        });
    });

    //Menu Route Group
    Route::group(['prefix' => 'menu'], function () {
        Route::get('/', 'Menu\GeneralMenuController@index')
            ->name('general.menu.index');
        Route::post('/store', 'Menu\GeneralMenuController@store')
            ->name('general.menu.store');
        Route::put('/store-menu-group/{id}', 'Menu\GeneralMenuController@storeMenuUser')
            ->name('general.menu.store.menu.user');
        Route::put('/store-menu-user/{id}', 'Menu\GeneralMenuController@storeMenuGroup')
            ->name('general.menu.store.menu.group');
        Route::put('/update/{id}', 'Menu\GeneralMenuController@update')
            ->name('general.menu.update');
        Route::put('/update-status/{id}', 'Menu\GeneralMenuController@updateStatus')
            ->name('general.menu.update.status');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('menu', 'Menu\AjaxGetGeneralMenu')
                ->name('ajax.general.get.menu');

            Route::get('menu-options', 'Menu\AjaxGetMenuOptions')
                ->name('ajax.general.get.menu.options');

            Route::get('manage-menu-options', 'Menu\AjaxGetManageMenuOptions')
                ->name('ajax.general.get.manage.menu.options');
            Route::get('manage-single-menu-options', 'Menu\AjaxGetManageSingleMenuOptions')
                ->name('ajax.general.get.manage.single.menu.options');
            Route::get('manage-all-menu-options', 'Menu\AjaxGetAllManageMenuOptions')
                ->name('ajax.general.get.manage.all.menu.options');

            Route::get('manage-user-menu-options', 'Menu\AjaxGetManageUserMenuOptions')
                ->name('ajax.general.get.manage.user.menu.options');
            Route::get('manage-single-user-menu-options', 'Menu\AjaxGetManageSingleUserMenuOptions')
                ->name('ajax.general.get.manage.single.user.menu.options');
            Route::get('manage-all-user-menu-options', 'Menu\AjaxGetAllManageUserMenuOptions')
                ->name('ajax.general.get.manage.all.user.menu.options');

            // Destroy
            Route::delete('destroy/{id}', 'Menu\AjaxDestroyGeneralMenu')
                ->name('ajax.general.destroy.menu');
            // Check Unique
            Route::get('/menu/name/exist', 'Menu\AjaxCheckPropertyExist@isUniqueName')
                ->name('ajax.general.check.menu.name.exist');
            Route::get('/menu/url/exist', 'Menu\AjaxCheckPropertyExist@isUniqueUrl')
                ->name('ajax.general.check.menu.url.exist');
        });
    });

    // KPI
    Route::group(['prefix' => 'kpi'], function () {
        Route::get('/', 'KPI\KPIController@index')
            ->name('general.kpi.main');

        // Route::post('/store', 'KPI\KPIController@store')
        //     ->name('general.kpi.store');
        // Route::put('/update/{id}', 'KPI\KPIController@update')
        //     ->name('general.kpi.update');

        // KPI Employee
        Route::post('/store-employee', 'KPI\Employee\KPIEmployeeController@store')
            ->name('general.kpi.employee.store');
        Route::put('/update-employee/{id}', 'KPI\Employee\KPIEmployeeController@update')
            ->name('general.kpi.employee.update');


        Route::group(['prefix' => 'api/v1'], function () {
            // KPI Function
            Route::get('/detail/test', 'KPI\Detail\AjaxGetKPITest')
                ->name('ajax.general.get.kpi.detail.test');
            Route::get('/formula-name/{id}', 'KPI\Detail\AjaxGetKPIFormulaName')
                ->name('ajax.general.get.kpi.detail.formula.name');

            // KPI Formula
            Route::get('/formula', 'KPI\AjaxGetKPIFormula')
                ->name('ajax.general.get.kpi.formula');
            Route::get('/formula-excel', 'KPI\AjaxGetKPIFormulaExcel')
                ->name('ajax.general.get.kpi.formula.excel');

            // KPI Options
            Route::get('/formula-options', 'KPI\AjaxGetKPIFormulaOptions')
                ->name('ajax.general.get.kpi.formula.options');
            Route::get('/category-options', 'KPI\AjaxGetKPICategoryOptions')
                ->name('ajax.general.get.kpi.category.options');

            // KPI Group
            // Route::get('/group', 'KPI\AjaxGetKPIGroup')
            //     ->name('ajax.general.get.kpi.group');

            // KPI Detail Transaction
            Route::get('/detail/goods-request', 'KPI\Detail\AjaxGetKPIWarehouseGoodsRequest')
                ->name('ajax.general.get.kpi.detail.goods.request');
            Route::get('/detail/moq', 'KPI\Detail\AjaxGetKPIWarehouseMOQ')
                ->name('ajax.general.get.kpi.detail.moq');
            Route::get('/detail/goods-borrow', 'KPI\Detail\AjaxGetKPIWarehouseGoodsBorrow')
                ->name('ajax.general.get.kpi.detail.goods.borrow');

            // KPI Old
            // Route::get('/', 'KPI\AjaxGetKPI')
            //     ->name('ajax.general.get.kpi');
            // Route::get('/detail', 'KPI\AjaxGetKPIDetail')
            //     ->name('ajax.general.get.kpi.detail');
            // Route::delete('destroy/{id}', 'KPI\AjaxDestroyKPIGroup')
            //     ->name('ajax.general.destroy.kpi.group');

            // KPI Employee
            Route::get('/kpi-employee', 'KPI\Employee\AjaxGetKPIEmployee')
                ->name('ajax.general.get.kpi.employee');
            Route::get('/kpi-employee-excel', 'KPI\Employee\AjaxGetKPIEmployeeExcel')
                ->name('ajax.general.get.kpi.employee.excel');
            Route::get('/detail-kpi-employee', 'KPI\Employee\AjaxGetKPIEmployeeDetail')
                ->name('ajax.general.get.kpi.employee.detail');
            Route::delete('destroy-kpi-employee/{id}', 'KPI\Employee\AjaxDestroyKPIEmployee')
                ->name('ajax.general.destroy.kpi.employee');

            // KPI Employee
            Route::get('/kpi-employee-result', 'KPI\Employee\AjaxGetKPIEmployeeResult')
                ->name('ajax.general.get.kpi.employee.result');
            Route::get('/kpi-employee-result-excel', 'KPI\Employee\AjaxGetKPIEmployeeResultExcel')
                ->name('ajax.general.get.kpi.employee.result.excel');
            Route::get('/detail-kpi-employee-result', 'KPI\Employee\AjaxGetKPIEmployeeResultDetail')
                ->name('ajax.general.get.kpi.employee.result.detail');
        });
    });

    Route::group(['prefix' => 'log-activity'], function () {
        Route::get('/', 'Log\LogActivityController@index')
            ->name('general.log.main');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('/', 'Log\AjaxGetLogActivity')
                ->name('ajax.general.get.log.activity');
            Route::get('/detail', 'Log\AjaxGetLogActivityDetail')
                ->name('ajax.general.get.log.activity.detail');
            Route::get('/group-by-employee', 'Log\AjaxGetLogActivityGroByEmloyee')
                ->name('ajax.general.get.log.activity.group.by.emloyee');
            Route::get('/excel', 'Log\AjaxGetLogActivityExcel')
                ->name('ajax.general.get.log.activity.excel');
        });
    });

    //Bank Route Group
    Route::group(['prefix' => 'bank'], function () {
        Route::get('/', 'Bank\BankController@index')
            ->name('general.bank.index');
        Route::post('/store', 'Bank\BankController@store')
            ->name('general.bank.store');
        Route::put('/update/{id}', 'Bank\BankController@update')
            ->name('general.bank.update');
        Route::put('/update-status/{id}', 'Bank\BankController@updateStatus')
            ->name('general.bank.update.status');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('bank', 'Bank\AjaxGetBank')
                ->name('ajax.general.get.bank');
            Route::get('bank-options', 'Bank\AjaxGetBankOptions')
                ->name('ajax.general.get.bank.options');
            // Destroy
            Route::delete('destroy/{id}', 'Bank\AjaxDestroyBank')
                ->name('ajax.general.destroy.bank');
            // Check Unique
            Route::get('/bank/name/exist', 'Bank\AjaxCheckPropertyExist@isUniqueName')
                ->name('ajax.general.check.bank.name.exist');
        });
    });

    //Bank Account Route Group
    Route::group(['prefix' => 'bank-account'], function () {
        Route::get('/', 'BankAccount\BankAccountController@index')
            ->name('general.bank.account.index');
        Route::post('/store', 'BankAccount\BankAccountController@store')
            ->name('general.bank.account.store');
        Route::put('/update/{id}', 'BankAccount\BankAccountController@update')
            ->name('general.bank.account.update');
        Route::put('/update-status/{id}', 'BankAccount\BankAccountController@updateStatus')
            ->name('general.bank.account.update.status');

        Route::group(['prefix' => 'api/v1'], function () {
            Route::get('bankaccount', 'BankAccount\AjaxGetBankAccount')
                ->name('ajax.general.get.bank.account');
            Route::get('bankaccount-options', 'BankAccount\AjaxGetBankAccountOptions')
                ->name('ajax.general.get.bank.account.options');
            // Destroy
            Route::delete('destroy/{id}', 'BankAccount\AjaxDestroyBankAccount')
                ->name('ajax.general.destroy.bank.account');
            // Check Unique
            Route::get('/bankaccount/name/exist', 'BankAccount\AjaxCheckPropertyExist@isUniqueName')
                ->name('ajax.general.check.bank.account.name.exist');
        });
    });

});
