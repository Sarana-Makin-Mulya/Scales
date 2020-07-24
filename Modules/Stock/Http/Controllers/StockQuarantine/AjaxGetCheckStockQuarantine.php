<?php

namespace Modules\Stock\Http\Controllers\StockQuarantine;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\StockQuarantine;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Transformers\StockQuarantine\CheckStockQuarantineResource;

class AjaxGetCheckStockQuarantine extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $filterStatus    = $request->filterStatus;
        if ($filterStatus==1) {
            $filter_date = Carbon::now()->subMonth(6);
            //$filter_date = Carbon::now()->subDays(1);
            $filter_date = $filter_date->format('Y-m-d')." 00:00:00";
            // return $filter_date;
            $data = StockTransaction::query()
                ->select('*')
                ->addSelect(DB::raw("'quarantine' as filterStatus"))
                ->whereHas('item', function ($query) use ($request) {
                    $query->where('info', 'LIKE', '%' . $request->keyword . '%');
                })
                ->whereIn('transaction_symbol', ['DO', 'OA'])
                ->where(function ($query) use ($filter_date) {
                    $query->where('transaction_date', '<=', $filter_date)
                        ->where('stock_quarantine_id', null)
                        ->where('stock_category', StockTransaction::SC_ACTIVE)
                        ->orWhere('stock_quarantine_date', '<=', $filter_date)
                        ->where('stock_quarantine_id', '>', 0)
                        ->where('stock_category', StockTransaction::SC_QUARANTINE);
                })
                ->where('stock_current', '>', 0)
                ->orderBy('transaction_date', 'ASC')
                ->paginate($request->per_page);
        } else {
            $query = StockQuarantine::query();
            $data = $query
                ->select('*')
                ->addSelect(DB::raw("'return_to_stock' as filterStatus"))
                ->whereHas('item', function ($query) use ($request) {
                    $query->where('info', 'LIKE', '%' . $request->keyword . '%');
                })
                ->where('action', 1)
                ->orderBy($this->orderBy, $this->sortBy)
                ->paginate($request->per_page);
        }

        return CheckStockQuarantineResource::collection($data);
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
