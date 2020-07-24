<?php

namespace Modules\Stock\Http\Controllers\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\Stock\StockIndexResource;

class AjaxGetStockIndex extends Controller
{
    protected $orderBy;
    protected $sortBy = 'ASC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $items = Item::with('itemCategory')
            ->where('info', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return StockIndexResource::collection($items);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'code';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'ASC'
            : $this->sortBy = 'DESC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
