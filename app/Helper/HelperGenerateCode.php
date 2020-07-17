<?php

use Modules\PublicWarehouse\Entities\DeliveryOrder;
use Modules\PublicWarehouse\Entities\GoodsBorrow;
use Modules\PublicWarehouse\Entities\GoodsRepairment;
use Modules\PublicWarehouse\Entities\GoodsRequest;
use Modules\PublicWarehouse\Entities\GoodsReturn;
use Modules\PurchaseOrder\Entities\JunkItem;
use Modules\PurchaseOrder\Entities\JunkItemBuyer;
use Modules\PurchaseOrder\Entities\JunkItemRequest;
use Modules\PurchaseOrder\Entities\Payment;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Entities\PurchaseRequest;
use Modules\PurchaseOrder\Entities\ServiceOrder;
use Modules\PurchaseOrder\Entities\ServiceRequest;
use Modules\Stock\Entities\StockAdjustment;

// GENERATE CODE GOODS REQUEST
if (! function_exists('generateCodeGoodsRequest')) {
    function generateCodeGoodsRequest()
    {
        $last_row = GoodsRequest::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeGoodsRequest($last_row);
    }
}

if (! function_exists('checkCodeGoodsRequest')) {
    function checkCodeGoodsRequest($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("GR".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (GoodsRequest::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeGoodsRequest($number);
        }

        return $code;
    }
}

// GENERATE CODE PURCHASE REQUEST
if (! function_exists('generateCodePurchaseRequest')) {
    function generateCodePurchaseRequest()
    {
        $last_row = PurchaseRequest::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodePurchaseRequest($last_row);
    }
}

if (! function_exists('checkCodePurchaseRequest')) {
    function checkCodePurchaseRequest($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("PR".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (PurchaseRequest::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodePurchaseRequest($number);
        }

        return $code;
    }
}

// GENERATE CODE JUNK ITEM
if (! function_exists('generateCodeJunkItem')) {
    function generateCodeJunkItem()
    {
        $last_row = JunkItem::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeJunkItem($last_row);
    }
}

if (! function_exists('checkCodeJunkItem')) {
    function checkCodeJunkItem($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("JI".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (JunkItem::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeJunkItem($number);
        }

        return $code;
    }
}

// GENERATE CODE JUNK ITEM BUYER
if (! function_exists('generateCodeJunkItemBuyer')) {
    function generateCodeJunkItemBuyer()
    {
        $last_row = JunkItemBuyer::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeJunkItemBuyer($last_row);
    }
}

if (! function_exists('checkCodeJunkItemBuyer')) {
    function checkCodeJunkItemBuyer($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("JB".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (JunkItemBuyer::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeJunkItemBuyer($number);
        }

        return $code;
    }
}

// GENERATE CODE JUNK ITEM REQUEST
if (! function_exists('generateCodeJunkItemRequest')) {
    function generateCodeJunkItemRequest()
    {
        $last_row = JunkItemRequest::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeJunkItemRequest($last_row);
    }
}

if (! function_exists('checkCodeJunkItemRequest')) {
    function checkCodeJunkItemRequest($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("PJ".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (JunkItemRequest::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeJunkItemRequest($number);
        }

        return $code;
    }
}

// GENERATE CODE PAYMENT
if (! function_exists('generateCodePayment')) {
    function generateCodePayment()
    {
        $last_row = Payment::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodePayment($last_row);
    }
}

if (! function_exists('checkCodePayment')) {
    function checkCodePayment($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("PP".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (Payment::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodePayment($number);
        }

        return $code;
    }
}

// GENERATE CODE SERVICE REQUEST
if (! function_exists('generateCodeServiceRequest')) {
    function generateCodeServiceRequest()
    {
        $last_row = ServiceRequest::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeServiceRequest($last_row);
    }
}

if (! function_exists('checkCodeServiceRequest')) {
    function checkCodeServiceRequest($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("SR".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (ServiceRequest::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeServiceRequest($number);
        }

        return $code;
    }
}

// GENERATE CODE SERVICE ORDER
if (! function_exists('generateCodeServiceOrder')) {
    function generateCodeServiceOrder()
    {
        $last_row = ServiceOrder::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeServiceOrder($last_row);
    }
}

if (! function_exists('checkCodeServiceOrder')) {
    function checkCodeServiceOrder($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("SO".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (ServiceOrder::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeServiceOrder($number);
        }

        return $code;
    }
}

// GENERATE CODE PURCHASE ORDER
if (! function_exists('generateCodePurchaseOrder')) {
    function generateCodePurchaseOrder()
    {
        $last_row = PurchaseOrder::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodePurchaseOrder($last_row);
    }
}

if (! function_exists('checkCodePurchaseOrder')) {
    function checkCodePurchaseOrder($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("PO".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (PurchaseOrder::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodePurchaseOrder($number);
        }

        return $code;
    }
}

// GENERATE DELIVERY ORDER
if (! function_exists('generateCodeDeliveryOrder')) {
    function generateCodeDeliveryOrder()
    {
        $last_row = DeliveryOrder::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeDeliveryOrder($last_row);
    }
}

if (! function_exists('checkCodeDeliveryOrder')) {
    function checkCodeDeliveryOrder($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("DO".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (DeliveryOrder::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeDeliveryOrder($number);
        }

        return $code;
    }
}

// GENERATE GOODS RETURN
if (! function_exists('generateCodeGoodsReturn')) {
    function generateCodeGoodsReturn()
    {
        $last_row = GoodsReturn::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeGoodsReturn($last_row);
    }
}

if (! function_exists('checkCodeGoodsReturn')) {
    function checkCodeGoodsReturn($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("RI".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (GoodsReturn::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeGoodsReturn($number);
        }

        return $code;
    }
}

// GENERATE GOODS BORROW
if (! function_exists('generateCodeGoodsBorrow')) {
    function generateCodeGoodsBorrow()
    {
        $last_row = GoodsBorrow::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeGoodsBorrow($last_row);
    }
}

if (! function_exists('checkCodeGoodsBorrow')) {
    function checkCodeGoodsBorrow($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("GB".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (GoodsBorrow::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeGoodsBorrow($number);
        }

        return $code;
    }
}

// GENERATE STOCK ADJUSTMENT
if (! function_exists('generateCodeAdjustment')) {
    function generateCodeAdjustment()
    {
        $last_row = StockAdjustment::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeAdjustment($last_row);
    }
}

if (! function_exists('checkCodeAdjustment')) {
    function checkCodeAdjustment($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("SA".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (StockAdjustment::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeAdjustment($number);
        }

        return $code;
    }
}

// GENERATE GOODS REPAIRMENT
if (! function_exists('generateCodeGoodsRepairment')) {
    function generateCodeGoodsRepairment()
    {
        $last_row = GoodsRepairment::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return checkCodeRepairment($last_row);
    }
}

if (! function_exists('checkCodeRepairment')) {
    function checkCodeRepairment($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("RP".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (GoodsRepairment::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return checkCodeRepairment($number);
        }

        return $code;
    }
}


