<?php

use Illuminate\Support\Facades\DB;
use Modules\PublicWarehouse\Entities\DeliveryOrderItem;
use Modules\PublicWarehouse\Entities\GoodsReturn;
use Modules\PurchaseOrder\Entities\Outstanding;
use Modules\PurchaseOrder\Entities\OutstandingDetail;
use Modules\PurchaseOrder\Entities\OutstandingDiscon;
use Modules\PurchaseOrder\Entities\Payment;
use Modules\PurchaseOrder\Entities\PaymentDetail;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Entities\PurchaseOrderItems;
use Modules\PurchaseOrder\Entities\PurchaseOrderTermin;
use Modules\PurchaseOrder\Entities\ServiceOrder;
use Modules\PurchaseOrder\Entities\ServiceOrderFee;
use Modules\PurchaseOrder\Entities\ServiceOrderTermin;
use Modules\Supplier\Entities\SupplierSaldo;
use Modules\Supplier\Entities\SupplierSaldoUsed;

if (! function_exists('getPOSupplierCode')) {
    function getPOSupplierCode($code)
    {
        $module = new Modules\PurchaseOrder\Entities\PurchaseOrder;
        $data = $module::query()
            ->where('code',$code)
            ->first();
        return !empty($data) ? $data->supplier_code : '';

    }
}

if (! function_exists('getDeliveryOrderReturRecepitsQuantity')) {
    function getDeliveryOrderReturRecepitsQuantity($retur_code)
    {
        $do_quantity = 0;
        if (GoodsReturn::find($retur_code)) {
            $DOItem = DeliveryOrderItem::query()
                ->select(DB::raw('sum(quantity) as total_quantity'))
                ->where('goods_return_code', $retur_code)
                ->where('is_active',1)
                ->first();
            $do_quantity = (!empty($DOItem)) ? $DOItem->total_quantity : 0;
        }
        return (!empty($do_quantity)) ? $do_quantity : 0;
    }
}

if (! function_exists('getDeliveryOrderReturStatus')) {
    function getDeliveryOrderReturStatus($retur_code)
    {
        $data = ['replace_status' => null, 'status' => null];
        if ($goodsReturn = GoodsReturn::find($retur_code)) {
            $total_retur    = $goodsReturn->quantity;
            $total_receipts = getDeliveryOrderReturRecepitsQuantity($retur_code);

            if ($total_retur <= $total_receipts) {
                $data = [
                    'replace_status' => GoodsReturn::REPLACE_COMPLETED,
                    'status' =>  GoodsReturn::COMPLETED,
                ];
            } elseif ($total_receipts>0 && $total_retur>$total_receipts) {
                $data = [
                    'replace_status' => GoodsReturn::REPLACE_PARTLY,
                    'status' => GoodsReturn::PROCESS,
                ];
            } else {
                $data = [
                    'replace_status' => GoodsReturn::REPLACE_WAITING,
                    'status' => GoodsReturn::PROCESS,
                ];
            }
        }

        return $data;
    }
}

if (! function_exists('getDeliveryOrderItemQuantity')) {
    function getDeliveryOrderItemQuantity($po_code, $id)
    {
        $do_quantity = 0;
        $PurchaseOrder = new Modules\PurchaseOrder\Entities\PurchaseOrder;
        if ($PO = $PurchaseOrder::find($po_code)) {
            $DeliveryOrderItem = new Modules\PublicWarehouse\Entities\DeliveryOrderItem;
            //if ($PO->po_type==1) {
                $DOItem = $DeliveryOrderItem::query()
                    ->select(DB::raw('sum(quantity) as total_quantity'))
                    ->where('purchasing_purchase_order_item_id', $id)
                    ->where('is_active',1)
                    ->first();
                $do_quantity = (!empty($DOItem)) ? $DOItem->total_quantity : 0;
            // } else {
            //     $DOItem = $DeliveryOrderItem::query()
            //         ->select(DB::raw('sum(quantity) as total_quantity'))
            //         ->where('purchasing_purchase_order_item_direct_id', $id)
            //         ->where('is_active',1)
            //         ->first();
            //     $do_quantity = (!empty($DOItem)) ? $DOItem->total_quantity : 0;
            // }
        }

        return (!empty($do_quantity)) ? $do_quantity : 0;
    }
}

