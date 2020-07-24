<?php

namespace Modules\Stock\Http\Controllers\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\GoodsBorrowItem;
use Modules\PublicWarehouse\Transformers\GoodsBorrow\GoodsBorrowItemResource;
use Modules\PublicWarehouse\Transformers\GoodsBorrow\GoodsBorrowPerItemResource;

class AjaxGetDetailStockBorrowedHistory extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $filterCode     = $request->filterCode;
        $this->createSortOrder($request);
        $query = GoodsBorrowItem::query();

        //filter Item
        if (!empty($filterCode)) {
            $query = $query->where('item_code', $filterCode);
        }

        $transaction = $query
            ->whereHas('items', function ($query) use ($request) {
                $query->where('info', 'LIKE', '%' . $request->keyword . '%');
            })
            ->where('return_status', 0)
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return GoodsBorrowPerItemResource::collection($transaction);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'created_at';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
