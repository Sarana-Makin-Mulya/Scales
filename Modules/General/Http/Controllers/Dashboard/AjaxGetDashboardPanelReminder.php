<?php

namespace Modules\General\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AjaxGetDashboardPanelReminder extends Controller
{
    public function __invoke(Request $request)
    {
        $data = [
            'title' => 'Pengingat',
            'bgColor' => 'bg-warning',
            'icon' => 'fas fa-exclamation-circle',
            'description' => '0 Barang',
        ];

        $name = $request->name;

        if ($name=="ic_approvals") {
            $ic_approvals = getDashboardIcApprovals();
            $data = [
                'title' => 'Persetujuan',
                'bgColor' => getReminderStatistictStatus($ic_approvals),
                'icon' => getReminderStatistictIcon($ic_approvals),
                'description' => $ic_approvals." permintaan",
                'url' => 'stock/adjustment/approvals',
            ];
        } elseif ($name=="ic_closing_stock") {
            $ic_closing_stock = getClosingStockStatus();
            if ($ic_closing_stock['status']==1) {
                $closingRow = 0;
                $status = "selesai";
            } else {
                $closingRow = 1;
                $status = "belum";

            }

            $data = [
                'title' => 'Closing Stock Bulanan',
                'bgColor' => getReminderStatistictStatus($closingRow),
                'icon' => getReminderStatistictIcon($closingRow),
                'description' => $ic_closing_stock['monthName']." ".$ic_closing_stock['year']." ".$status,
                'url' => 'stock/closing',
            ];

        } elseif ($name=="loading_doks") {
            $loading_cocks = getDashboardLoadigDocks();
            $data = [
                'title' => 'Loading Docks',
                'bgColor' => getReminderStatistictStatus($loading_cocks),
                'icon' => getReminderStatistictIcon($loading_cocks),
                'description' => $loading_cocks." barang",
                'url' => 'wh/delivery-order/storaged',
            ];
        } elseif ($name=="stock_opname_daily") {
            $stock_opname_daily = getDashboardStockOpnameDaily();
            $data = [
                'title' => 'Stok Opname Harian',
                'bgColor' => getReminderStatistictStatus($stock_opname_daily['status']),
                'icon' => getReminderStatistictIcon($stock_opname_daily['status']),
                'description' => $stock_opname_daily['desc'],
                'url' => 'stock/opname/daily',
            ];
        } elseif ($name=="stock_opname_issue") {
            $stock_opname_issue = getDashboardStockOpnameIssue();
            $data = [
                'title' => 'Stok Opname Bermasalah',
                'bgColor' => getReminderStatistictStatus($stock_opname_issue),
                'icon' => getReminderStatistictIcon($stock_opname_issue),
                'description' => $stock_opname_issue." barang",
                'url' => 'stock/opname/daily',
            ];
        } elseif ($name=="stock_less") {
            $stock_less = getDashboardStockLess();
            $data = [
                'title' => 'Stok Kurang',
                'bgColor' => getReminderStatistictStatus($stock_less),
                'icon' => getReminderStatistictIcon($stock_less),
                'description' => $stock_less." barang",
                'url' => 'stock/buffer',
            ];
        } elseif ($name=="po_outstanding") {
            $po_outstanding = getDashboardPOOutstanding();
            $data = [
                'title' => 'PO Outstanding',
                'bgColor' => getReminderStatistictStatus($po_outstanding),
                'icon' => getReminderStatistictIcon($po_outstanding),
                'description' => $po_outstanding." PO",
                'url' => 'po/payment?tab=outstanding',
            ];
        } elseif ($name=="outstanding_due_date") {
            $outstanding_due_date = getDashboardPOOutstandingDuedate();
            $data = [
                'title' => 'Tagihan Jatuh Tempo',
                'bgColor' => getReminderStatistictStatus($outstanding_due_date),
                'icon' => getReminderStatistictIcon($outstanding_due_date),
                'description' => $outstanding_due_date." Tagihan",
                'url' => 'po/payment?tab=outstanding&filter=due-date',
            ];
        } elseif ($name=="do_off_target") {
            $outstanding_due_date = getDashboardPOOutstandingDuedate();
            $data = [
                'title' => 'Penerimaan di luar target',
                'bgColor' => getReminderStatistictStatus($outstanding_due_date),
                'icon' => getReminderStatistictIcon($outstanding_due_date),
                'description' => $outstanding_due_date." Pesanan",
                'url' => 'po/payment?tab=outstanding&filter=due-date',
            ];
        } elseif ($name=="stock_quarantine") {
            $stock_quarantine = getDashboardStockQuarantine();
            $data = [
                'title' => 'Karantina',
                'bgColor' => getReminderStatistictStatus($stock_quarantine),
                'icon' => getReminderStatistictIcon($stock_quarantine),
                'description' => $stock_quarantine." Barang",
                'url' => 'stock/quarantine',
            ];
        } elseif ($name=="dead_stock") {
            $dead_stock = getDashboardStockDead();
            $data = [
                'title' => 'Dead Stock',
                'bgColor' => getReminderStatistictStatus($dead_stock),
                'icon' => getReminderStatistictIcon($dead_stock),
                'description' => $dead_stock." Barang",
                'url' => 'stock/dead',
            ];
        } elseif ($name=="waiting_report") {
            $waiting_report = getDashboardWaitingReport();
            $data = [
                'title' => 'Menunggu Laporan',
                'bgColor' => getReminderStatistictStatus($waiting_report),
                'icon' => getReminderStatistictIcon($waiting_report),
                'description' => $waiting_report." Penyesuaian Stok",
                'url' => 'stock/adjustment?filter_status=2',
            ];
        } elseif ($name=="repairment_area") {
            $repairment_area = getDashboardStockRepairment();
            $data = [
                'title' => 'Repairment Area',
                'bgColor' => getReminderStatistictStatus($repairment_area),
                'icon' => getReminderStatistictIcon($repairment_area),
                'description' => $repairment_area." Barang",
                'url' => 'wh/goods-repairment',
            ];
        } elseif ($name=="borrowed_expired") {
            $borrowed_expired = getDashboardGoodsBorrowDueDate();
            $data = [
                'title' => 'Peminjaman Jatuh Tempo',
                'bgColor' => getReminderStatistictStatus($borrowed_expired),
                'icon' => getReminderStatistictIcon($borrowed_expired),
                'description' => $borrowed_expired." Barang",
                'url' => 'wh/goods-borrow?tab=expired',
            ];
        } elseif ($name=="po_issue") {
            $po_issue = getDashboardPOIssue();
            $data = [
                'title' => 'Penerimaan tidak sesuai PO',
                'bgColor' => getReminderStatistictStatus($po_issue),
                'icon' => getReminderStatistictIcon($po_issue),
                'description' => $po_issue." PO",
                'url' => 'po/purchase-order',
            ];
        } elseif ($name=="goods_return") {
            $goods_return = getDashboardGoodsReturn();
            $data = [
                'title' => 'Proses Retur',
                'bgColor' => getReminderStatistictStatus($goods_return),
                'icon' => getReminderStatistictIcon($goods_return),
                'description' => $goods_return." Barang",
                'url' => 'wh/goods-return?tab=return',
            ];
        }

        return $data;
    }
}