if (! function_exists('getDeliveryOrderItemQuantityString')) {
    // untuk string akumulasi total barang diterima beserta satuannya
    function getDeliveryOrderItemQuantityString($po_code, $id)
    {
        $do_quantity = 0;
        $PurchaseOrder = new Modules\PurchaseOrder\Entities\PurchaseOrder;
        if ($PO = $PurchaseOrder::find($po_code)) {
            $DeliveryOrderItem = new Modules\PublicWarehouse\Entities\DeliveryOrderItem;
            //if ($PO->po_type==1) {
                $DOItem = $DeliveryOrderItem::query()
                    ->select(DB::raw('sum(quantity) as total_quantity'))
                    ->where('purchasing_purchase_order_item_id', $id)
                    ->where('is_active',1)
                    ->first();
                $do_quantity = (!empty($DOItem)) ? $DOItem->total_quantity : 0;
            // } else {
            //     $DOItem = $DeliveryOrderItem::query()
            //         ->select(DB::raw('sum(quantity) as total_quantity'))
            //         ->where('purchasing_purchase_order_item_direct_id', $id)
            //         ->where('is_active',1)
            //         ->first();
            //     $do_quantity = (!empty($DOItem)) ? $DOItem->total_quantity : 0;
            // }
        }

        return (!empty($do_quantity)) ? $do_quantity : 0;
    }
}

if (! function_exists('getPOSubTotal')) {
    function getPOSubTotal($code)
    {
        $module = new Modules\PurchaseOrder\Entities\PurchaseOrderItems;
        $data = $module::query()
            ->select(DB::raw('sum(quantity*price) as total'))
            ->where('purchasing_purchase_order_code',$code)
            ->where('is_active',1)
            ->first();
        return $data->total;

    }
}

if (! function_exists('getPoItemQuantityStatus')) {
    function getPoItemQuantityStatus($po_code, $id)
    {
        $PurchaseOrderItem = new Modules\PurchaseOrder\Entities\PurchaseOrderItems;

        $POItem = $PurchaseOrderItem::where('is_active',1)->where('id',$id)->first();
        $po_quantity = (!empty($POItem)) ? $POItem->quantity : 0;
        $do_quantity = getDeliveryOrderItemQuantity($po_code, $id);

        if ($do_quantity==0) {
            $status = $PurchaseOrderItem::PROCESS;
        } elseif ($do_quantity==$po_quantity) {
            $status = $PurchaseOrderItem::COMPLETED;
        } elseif ($do_quantity>$po_quantity) {
            $status = $PurchaseOrderItem::EXCESS;
        } else {
            $status = $PurchaseOrderItem::PARTLY;
        }

        return $status;
    }
}

if (! function_exists('getPoStatus')) {
    function getPoStatus($po_code) // OK
    {
        $PurchaseOrder = new Modules\PurchaseOrder\Entities\PurchaseOrder;
        $PurchaseOrderItem = new Modules\PurchaseOrder\Entities\PurchaseOrderItems;
        $PO             = $PurchaseOrderItem::where('purchasing_purchase_order_code', $po_code)->where('is_active',1)->whereIN('status', [1, 2])->get();
        $POPARTLY       = $PurchaseOrderItem::where('purchasing_purchase_order_code', $po_code)->where('is_active',1)->where('status', 2)->get();
        $POCOMPLETED    = $PurchaseOrderItem::where('purchasing_purchase_order_code', $po_code)->where('is_active',1)->where('status', 3)->get();
        $POEXCESS       = $PurchaseOrderItem::where('purchasing_purchase_order_code', $po_code)->where('is_active',1)->where('status', 4)->get();

        if ($PO->count()>0) {
            $status = ($POPARTLY->count()>0 OR $POCOMPLETED->count()>0) ? $PurchaseOrder::DO_PARTLY : $PurchaseOrder::DO_WAITING;
        } else {
            $status = ($POEXCESS->count()>0) ? $PurchaseOrder::DO_EXCESS : $PurchaseOrder::DO_COMPLETED;
        }

        return $status;
    }
}

