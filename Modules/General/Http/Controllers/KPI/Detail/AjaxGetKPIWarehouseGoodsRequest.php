<?php

namespace Modules\General\Http\Controllers\KPI\Detail;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Transformers\KPI\Detail\KPIWarehouseGoodsRequestResource;
use Modules\PublicWarehouse\Entities\GoodsRequestItems;

class AjaxGetKPIWarehouseGoodsRequest extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $keyword = $request->keyword;
        $date   = getKPIDate($request->filterDate);
        $month  = $date['month'];
        $year   = $date['year'];
        $date   = $date['date'];
        $data   = GoodsRequestItems::with('goodsRequest')
                    ->whereHas('goodsRequest', function ($query) use ($month, $year) {
                        $query->where(function ($query) use ($month, $year) {
                            $query->whereMonth('transaction_date', $month)
                                  ->whereYear('transaction_date', $year)
                                  ->whereRaw('transaction_date < DATE_ADD(transaction_date, INTERVAL 7 DAY)')
                                  ->whereIn('status', [3])
                                  ->where('is_active', 1);
                        })
                        ->orWhere(function ($query) use ($month, $year) {
                            $query->whereMonth('transaction_date', $month)
                                  ->whereYear('transaction_date', $year)
                                  ->whereRaw('transaction_date < DATE_ADD(transaction_date, INTERVAL 7 DAY)')
                                  ->whereIn('status', [1, 2])
                                  ->where('is_active', 1);
                        });
                    })
                    ->where(function ($query) use ($keyword) {
                        $query->where('item_code', 'LIKE', '%' . $keyword . '%');
                    })
                    ->where('is_active', 1)
                    ->whereIn('status', [1, 2, 3, 4 ])
                    ->paginate($request->per_page);

        return KPIWarehouseGoodsRequestResource::collection($data);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'user_group_id';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

    }
}
