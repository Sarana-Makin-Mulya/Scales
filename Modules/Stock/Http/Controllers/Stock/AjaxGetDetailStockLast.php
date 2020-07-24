<?php

namespace Modules\Stock\Http\Controllers\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockClosing;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Transformers\Stock\StockHistoryResource;

class AjaxGetDetailStockLast extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $filterCode     = $request->filterCode;
        $query = StockTransaction::query();


        if (!empty($filterCode)) {
            $query = $query->where('item_code', $filterCode);
        }

        // Filter Transaction Category
        switch ($request->filter) {
            case "goods_request":
                $query = $query->where('ic_goods_request_item_id', '>', 0);
                break;
            case "goods_borrow":
                $query = $query->where('ic_goods_borrow_item_id', '>', 0);
                break;
            case "stock_adjustment":
                $query = $query->where('stock_adjustment_item_id', '>', 0);
                break;
            case "delivery_order":
                $query = $query->where('delivery_order_item_id', '>', 0);
                break;
            case "goods_return":
                $query = $query->where('ic_goods_return_id', '>', 0);
                break;
            default:
                $query = $query;
                break;
        }

        $query_first = $query
            ->whereHas('item', function ($query) use ($request) {
                $query->where('info', 'LIKE', '%' . $request->keyword . '%');
            })
            ->where('entry_status', '<>', StockTransaction::STOCK_SUMMARY)
            ->where('status', '<>', StockTransaction::STATUS_CANCEL)
            ->where('ic_goods_borrow_item_id', 0)
            ->where('delivery_order_item_id', 0);

        $transaction  = $query_first
                ->orWhere(function ($q) use ($filterCode, $request) {
                    $q->where('item_code', $filterCode);
                    $q->where('delivery_order_item_id','>', 0);
                    $q->whereHas('deliveryOrderItem', function ($query) {
                        $query->where('storaged',0);
                    });
                    $q->whereHas('item', function ($query) use ($request) {
                        $query->where('info', 'LIKE', '%' . $request->keyword . '%');
                    });
                    $q->where('entry_status', '<>', StockTransaction::STOCK_SUMMARY);
                    $q->where('status', '<>', StockTransaction::STATUS_CANCEL);
                    $q->where('ic_goods_borrow_item_id', 0);
                })
                ->orderBy($this->orderBy, $this->sortBy)
                ->paginate($request->per_page);

        return StockHistoryResource::collection($transaction);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'transaction_date';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