if (! function_exists('getItemAvgPricev1')) {
    function getItemAvgPricev1($code)
    {
        if (!empty($code)) {
            $PurchaseOrderItems = new Modules\PurchaseOrder\Entities\PurchaseOrderItems;
            $data2 = $PurchaseOrderItems::query()
                ->select('price','created_at')
                ->where('item_code',$code)
                ->where('price','>',0)
                ->where('is_active',1)
                ->orderBy('created_at','desc')
                ->skip(0)
                ->take(5)
                ->get();

            $PurchaseOrderDirectDetail = new Modules\PurchaseOrder\Entities\PurchaseOrderDirectDetail;
            $data = $PurchaseOrderDirectDetail::query()
                ->select('price','created_at')
                ->where('item_code',$code)
                ->where('price','>',0)
                ->where('is_active',1)
                ->orderBy('created_at','desc')
                ->skip(0)
                ->take(5)
                ->get();

            $collection =  collect([$data2, $data]);
            $collapsed  = $collection->collapse();
            $collapsed->all();
            $avg_price = $collapsed->sortByDesc('created_at')->take(5)->avg('price');
            return ($avg_price>0) ? $avg_price : 0;
        }
        return 0;
    }
}

if (! function_exists('getItemAvgPrice')) {
    function getItemAvgPrice($code, $po_code)
    {
        if (!empty($code)) {
            $avg_count = 0;
            $price     = 0;
            $PurchaseOrderItems = new Modules\PurchaseOrder\Entities\PurchaseOrderItems;
            $query = $PurchaseOrderItems::query();

            if ($po_code!=null) {
                $query = $query->where('purchasing_purchase_order_code','<>', $po_code);
            }

            $data = $query->with('purchaseOrder')
                ->where('item_code',$code)
                ->where('price','>',0)
                ->where('is_active',1)
                ->orderBy('created_at','desc')
                ->skip(0)
                ->take(5)
                ->get();

                foreach ($data as $item) {
                    if (!empty($item->purchaseOrder)) {
                        $exchange_rate = ($item->purchaseOrder->exchange_rate>0) ? $item->purchaseOrder->exchange_rate : 1;
                    } else {
                        $exchange_rate = 0;
                    }
                    $price         = $price + ($item->price * $exchange_rate);
                    $avg_count++;
                }

               if ($price>0) {
                    return $price/$avg_count;
               } else {
                   return 0;
               }
        }
        return 0;
    }
}

//----------------------

if (! function_exists('getTermUnit')) {
    function getTermUnit($unit)
    {

        switch ($unit) {
            case '1':
                $unit_name = "Hari";
                break;
            case '2':
                $unit_name = "Minggu";
                break;
            case '3':
                $unit_name = "Bulan";
                break;
            case '4':
                $unit_name = "Tahun";
                break;
            default:
                $unit_name = "Bulan";
                break;
        }
        return $unit_name;
    }
}

if (! function_exists('getPaymentStatus')) {
    function getPaymentStatus($status)
    {

        switch ($status) {
            case '1':
                $status_name = "Belum Bayar";
                break;
            case '2':
                $status_name = "Sebagian";
                break;
            case '3':
                $status_name = "Lunas";
                break;
            case '4':
                $status_name = "Lebih Bayar";
                break;
            default:
                $status_name = "Belum Bayar";
                break;
        }
        return $status_name;
    }
}

if (! function_exists('getMoneyShow')) {
    function getMoneyShow()
    {
        $user_level = getAuthorization(getAuthLevel(), session('user_level'));
        switch ($user_level) {
            case 'pembelian':
                $status = true;
                break;
            case 'direksi':
                $status = true;
                break;
            default:
                $status = false;
                break;
        }
        return $status;
    }
}

if (! function_exists('getTotalPO')) {
    function getTotalPO($code, $type)
    {
        if ($type=="PO") {
            $purchaseOrder = PurchaseOrderItems::query()
                ->select(DB::raw('sum(quantity * price) as total_po'))
                ->where('purchasing_purchase_order_code', $code)
                ->where('is_active', 1)
                ->first();
            $total      = (!empty($purchaseOrder)) ? $purchaseOrder->total_po : 0;
            $outstanding   = Outstanding::query()
                ->where('purchasing_purchase_order_code', $code)
                ->first();
            $ppn_nominal = (!empty($outstanding)) ? $outstanding->ppn_nominal : 0;
            $total_discon = getTotalPODisconPerPO($code, 'PO');
        } else {
            $serviceOrder = ServiceOrderFee::query()
                ->select(DB::raw('sum(quantity * price) as total_so'))
                ->where('service_order_code', $code)
                ->where('is_active', 1)
                ->first();
            $total      = (!empty($serviceOrder)) ? $serviceOrder->total_so : 0;
            $outstanding   = Outstanding::query()
                ->where('purchasing_service_order_code', $code)
                ->first();
            $ppn_nominal = (!empty($outstanding)) ? $outstanding->ppn_nominal : 0;
            $total_discon = getTotalPODisconPerPO($code, 'SO');
        }

        return ($total-$total_discon) + $ppn_nominal;
    }
}

