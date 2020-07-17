<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\PublicWarehouse\Entities\DeliveryOrder;
use Modules\PublicWarehouse\Entities\DeliveryOrderItem;
use Modules\PublicWarehouse\Entities\GoodsBorrowItem;
use Modules\PublicWarehouse\Entities\GoodsRequest;
use Modules\PublicWarehouse\Entities\GoodsRequestItems;
use Modules\PublicWarehouse\Entities\GoodsReturn;
use Modules\PurchaseOrder\Entities\Outstanding;
use Modules\PurchaseOrder\Entities\OutstandingDetail;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Entities\PurchaseRequest;
use Modules\PurchaseOrder\Entities\ServiceOrder;
use Modules\PurchaseOrder\Entities\ServiceRequest;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\StockAdjustment;
use Modules\Stock\Entities\StockAdjustmentItem;
use Modules\Stock\Entities\StockClosing;
use Modules\Stock\Entities\StockOpname;
use Modules\Stock\Entities\StockOpnameGroup;
use Modules\Stock\Entities\StockQuarantine;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Transformers\Stock\StockHistoryResource;


if (! function_exists('getStatistictGudangUmum')) {
    function getStatistictGudangUmum()
    {
        $statistict = [];
        // Goods Request
        $row_goods_request = getDashboardGoodsRequest();
        $statistict['goods_request'] = [
            'title' => 'Permintaan Barang',
            'badge' => getStatistictBadge($row_goods_request),
            'value' => $row_goods_request,
        ];
        // Goods Borrow
        $row_goods_borrow= getDashboardGoodsBorrow();
        $statistict['goods_borrow'] = [
            'title' => 'Peminjaman Barang',
            'badge' => getStatistictBadge($row_goods_borrow),
            'value' => $row_goods_borrow,
        ];
        // Service Request
        $row_service_request = getDashboardServiceRequest('all');
        $statistict['service_request'] = [
            'title' => 'Permintaan Jasa',
            'badge' => getStatistictBadge($row_service_request),
            'value' => $row_service_request,
        ];
        // Purchase Request
        $row_purchase_request = getDashboardPurchaseRequest('all');
        $statistict['purchase_request'] = [
            'title' => 'Pengajuan Barang',
            'badge' => getStatistictBadge($row_purchase_request),
            'value' => $row_purchase_request,
        ];
        // Delivery Order
        $row_delivery_order= getDashboardDeliveryOrder();
        $statistict['delivery_order'] = [
            'title' => 'Barang Masuk',
            'badge' => getStatistictBadge($row_delivery_order),
            'value' => $row_delivery_order,
        ];
        // Penyesuaian Stock
        $row_stock_adjustment= getDashboardStockAdjustment();
        $statistict['stock_adjustment'] = [
            'title' => 'Penyesuaian Stok',
            'badge' => getStatistictBadge($row_stock_adjustment),
            'value' => $row_stock_adjustment,
        ];
        // Return
        $row_return= getDashboardReturn();
        $statistict['return'] = [
            'title' => 'Retur',
            'badge' => getStatistictBadge($row_return),
            'value' => $row_return,
        ];
        // Stock Opname
        $row_stock_opname= getDashboardStockOpname();
        $statistict['stock_opname'] = [
            'title' => 'Stok Opname',
            'badge' => getStatistictBadge($row_stock_opname),
            'value' => $row_stock_opname,
        ];

       return $statistict;
    }
}

