<?php

namespace Modules\General\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AjaxGetDashboardPanelNotification extends Controller
{
    public function __invoke(Request $request)
    {

        $data = [
            'title' => 'Transaksi',
            'bgColor' => 'bg-info',
            'icon' => 'fas fa-shopping-bag',
            'url' => '#',
            'description' => '0',
        ];

        $name = $request->name;

        if ($name=="purchase_request") {
            $url = "#";
            if (getAuthLevelName(session('user_level'))=="pembelian") {
                $url = "po/purchase-order?tab=request";
            } else {
                $url = "po/purchase-request";
            }

            $data = [
                'title' => 'Pengajuan Barang',
                'bgColor' => 'bg-info',
                'icon' => 'fas fa-shopping-bag',
                'url' => $url,
                'description' => getDashboardPurchaseRequest('request'),
            ];
        } elseif ($name=="po_waiting_do") {
            $data = [
                'title' => 'Menunggu Kedatangan',
                'bgColor' => 'bg-success',
                'icon' => 'fas fa-dolly-flatbed',
                'url' => 'wh/delivery-order/po',
                'description' => getDashboardPOWaitingDO(),
            ];
        } elseif ($name=="po_issue") {
            $data = [
                'title' => 'Penerimaan tidak sesuai PO',
                'bgColor' => 'bg-danger',
                'icon' => 'fas fa-list-ol',
                'url' => 'po/purchase-order',
                'description' => getDashboardPOIssue(),
            ];
        } elseif ($name=="goods_request_pending") {
            $data = [
                'title' => 'Permintaan Belum Terpenuhi',
                'bgColor' => 'bg-warning',
                'icon' => 'fas fa-boxes',
                'url' => 'wh/goods-request?tab=pending',
                'description' => getDashboardGoodsRequestPending(),
            ];
        } elseif ($name=="goods_borrow_due_date") {
            $data = [
                'title' => 'Peminjaman Jatuh Tempo',
                'bgColor' => 'bg-danger',
                'icon' => 'fas fa-business-time',
                'url' => 'wh/goods-borrow?tab=expired',
                'description' => getDashboardGoodsBorrowDueDate(),
            ];
        } elseif ($name=="stock_less") {
            $data = [
                'title' => 'Stok Kurang',
                'bgColor' => 'bg-warning',
                'icon' => 'fas fa-chart-area',
                'url' => 'stock/goods?tab=less',
                'description' => getDashboardStockLess(),
            ];
        } elseif ($name=="stock_opname") {
            $data = [
                'title' => 'Stok Opname',
                'bgColor' => 'bg-danger',
                'icon' => 'fas far fa-list-alt',
                'url' => 'stock/opname',
                'description' => getDashboardStockOpname(),
            ];
        } elseif ($name=="report_po") {
            $data = [
                'title' => 'Laporan PO',
                'bgColor' => 'bg-success',
                'icon' => 'fas fas fa-store',
                'url' => 'po/purchase-order/report',
                'description' => getDashboardReportPO(),
            ];
        } elseif ($name=="approvals") {
            $data = [
                'title' => 'Menunggu Persetujuan',
                'bgColor' => 'bg-info',
                'icon' => 'far fa-calendar-check',
                'url' => 'stock/adjustment/approvals',
                'description' => getDashboardApprovals(),
            ];
        } elseif ($name=="service_request") {
                $url = "#";
                if (getAuthLevelName(session('user_level'))=="pembelian") {
                   $url = "po/service-order?tab=request";
                } else {
                   $url = "po/service-request";
                }

                $data = [
                    'title' => 'Pengajuan Jasa',
                    'bgColor' => 'bg-success',
                    'icon' => 'fas fa-shipping-fast',
                    'url' => $url,
                    'description' => getDashboardServiceRequest('request'),
                ];
        }
        return $data;
    }
}