if (! function_exists('getTotalPODiscon')) {
    function getTotalPODiscon($code, $item_id, $type)
    {
        if ($type=="PO") {
            $PO = OutstandingDiscon::query()
                ->where('purchase_order_code', $code)
                ->where('purchase_order_item_id', $item_id)
                ->where('is_active', 1)
                ->first();
            $discon      = (!empty($PO)) ? ['nominal' => $PO->discon_nominal, 'percen' => $PO->discon_percen, 'note' => $PO->note] : ['nominal' => null, 'percen' => null, 'note' => null];
        } else {
            $SO = OutstandingDiscon::query()
                ->where('service_order_code', $code)
                ->where('service_order_fee_id', $item_id)
                ->where('is_active', 1)
                ->first();
            $discon      = (!empty($SO)) ? ['nominal' => $SO->discon_nominal, 'percen' => $SO->discon_percen, 'note' => $SO->note] : ['nominal' => null, 'percen' => null, 'note' => null];
        }

        return $discon;
    }
}

if (! function_exists('getTotalPODisconPerPO')) {
    function getTotalPODisconPerPO($code, $type)
    {
        if ($type=="PO") {
            $PO = OutstandingDiscon::query()
                ->select(DB::raw('sum(discon_nominal) as total_po'))
                ->where('purchase_order_code', $code)
                ->where('is_active', 1)
                ->first();
            $total  = (!empty($PO)) ? $PO->total_po : 0;
        } else {
            $SO = OutstandingDiscon::query()
                ->select(DB::raw('sum(discon_nominal) as total_so'))
                ->where('service_order_code', $code)
                ->where('is_active', 1)
                ->first();
            $total  = (!empty($SO)) ? $SO->total_so : 0;
        }

        return $total;
    }
}

if (! function_exists('getTotalOutstanding')) {
    function getTotalOutstanding($code, $type)
    {
        $outstanding = 0;
        $nominal     = getTotalPO($code, $type);
        $payment     = getTotalPayment($code, $type);
        if ($payment>=$nominal) {
            $outstanding = 0;
        } else {
            $outstanding = $nominal - $payment;
        }
        return $outstanding;
    }
}

if (! function_exists('getTotalPayment')) {
    function getTotalPayment($code, $type)
    {
        $query = PaymentDetail::query();
        if ($type=="PO") {
            $query = $query->where('purchasing_purchase_order_code', $code);
        } else {
            $query = $query->where('purchasing_service_order_code', $code);
        }
        $payment = $query
                ->select(DB::raw('sum(payment_nominal) as total'))
                ->where('status',PaymentDetail::ACTIVE)
                ->where('is_active',1)
                ->first();
    return (!empty($payment->total)) ? $payment->total : 0;
    }
}

if (! function_exists('getDetailOutstanding')) {
    function getDetailOutstanding($id)
    {
        $outstanding = 0;
        $check_outs  = OutstandingDetail::where('id', $id)->first();
        $payment     = getDetailPayment($id);
        if ($payment>=$check_outs->nominal) {
            $outstanding = 0;
        } else {
            $outstanding = $check_outs->nominal - $payment;
        }

        return $outstanding;
    }
}

if (! function_exists('getDetailPayment')) {
    function getDetailPayment($id)
    {
        $payment = PaymentDetail::query()
            ->select(DB::raw('sum(payment_nominal) as total'))
            ->where('outstanding_detail_id',$id)
            ->where('status',PaymentDetail::ACTIVE)
            ->where('is_active',1)
            ->first();
        return (!empty($payment->total)) ? $payment->total : 0;
    }
}

