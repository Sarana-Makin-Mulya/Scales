<?php

namespace Modules\Stock\Http\Controllers\StockAdjustment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockAdjustment;
use Modules\Stock\Entities\StockAdjustmentItem;
use Modules\Stock\Transformers\StockAdjustment\StockAdjustmentItemResource;
use Modules\Stock\Transformers\StockAdjustment\StockAdjustmentPerItemResource;
use Modules\Stock\Transformers\StockAdjustment\StockAdjustmentResource;

class AjaxGetStockAdjustmentPerItem extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $approvals = ($request->filter_approvals==0 or $request->filter_approvals==null) ? array(1,2,3)  : array($request->filter_approvals);
        if ($request->filter_status==0 or $request->filter_status==null) {
            $status = array(1,2,3);
        } else {
            if ($request->filter_status==1) {
                $status = array(1,2);
            } else {
                $status = array($request->filter_status);
            }
        }

        $this->createSortOrder($request);
        $items = StockAdjustmentItem::query()
            ->whereIn('status',$status)
            ->whereIn('approvals_status',$approvals)
            ->where('is_active', 1)
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

       return StockAdjustmentPerItemResource::collection($items);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'id';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'ASC'
            : '';
    }
}
