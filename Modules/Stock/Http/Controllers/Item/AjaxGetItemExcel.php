<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\ItemResource;

class AjaxGetItemExcel extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $query = Item::with('itemCategory');

        if (!empty($request->filter_category)) {
            $query = $query->where('item_category_id', $request->filter_category);
        }

        if (!empty($request->filter_unit)) {
            $filter_unit = $request->filter_unit;
            $query = $query->whereHas('unitConversion', function ($query_filter) use ($filter_unit) {
                $query_filter->where('unit_id', $filter_unit);
            });
        }

        if (!empty($request->filter_brand)) {
            $query = $query->where('item_brand_id', $request->filter_brand);
        }

        if (!empty($request->filter_type)) {
            $query = $query->where('type', $request->filter_type);
        }

        if (!empty($request->filter_size)) {
            $query = $query->where('size', $request->filter_size);
        }

        if (!empty($request->filter_color)) {
            $query = $query->where('color', $request->filter_color);
        }

        if (!empty($request->filter_status_stock)) {
            if ($request->filter_status_stock==2) {
                $query = $query->where('status_stock', 0);
            } else {
                $query = $query->where('status_stock', $request->filter_status_stock);
            }

        }

        $items = $query
            ->where('info', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->get();

        return ItemResource::collection($items);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'updated_at';
            //: $this->orderBy = 'stock_app_old_id';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            // ? $this->sortBy = 'ASC'
            // : $this->sortBy = 'DESC';
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
