<?php
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\Bank;
use Modules\General\Entities\BankAccount;
use Modules\General\Entities\Notification;
use Modules\General\Entities\NotificationRead;
use Modules\PublicWarehouse\Entities\GoodsRequestReturn;
use Modules\PublicWarehouse\Entities\Weighing;
use Modules\PublicWarehouse\Entities\WeighingCategory;
use Modules\PurchaseOrder\Entities\JunkItem;
use Modules\PurchaseOrder\Entities\JunkItemPrice;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Entities\ServiceRequest;
use Modules\Stock\Entities\StockAdjustment;

if (! function_exists('getWeighingCategoryName')) {
    function getWeighingCategoryName($id)
    {
        $data  = WeighingCategory::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getNettoWeight')) {
    function getNettoWeight($id)
    {
        $netto_weight = 0;
        $data  = Weighing::where('id',$id)->first();
        if (!empty($data)) {
            $netto_weight = ($data->first_weight - $data->second_weight);
            if ($netto_weight<0) {
                $netto_weight = $netto_weight * -1;
            }
            if ($data->tolerance_weight>0) {
                $netto_weight = $netto_weight - $data->tolerance_weight;
            }
        }
        return $netto_weight;
    }
}

if (! function_exists('getBankAccountDetail')) {
    function getBankAccountDetail($id)
    {
        $data  = BankAccount::where('id',$id)->first();
        return !empty($data) ? getBankName($data->bank_id)."(".getCurrencySymbol($data->currency_id).") : ".$data->account_number." a.n ".$data->account_name."": '';
    }
}

if (! function_exists('setMoney')) {
    function setMoney($value)
    {
        return number_format($value,2,',','.');
    }
}

if (! function_exists('changeDetection')) {
    function changeDetection($model)
    {
        $change = $model->getChanges();
        return (count($change)>0) ? true : false;
    }
}

if (! function_exists('getTotalDay')) {
    function getTotalDay($month, $year)
    {
        switch ($month) {
            case 1:
                $day = 31;
                break;
            case 2:
                $day = ($year%4==0) ? 29 : 28;
                break;
            case 3:
                $day = 31;
                break;
            case 4:
                $day = 30;
                break;
            case 5:
                $day = 31;
                break;
            case 6:
                $day = 30;
                break;
            case 7:
                $day = 31;
                break;
            case 8:
                $day = 31;
                break;
            case 9:
                $day = 30;
                break;
            case 10:
                $day = 31;
                break;
            case 11:
                $day = 30;
                break;
            case 12:
                $day = 31;
                break;
            default:
                $day = 0;
                break;
        }
        return $day;
    }
}

if (! function_exists('getMonthName')) {
    function getMonthName($month)
    {
        switch ($month) {
            case 1:
                $text = "Januari";
                break;
            case 2:
                $text = "Februari";
                break;
            case 3:
                $text = "Maret";
                break;
            case 4:
                $text = "April";
                break;
            case 5:
                $text = "Mei";
                break;
            case 6:
                $text = "Juni";
                break;
            case 7:
                $text = "Juli";
                break;
            case 8:
                $text = "Agustus";
                break;
            case 9:
                $text = "September";
                break;
            case 10:
                $text = "Oktober";
                break;
            case 11:
                $text = "November";
                break;
            case 12:
                $text = "Desember";
                break;
            default:
                $text = "Unname";
                break;
        }
        return $text;
    }
}

if (! function_exists('trimed')) {
    function trimed($text)
    {
        $text = trim($text);
        while (strpos($text, '  ')) {
            $text = str_replace('  ', ' ', $text);
        }
        return $text;
    }
}



if (! function_exists('createNotification')) {
    function createNotification($data)
    {
        Notification::create([
            'notification_group_id' => $data['notification_group_id'],
            'type' => $data['type'],
            'transaction_type' => $data['transaction_type'],
            'transaction_code' => $data['transaction_code'],
            'transaction_id' => $data['transaction_id'],
            'from' => $data['from'],
            'to' => $data['to'],
            'message' => $data['message'],
            'url' => $data['url'],
            'status' => $data['status'],
            'issued_by' => $data['issued_by'],
        ]);
    }
}

if (! function_exists('getNotificationStatus')) {
    function getNotificationStatus($id)
    {
        $read_by = getAuthEmployeeNik(session('user_level'));
        $data  = NotificationRead::query()
            ->where('notification_id',$id)
            ->where('read_by', $read_by)
            ->first();
        return !empty($data) ? 1 : 0;
    }
}

if (! function_exists('getServiceCategoryType')) {
    function getServiceCategoryType($id)
    {
        $model = new Modules\PurchaseOrder\Entities\ServiceCategory;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->type : '';
    }
}

if (! function_exists('getServiceDescription')) {
    function getServiceDescription($code)
    {
        $data  = ServiceRequest::where('code',$code)->first();
        $type  = getServiceCategoryType($data->service_category_id);
        $category = getServiceCategoryName($data->service_category_id);
        switch ($type) {
            case "item":
                $desc = $category." : ".getItemName($data->item_code);
                break;
            case "machine":
                $desc = $category." : ".getMachineDetail($data->machine_id);
                break;
            default:
                $desc = $category;
                break;
        }

        return $desc;
    }
}

if (! function_exists('getServiceCategoryName')) {
    function getServiceCategoryName($id)
    {
        $model = new Modules\PurchaseOrder\Entities\ServiceCategory;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getCurrencyName')) {
    function getCurrencyName($id)
    {
        $model = new Modules\General\Entities\Currency();
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getCurrenyPurchaseOrder')) {
    function getCurrenyPurchaseOrder($code)
    {
        $data  = PurchaseOrder::where('code', $code)->first();
        return ($data->Outstanding->count()>0) ? getCurrencySymbol($data->Outstanding[0]->currency) : "xxxx->Rp";
    }
}

if (! function_exists('getBankName')) {
    function getBankName($id)
    {
        $data  = Bank::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getCurrencySymbol')) {
    function getCurrencySymbol($id)
    {
        $model = new Modules\General\Entities\Currency();
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->symbol : '';
    }
}

if (! function_exists('getGoodsReleaseStatusName')) {
    function getGoodsReleaseStatusName($id)
    {
        $model = new Modules\PublicWarehouse\Entities\GoodsReleaseStatus;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getGoodsRequestReturn')) {
    function getGoodsRequestReturn($id)
    {
        $data  = GoodsRequestReturn::select(DB::raw('sum(quantity) as total'))->where('ic_goods_request_item_id',$id)->first();
        return !empty($data) ? (($data->total==null) ? 0 : abs($data->total)) : 0;
    }
}

if (! function_exists('getGoodsRequestItemOutReturn')) {
    function getGoodsRequestItemOutReturn($id)
    {
        $data  = GoodsRequestReturn::select(DB::raw('sum(quantity) as total'))->where('ic_goods_request_item_out_id',$id)->first();
        return !empty($data) ? (($data->total==null) ? 0 : abs($data->total)) : 0;
    }
}

if (! function_exists('getStockAdjustmentCategoryName')) {
    function getStockAdjustmentCategoryName($id)
    {
        $model = new Modules\Stock\Entities\StockAdjustmentCategory;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getStockAdjustmentCategoryStock')) {
    function getStockAdjustmentCategoryStock($id)
    {
        $model = new Modules\Stock\Entities\StockAdjustmentCategory;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->stock : '';
    }
}


if (! function_exists('getStockAdjustmentType')) {
    function getStockAdjustmentType($code)
    {
        $data  = StockAdjustment::where('code',$code)->first();
        return !empty($data) ? $data->type : '';
    }
}

if (! function_exists('getStockAdjustmentCategoryTypeValue')) {
    function getStockAdjustmentCategoryTypeValue($id)
    {
        $model = new Modules\Stock\Entities\StockAdjustmentCategory;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->type_value : '';
    }
}

if (! function_exists('getCategoryName')) {
    function getCategoryName($id)
    {
        $model = new Modules\Stock\Entities\ItemCategory;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getSupplierName')) {
    function getSupplierName($code)
    {
        $model = new Modules\Supplier\Entities\Supplier;
        $data  = $model::where('code',$code)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getUnitName')) {
    function getUnitName($id)
    {
        $model = new Modules\General\Entities\Unit;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->symbol : '';
    }
}

if (! function_exists('getUnitConversionName')) {
    function getUnitConversionName($id)
    {
        $model = new Modules\Stock\Entities\ItemUnitConversion;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? getUnitName($data->unit_id) : '';
    }
}

if (! function_exists('getBrandName')) {
    function getBrandName($id)
    {
        $model = new Modules\Stock\Entities\Brand;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getMachineDetail')) {
    function getMachineDetail($id)
    {
        $model = new Modules\General\Entities\Machine;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name." ".$data->serial_number." ".$data->capacity  : '';
    }
}

if (! function_exists('getItemDetail')) {
    function getItemDetail($code)
    {
        $model = new Modules\Stock\Entities\Item;
        $data  = $model::where('code',$code)->first();
        return !empty($data) ? trimed($data->code." - ".$data->detail) : '';
    }
}

if (! function_exists('getItemPreview')) {
    function getItemPreview($code)
    {
        $itemName = getItemDetail($code);
        $length   = strlen($itemName);
        if ($length>50) {
            $itemName = substr($itemName, 0, 50)."...";
        }

        return $itemName;
    }
}

if (! function_exists('getItemName')) {
    function getItemName($code)
    {
        $model = new Modules\Stock\Entities\Item;
        $data  = $model::where('code',$code)->first();
        return !empty($data) ? $data->detail : '';
    }
}

if (! function_exists('getJunkItemPriceDesc')) {
    function getJunkItemPriceDesc($id)
    {
        $data  = JunkItemPrice::where('id',$id)->first();
        if (!empty($data)) {
            $price_type = ($data->type==1) ? 'Harga Normal' : 'Harga Diskon';
            $price_desc = getCurrencySymbol($data->currency_id).". ".number_format($data->price,2,',','.')." / ".getUnitName($data->unit_id)." (".$price_type.")";
        } else {
            $price_desc = null;
        }

        return  $price_desc;
    }
}

if (! function_exists('getJunkItemName')) {
    function getJunkItemName($code)
    {
        $data  = JunkItem::where('code',$code)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getJunkItemDesc')) {
    function getJunkItemDesc($code)
    {
        $data  = JunkItem::where('code',$code)->first();
        return !empty($data) ? $data->code." - ".$data->name : '';
    }
}

if (! function_exists('getItemCategory')) {
    function getItemCategory($code)
    {
        $model = new Modules\Stock\Entities\Item;
        $data  = $model::where('code',$code)->first();
        return !empty($data) ? getCategoryName($data->item_category_id)  : '';
    }
}
