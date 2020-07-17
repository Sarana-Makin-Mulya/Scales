<?php

namespace Modules\General\Http\Controllers\KPI\Detail;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Transformers\KPI\Detail\KPIWarehouseGoodsBorrowResource;
use Modules\PublicWarehouse\Entities\GoodsBorrowItem;

class AjaxGetKPIWarehouseGoodsBorrow extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $keyword = $request->keyword;
        $kpi    = getKPIDate($request->filterDate);
        $month  = $kpi['month'];
        $year   = $kpi['year'];
        $date   = $kpi['date'];

        // $borrow = GoodsBorrowItem::with('goodsBorrow')
        // ->select(DB::raw('sum(quantity) as total'))
        // ->whereHas('goodsBorrow', function ($query) use ($month, $year, $date) {
        //     $query->select('target_return_date')
        //         ->whereMonth('target_return_date', $month)
        //         ->whereYear('target_return_date', $year)
        //         ->whereDate('target_return_date', '<=', $date);
        // })
        // ->first();


        $data   = GoodsBorrowItem::with('goodsBorrow')
                    ->whereHas('goodsBorrow', function ($query) use ($month, $year, $date) {
                        $query->where(function ($query) use ($month, $year, $date) {
                            $query->select('target_return_date')
                                ->whereMonth('target_return_date', $month)
                                ->whereYear('target_return_date', $year)
                                ->whereDate('target_return_date', '<=', $date)
                                ->where('is_active', 1);
                        });
                    })
                    ->where(function ($query) use ($keyword) {
                        $query->where('item_code', 'LIKE', '%' . $keyword . '%');
                    })
                    ->where('is_active', 1)
                    ->paginate($request->per_page);

        return KPIWarehouseGoodsBorrowResource::collection($data);
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
