<?php

namespace Modules\General\Http\Controllers\KPI\Detail;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockAdjustmentItem;

class AjaxGetKPITest extends Controller
{
    public function __invoke(Request $request)
    {
        $item_code = "ATK0004";
        $month   = 2;
        $year    = 2020;
        //return $this->checkRowItem('SA07200008', '<>', 1);
        //return getClosingStockStatus();
       // return getItemStockClosing($item_code,$month, $year);
        //return getFirstStock($month, $year, 'CS');
        //return getItemTransaction($month, $year);
        return getItemStockClosing($item_code, $month, $year);
        //return getKPIWarehouseGoodsRequest();
    }

    public function checkRowItem($code, $operation, $approvals_status)
    {
        $query = StockAdjustmentItem::query();

        if ($approvals_status!="all") {
            $query = $query->where('approvals_status', $operation, $approvals_status);
        }

        $data = $query
            ->where('is_active',1)
            ->where('stock_adjustment_code',$code)
            ->get();
        return $data->count();
    }
}