if (! function_exists('checkOutstandingStatus')) {
    function checkOutstandingStatus($outstanding_id)
    {
        $total_po           = OutstandingDetail::where('outstanding_id', $outstanding_id)->where('is_active', 1)->get();
        $total_outstanding  = OutstandingDetail::where('outstanding_id', $outstanding_id)->where('status', OutstandingDetail::PAY_OUTSTANDING)->where('is_active', 1)->get();
        $total_partly       = OutstandingDetail::where('outstanding_id', $outstanding_id)->where('status', OutstandingDetail::PAY_PARTLY)->where('is_active', 1)->where('is_active', 1)->get();
        $total_paid         = OutstandingDetail::where('outstanding_id', $outstanding_id)->where('status', OutstandingDetail::PAY_PAID)->where('is_active', 1)->where('is_active', 1)->get();
        $total_overpaid     = OutstandingDetail::where('outstanding_id', $outstanding_id)->where('status', OutstandingDetail::PAY_OVERPAID)->where('is_active', 1)->where('is_active', 1)->get();

        if ($total_po->count()==$total_outstanding->count()) {
            $status = Outstanding::PAY_OUTSTANDING;
        } else {
            if ($total_outstanding->count()>0) {
                $status = Outstanding::PAY_PARTLY;
            } elseif ($total_partly->count()>0) {
                $status = Outstanding::PAY_PARTLY;
            } else {
                if ($total_po->count() == ($total_paid->count() + $total_overpaid->count())) {
                    $status = Outstanding::PAY_PAID;
                    if ($total_overpaid->count()>0) {
                        $status = Outstanding::PAY_OVERPAID;
                    }
                } else {
                    $status = Outstanding::PAY_OUTSTANDING;
                }
            }
        }
        return $status;
    }
}
if (! function_exists('checkPaymentOutstandingStatus')) {
    function checkPaymentOutstandingStatus($outstanding_id)
    {
        $outstanding  = OutstandingDetail::query()
                        ->select(DB::raw('sum(nominal) as total'))
                        ->where('outstanding_id', $outstanding_id)
                        ->where('is_active', 1)
                        ->first();
        $payment      = PaymentDetail::query()
                        ->select(DB::raw('sum(payment_nominal) as total'))
                        ->where('outstanding_id', $outstanding_id)
                        ->where('status', PaymentDetail::ACTIVE)
                        ->where('is_active', 1)
                        ->first();
        $status            = Outstanding::PAY_OUTSTANDING;
        $total_outstanding = (!empty($outstanding->total)) ? $outstanding->total : 0;
        $total_payment     = (!empty($payment->total)) ? $payment->total : 0;
        if ($total_payment>0) {
            if ($total_payment == $total_outstanding) {
                $status  = Outstanding::PAY_PAID;
            } elseif ($total_payment < $total_outstanding) {
                $status  = Outstanding::PAY_PARTLY;
            }
        }

        return $status;
    }
}

if (! function_exists('checkPaymentOutstandingDetailStatus')) {
    function checkPaymentOutstandingDetailStatus($outstanding_detail_id)
    {
        $outstanding  = OutstandingDetail::query()
            ->where('id', $outstanding_detail_id)
            ->where('is_active', 1)
            ->first();
        $payment      = PaymentDetail::query()
                        ->select(DB::raw('sum(payment_nominal) as total'))
                        ->where('outstanding_detail_id', $outstanding_detail_id)
                        ->where('status', PaymentDetail::ACTIVE)
                        ->where('is_active', 1)
                        ->first();
        $status            = OutstandingDetail::PAY_OUTSTANDING;
        $total_outstanding = (!empty($outstanding)) ? $outstanding->nominal : 0;
        $total_payment     = (!empty($payment->total)) ? $payment->total : 0;
        if ($total_payment>0) {
            if ($total_payment == $total_outstanding) {
                $status  = OutstandingDetail::PAY_PAID;
            } elseif ($total_payment < $total_outstanding) {
                $status  = OutstandingDetail::PAY_PARTLY;
            }
        }

        return $status;
    }
}

if (! function_exists('getPODetail')) {
    function getPODetail($po_type, $id)
    {
        $query = OutstandingDetail::query()
            ->where('id',$id)
            ->first();

        $data = [
            'po_code' => null,
            'po_nominal' => null,
            'po_due_date' => null,
            'outstanding_status' => null,
        ];

        if (!empty($query)) {
            $data = [
                'po_code' => ($po_type=='PO') ? $query->purchasing_purchase_order_code : $query->purchasing_service_order_code,
                'po_nominal' => $query->nominal,
                'po_due_date' => $query->due_date,
                'outstanding_status' => $query->status,
            ];

        }
        return $data;
    }
}