if (! function_exists('getStatistictPurchasing')) {
    function getStatistictPurchasing()
    {
        $statistict = [];
        // Service Request
        $row_service_request = getDashboardServiceRequest('all');
        $statistict['service_request'] = [
            'title' => 'Pengajuan Jasa',
            'badge' => getStatistictBadge($row_service_request),
            'value' => $row_service_request,
        ];
        // Service Order
        $row_service_order = getDashboardServiceOrder();
        $statistict['service_order'] = [
            'title' => 'PO Pengadaan Jasa',
            'badge' => getStatistictBadge($row_service_order),
            'value' => $row_service_order,
        ];
        // Purchase Request
        $row_purchase_request = getDashboardPurchaseRequest('all');
        $statistict['purchase_request'] = [
            'title' => 'Pengajuan Barang',
            'badge' => getStatistictBadge($row_purchase_request),
            'value' => $row_purchase_request,
        ];
        // Purchase Order
        $row_purchase_order = getDashboardPurchaseOrder(1);
        $statistict['purchase_order'] = [
            'title' => 'Pembelian PO',
            'badge' => getStatistictBadge($row_purchase_order),
            'value' => $row_purchase_order,
        ];
        // Direct Purchase
        $row_direct_purchase = getDashboardPurchaseOrder(2);
        $statistict['direct_purchase'] = [
            'title' => 'Pembelian Langsung',
            'badge' => getStatistictBadge($row_direct_purchase),
            'value' => $row_direct_purchase,
        ];

        // Waiting DO
        $row_waiting_od = getDashboardPOWaitingDO();
        $statistict['waiting_do '] = [
            'title' => 'Menunggu Kedatangan',
            'badge' => getStatistictBadge($row_waiting_od),
            'value' => $row_waiting_od,
        ];

        // Delivery Order
        $row_delivery_order= getDashboardDeliveryOrder();
        $statistict['delivery_order'] = [
            'title' => 'Barang Masuk',
            'badge' => getStatistictBadge($row_delivery_order),
            'value' => $row_delivery_order,
        ];
        // Return
        $row_return= getDashboardReturn();
        $statistict['return'] = [
            'title' => 'Retur',
            'badge' => getStatistictBadge($row_return),
            'value' => $row_return,
        ];

       return $statistict;
    }
}


