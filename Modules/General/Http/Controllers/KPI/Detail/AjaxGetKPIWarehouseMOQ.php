<?php

namespace Modules\General\Http\Controllers\KPI\Detail;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Transformers\KPI\Detail\KPIWarehouseMOQResource;
use Modules\Stock\Entities\Item;

class AjaxGetKPIWarehouseMOQ extends Controller
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

        if ($month==date('m') && $year==date('Y')) {
            $data   = Item::query()
                        ->where('code', 'LIKE', '%' . $keyword . '%')
                        ->where('min_stock', '>', 0)
                        ->where('current_stock', 0)
                        ->where('is_active', 1)
                        ->paginate($request->per_page);
            return KPIWarehouseMOQResource::collection($data);
        } else {
            return 404;
        }
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'code';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

    }
}
