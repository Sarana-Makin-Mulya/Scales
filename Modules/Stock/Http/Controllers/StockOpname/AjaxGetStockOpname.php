<?php

namespace Modules\Stock\Http\Controllers\StockOpname;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockOpname;
use Modules\Stock\Entities\StockOpnameGroup;
use Modules\Stock\Transformers\StockOpname\StockOpnameResource;

class AjaxGetStockOpname extends Controller
{
    public function __invoke(Request $request)
    {
       $query = StockOpname::query();

       // Stock Opname Type
       if (empty($request->stockopname_type)) {
            $stockopname_type = 'item';
       } else {
            $stockopname_type = $request->stockopname_type;
       }

       // Stock Opname Group (New or History)
       if ($stockopname_type != "item") {
          $group = StockOpnameGroup::where('type', $stockopname_type)->orderBy('created_at', 'Desc')->first();
          $stock_opname_group_id = (!empty($group)) ? $group->id : 0;
          if ($request->filter_group=="new") {
            $query = $query->where('stock_opname_group_id', $stock_opname_group_id);
          } else {
            $query = $query->where('stock_opname_group_id','!=', $stock_opname_group_id);
          }
       }

       // Stock Status
       if ($request->filter=="all") {
            $query = $query;
       } elseif ($request->filter=="stock_waiting") {
            $query = $query
                ->where('stock_status', 0);
       } elseif ($request->filter=="stock_issue") {
            $query = $query
                ->whereIn('stock_status', [2, 3])
                ->where('quantity_issue','>',0)
                ->whereRaw('quantity_issue <> quantity_adjustment');
       } elseif ($request->filter=="approvals") {
            $query = $query
                ->whereIn('stock_status', [2, 3])
                ->where('quantity_issue','>',0)
                ->where('quantity_adjustment','>',0);
       } else {
            $query = $query->where('stock_status', 1);
       }


        $stockOpname = $query
            ->whereHas('item', function ($query) use ($request) {
                $query->where('info', 'LIKE', '%' . $request->keyword . '%');
            })
            ->where('stockopname_type', $stockopname_type)
            ->orderBy('updated_at', 'Desc')
            ->paginate($request->per_page);

        return StockOpnameResource::collection($stockOpname);
    }
}
