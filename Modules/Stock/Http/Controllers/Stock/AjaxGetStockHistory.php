<?php

namespace Modules\Stock\Http\Controllers\Stock;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockClosing;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Transformers\Stock\StockHistoryResource;

class AjaxGetStockHistory extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $query = StockTransaction::query();

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
                $query = $query->where('ic_goods_return_id', '<>', '');
                break;
            default:
                $query = $query;
                break;
        }

        // Filter Time
        switch ($request->filterByTime) {
            case "date":
                 $query = $query->whereDate('transaction_date', $request->filterTime);
                break;
            case "month":
                $query = $query->whereYear('transaction_date', substr($request->filterTime,0,4))->whereMonth('transaction_date', substr($request->filterTime,5,2));
                break;
            case "year":
                $query = $query->whereYear('transaction_date', $request->filterTime);
                break;
            default:
                $query = $query;
                break;
        }

        $transaction = $query
            ->whereHas('item', function ($query) use ($request) {
                $query->where('info', 'LIKE', '%' . $request->keyword . '%');
            })
            ->where('entry_status', '<>', StockTransaction::STOCK_SUMMARY)
            ->where('status', '<>', StockTransaction::STATUS_CANCEL)
            ->where('ic_goods_borrow_item_id', 0)
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