if (! function_exists('getDashboardPurchaseOrder')) {
    function getDashboardPurchaseOrder($po_type)
    {
        $query = PurchaseOrder::query()
                ->whereYear('issue_date', substr(now(),0,4))
                ->whereMonth('issue_date', substr(now(),5,2))
                ->where('po_type', $po_type)
                ->where('status', '<>', PurchaseOrder::CANCEL);
        $row = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardServiceOrder')) {
    function getDashboardServiceOrder()
    {
        $query = ServiceOrder::query()
                ->whereYear('issue_date', substr(now(),0,4))
                ->whereMonth('issue_date', substr(now(),5,2));
        $row = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardGoodsRequest')) {
    function getDashboardGoodsRequest()
    {
        $query = GoodsRequestItems::query()
                ->whereYear('created_at', substr(now(),0,4))
                ->whereMonth('created_at', substr(now(),5,2))
                ->where('status', '<>', GoodsRequestItems::CANCEL);

        $row = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardServiceRequest')) {
    function getDashboardServiceRequest($status)
    {
        $query = ServiceRequest::query();
        if ($status=="request") {
            $query = $query->where('status', ServiceRequest::REQUEST);
        } else {
            $query = $query->whereYear('issue_date', substr(now(),0,4))
                ->whereMonth('issue_date', substr(now(),5,2));
        }
        $row = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardPurchaseRequest')) {
    function getDashboardPurchaseRequest($status)
    {
        $query = PurchaseRequest::query();
        if ($status=="request") {
            $query = $query->where('status', PurchaseRequest::REQUEST);
        } else {
            $query = $query->whereYear('issue_date', substr(now(),0,4))
                ->whereMonth('issue_date', substr(now(),5,2))
                ->where('status', '<>', PurchaseRequest::CANCEL);
        }
        $row = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardGoodsBorrow')) {
    function getDashboardGoodsBorrow()
    {
        $query = GoodsBorrowItem::query()
                ->whereYear('created_at', substr(now(),0,4))
                ->whereMonth('created_at', substr(now(),5,2));

        $row   = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardDeliveryOrder')) {
    function getDashboardDeliveryOrder()
    {
        $query = DeliveryOrder::query()
                ->whereYear('arrival_date', substr(now(),0,4))
                ->whereMonth('arrival_date', substr(now(),5,2))
                ->where('status', DeliveryOrder::ACTIVE);
        $row   = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardStockAdjustment')) {
    function getDashboardStockAdjustment()
    {
        $query = StockAdjustmentItem::query()
                ->whereYear('issue_date', substr(now(),0,4))
                ->whereMonth('issue_date', substr(now(),5,2));
        $row   = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardReturn')) {
    function getDashboardReturn()
    {
        $query = GoodsReturn::query()
                ->whereYear('created_at', substr(now(),0,4))
                ->whereMonth('created_at', substr(now(),5,2));
        $row   = $query->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardStockOpname')) {
    function getDashboardStockOpname()
    {
        $query = StockOpname::query()
                ->whereYear('issue_date', substr(now(),0,4))
                ->whereMonth('issue_date', substr(now(),5,2));
        $row   = $query->get();
        return $row->count();
    }
}


if (! function_exists('getDashboardPOWaitingDO')) {
    function getDashboardPOWaitingDO()
    {
        $row = PurchaseOrder::query()
            ->whereIN('status_do', [1, 2])
            ->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardPOIssue')) {
    function getDashboardPOIssue()
    {
        $row = PurchaseOrder::query()
            ->whereIN('status_do', [2, 4])
            ->get();
        return $row->count();
    }
}


if (! function_exists('getDashboardGoodsReturn')) {
    function getDashboardGoodsReturn()
    {
        $row = GoodsReturn::query()
            ->where('is_active', 1)
            ->whereIN('status', [1, 2])
            ->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardGoodsRequestPending')) {
    function getDashboardGoodsRequestPending()
    {
        $row = GoodsRequestItems::query()
            ->where('is_active', 1)
            ->whereIN('status', [1, 2])
            ->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardGoodsBorrowDueDate')) {
    function getDashboardGoodsBorrowDueDate()
    {
        $row = GoodsBorrowItem::with('goodsBorrow')
        ->whereHas('goodsBorrow', function ($query) {
            $query->where('target_return_date', '<', date('Y-m-d'));
        })
        ->where('is_active', 1)
        ->where('return_status', 0)
        ->get();

        return $row->count();
    }
}

if (! function_exists('getDashboardLoadigDocks')) {
    function getDashboardLoadigDocks()
    {
        $row = DeliveryOrderItem::query()
                ->where('storaged', 0)
                ->get();
        return $row->count();
    }
}

if (! function_exists('getDashboardStockOpnameDaily')) {
    function getDashboardStockOpnameDaily()
    {
        $stockOpnameGroup = StockOpnameGroup::query()
            ->where('type', 'daily')
            ->whereDate('issue_date', now())
            ->where('status', '<>', StockOpnameGroup::CANCELED)
            ->first();

        if (!empty($stockOpnameGroup)) {
            $stockOpname = StockOpname::query()
                ->where('stock_opname_group_id', $stockOpnameGroup->id)
                ->where('stock_status', StockOpname::STOCK_WAITING)
                ->get();
            if ($stockOpname->count()>0) {
                $data['status'] = 1;
                $data['desc'] = "Belum dilakukan";
            } else {
                $data['status'] = 0;
                $data['desc'] = "Sudah dilakukan";
            }

        } else {
            $data['status'] = 1;
            $data['desc'] = "Belum dilakukan";
        }

        return $data;
    }
}

if (! function_exists('getDashboardStockOpnameIssue')) {
    function getDashboardStockOpnameIssue()
    {
        $stockOpname = StockOpname::query()
            ->whereIn('stock_status', [2, 3])
            ->where('quantity_issue','>',0)
            ->whereRaw('quantity_issue <> quantity_adjustment')
            ->get();

        return $stockOpname->count();
    }
}

if (! function_exists('getDashboardStockLess')) {
    function getDashboardStockLess()
    {
        $stock = Item::query()
            ->whereRaw('min_stock > current_stock')
            ->get();

        return $stock->count();
    }
}

if (! function_exists('getDashboardReportPO')) {
    function getDashboardReportPO()
    {
        $stock = PurchaseOrder::query()
            ->where('status', PurchaseOrder::DONE)
            ->whereYear('issue_date', substr(now(),0,4))
            ->whereMonth('issue_date', substr(now(),5,2))
            ->get();

        return $stock->count();
    }
}

if (! function_exists('getDashboardApprovals')) {
    function getDashboardApprovals()
    {
        $approvals = StockAdjustmentItem::query()
            ->where('approvals_status', StockAdjustmentItem::APPROVALS_PENDING)
            ->get();
        return $approvals->count();
    }
}

if (! function_exists('getDashboardIcApprovals')) {
    function getDashboardIcApprovals()
    {
        $approvals = StockAdjustmentItem::query()
            ->whereHas('StockOpnameGroup', function ($query) {
                $query->where('type','daily');
            })
            ->where('approvals_status', StockAdjustmentItem::APPROVALS_PENDING)
            ->get();
        return $approvals->count();
    }
}

if (! function_exists('getDashboardPOOutstanding')) {
    function getDashboardPOOutstanding()
    {
        $outstanding = Outstanding::query()
            ->where('is_active', 1)
            ->where('status', Outstanding::PAY_OUTSTANDING)
            ->get();
        return $outstanding->count();
    }
}

if (! function_exists('getDashboardPOOutstandingDuedate')) {
    function getDashboardPOOutstandingDuedate()
    {
        $outstanding = OutstandingDetail::query()
            ->where('is_active', 1)
            ->where('due_date', '<=', date('Y-m-d'))
            ->where('status', OutstandingDetail::PAY_OUTSTANDING)
            ->get();
        return $outstanding->count();
    }
}

if (! function_exists('getDashboardIcClosingStock')) {
    function getDashboardIcClosingStock()
    {
        $closingStock = getClosingStockStatus();
        return $closingStock;
    }
}

// ---------------------------------------------------------

if (! function_exists('getDashboardStockQuarantine')) {
    function getDashboardStockQuarantine()
    {
    $filter_date = Carbon::now()->subMonth(6);
    $filter_date = $filter_date->format('Y-m-d')." 00:00:00";
    $data = StockTransaction::query()
        ->whereIn('transaction_symbol', ['DO', 'OA'])
        ->where(function ($query) use ($filter_date) {
            $query->where('transaction_date', '<=', $filter_date)
                    ->where('stock_quarantine_id', null)
                    ->where('stock_category', StockTransaction::SC_ACTIVE)
                    ->orWhere('stock_quarantine_date', '<=', $filter_date)
                    ->where('stock_quarantine_id', '>', 0)
                    ->where('stock_category', StockTransaction::SC_QUARANTINE);
        })
        ->where('stock_current', '>', 0)
        ->get();
        return $data->count();
    }
}

if (! function_exists('getDashboardStockDead')) {
    function getDashboardStockDead()
    {
        $data = StockQuarantine::query()
            ->whereNotIn('id', DB::table('stock_adjustment_items')
                ->where('stock_quarantine_id', '>', 0)
                ->where('deleted_at', null)
                ->pluck('stock_quarantine_id'))
            ->where('action', 2)
            ->get();
        return $data->count();
    }
}

if (! function_exists('getDashboardWaitingReport')) {
    function getDashboardWaitingReport()
    {
        $report = StockAdjustmentItem::query()
            ->where('is_active', 1)
            ->where('status', StockAdjustmentItem::PROCESS)
            ->where('approvals_status',StockAdjustmentItem::APPROVALS_APPROVED)
            ->where('stock_opname_id', null)
            ->where('release_by', null)
            ->get();
        return $report->count();
    }
}

if (! function_exists('getDashboardStockRepairment')) {
    function getDashboardStockRepairment()
    {
        $outstanding = OutstandingDetail::query()
            ->where('is_active', 1)
            ->where('due_date', '<=', date('Y-m-d'))
            ->where('status', OutstandingDetail::PAY_OUTSTANDING)
            ->get();
        return $outstanding->count();
    }
}


if (! function_exists('getReminderStatistictIcon')) {
    function getReminderStatistictIcon($row)
    {
        return ($row>0) ? 'fas fa-exclamation-circle' : 'far fa-check-circle';
    }
}

if (! function_exists('getReminderStatistictStatus')) {
    function getReminderStatistictStatus($row)
    {
        return ($row>0) ? 'bg-warning' : 'bg-success';
    }
}

if (! function_exists('getStatistictBadge')) {
    function getStatistictBadge($row)
    {
        if ($row<=0) {
            $badge = "bg-secondary";
        } elseif ($row>0 and $row<=5) {
            $badge = "bg-info";
        } elseif ($row>5 and $row<=10) {
            $badge = "bg-success";
        } elseif ($row>10 and $row<=15) {
            $badge = "bg-warning";
        } else {
            $badge = "bg-danger";
        }

        return $badge;
    }
}
