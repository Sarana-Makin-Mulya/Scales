<?php

namespace Modules\PublicWarehouse\Http\Controllers\WeighingItem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\WeighingItems;
use Modules\PublicWarehouse\Transformers\Weighing\WeighingItemResource;

class AjaxGetWeighingItem extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $items = WeighingItems::query()
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return WeighingItemResource::collection($items);
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

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