if (! function_exists('getOutstandingDetail')) {
    function getOutstandingDetail($payment_code, $outstanding_detail_id, $outstanding_nominal, $status = 1)
    {
        $data = [];
        $paymentDetail = PaymentDetail::query()
            ->where('payment_code',$payment_code)
            ->where('outstanding_detail_id', $outstanding_detail_id)
            ->where('is_active', 1)
            ->where('status', PaymentDetail::ACTIVE)
            ->first();

        $outstanding = getDetailOutstanding($outstanding_detail_id);
        $payment     = getDetailPayment($outstanding_detail_id);

        if (!empty($paymentDetail)) {
            $data['pay_nominal'] = $paymentDetail->payment_nominal;
            $data['pay_checked'] = 1;
            $data['outstanding'] = $outstanding+$paymentDetail->payment_nominal;
            $data['payment']     = $payment;

            if ($outstanding_nominal<=$payment) {
                $data['pay_status'] = 3;
                $data['pay_border'] = 'border-success';
                $data['pay_color'] = '#38c172';
                $data['pay_icon'] = 'fas fa-check';
            } else {
                $data['pay_status'] = 2;
                $data['pay_border'] = 'border-warning';
                $data['pay_color'] = '#cccc00';
                $data['pay_icon'] = 'fas fa-tasks';
            }
        } else {
            $data['outstanding'] = $outstanding;
            $data['payment']     = $payment;
            $data['pay_nominal'] = null;
            $data['pay_checked'] = 0;
            $data['pay_status'] = $status;
            $data['pay_border'] = '';
            $data['pay_color'] = '';
            $data['pay_icon'] = '';
        }

        return $data;
    }
}

if (! function_exists('getOutstandingDueDate')) {
    function getOutstandingDueDate($id)
    {
        $query = OutstandingDetail::query()
            ->where('outstanding_id',$id)
            ->whereIn('status', [OutstandingDetail::PAY_OUTSTANDING, OutstandingDetail::PAY_PARTLY])
            ->orderBy('due_date', 'ASC')
            ->first();

        return (!empty($query)) ? $query['due_date'] : null;
    }
}

if (! function_exists('getSupplierSaldo')) {
    function getSupplierSaldo($supplier_code, $payment_code = null)
    {
        $total_used = 0;
        $query = SupplierSaldo::query();

        if (!empty($payment_code)) {
            $query = $query->where('payment_code', '!=', $payment_code);
            $used  = SupplierSaldo::query()
                    ->where('payment_code', $payment_code)
                    ->where('transaction',SupplierSaldo::USEDPAYMENT)
                    ->where('status',SupplierSaldo::DEBIT)
                    ->where('is_active', 1)
                    ->first();
            $total_used = (!empty($used)) ? $used->nominal : 0;
        }

        $data  = $query
            ->select(DB::raw('sum(nominal_active) as total_saldo'))
            ->where('supplier_code',$supplier_code)
            ->where('transaction',SupplierSaldo::OVERPAYMENT)
            ->where('status',SupplierSaldo::CREDIT)
            ->where('is_active', 1)
            ->first();

        $total_saldo = ($data->total_saldo>0) ? $data->total_saldo : 0;
        return $total_saldo + $total_used;
    }
}

if (! function_exists('getSupplierSaldoUsed')) {
    function getSupplierSaldoUsed($supplier_code, $payment_code)
    {
        $saldo_used = SupplierSaldoUsed::with('parent')
            ->select(DB::raw('sum(nominal) as total_saldo'))
            ->WhereHas('used', function ($query) use ($supplier_code, $payment_code) {
                $query->where('supplier_code', $supplier_code)
                      ->where('payment_code', $payment_code);
            })
            ->where('is_active', 1)
            ->first();
        return ($saldo_used->total_saldo>0) ? $saldo_used->total_saldo : 0;
    }
}



// if (! function_exists('getDataPOTermin')) {
//     function getDataPOTermin($id, $terminModel)
//     {
//         if ($terminModel=="SO") {
//             $query = ServiceOrderTermin::query();
//         } else {
//             $query = PurchaseOrderTermin::query();
//         }

//         $data = $query
//                 ->where('id',$id)
//                 ->where('is_active', 1)
//                 ->first();

//         if (!empty($data)) {
//             $setData = [
//                 'po_code' => ($terminModel=="SO") ? $data->purchasing_service_order_code : $data->purchasing_purchase_order_code,
//                 'po_nominal' => $data->nominal,
//                 'po_due_date' => $data->due_date,
//             ];
//         } else {
//             $setData = [
//                 'po_code' => null,
//                 'po_nominal' => null,
//                 'po_due_date' => null,
//             ];
//         }

//         return $setData;
//     }
// }


