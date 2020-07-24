<?php

namespace Modules\Stock\Http\Controllers\StockQuarantine;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\StockQuarantine;
use Modules\Stock\Transformers\StockQuarantine\StockQuarantineResource;

class AjaxGetStockQuarantine extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $filterAction    = $request->filterAction;
        $filterStatus    = $request->filterStatus;
        $filterApprovals = $request->filterApprovals;

        $query = StockQuarantine::query();

        if ($filterStatus!=null) {
            $query = $query->where('status', $filterStatus);
        }

        if ($filterApprovals!=null) {
            $query = $query->where('approvals', $filterApprovals);
        }

        $data = $query
            // ->whereNotIn('id', DB::table('stock_adjustment_items')
            //     ->where('stock_quarantine_id', '>', 0)
            //     ->where('deleted_at', null)
            //     ->pluck('stock_quarantine_id'))
            ->whereHas('item', function ($query) use ($request) {
                $query->where('info', 'LIKE', '%' . $request->keyword . '%');
            })
            ->where('action', $filterAction)
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return StockQuarantineResource::collection($data);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'updated_at';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : '';
    }
}
