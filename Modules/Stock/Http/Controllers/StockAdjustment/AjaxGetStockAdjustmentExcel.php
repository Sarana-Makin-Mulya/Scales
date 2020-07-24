<?php

namespace Modules\Stock\Http\Controllers\StockAdjustment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockAdjustment;
use Modules\Stock\Transformers\StockAdjustment\StockAdjustmentResource;

class AjaxGetStockAdjustmentExcel extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {

        $this->createSortOrder($request);
        $sortBy  = $this->sortBy;
        $keyword = $request->keyword;

        $query = StockAdjustment::query();

         // Filter Status
        if ($request->filter>0) {
            $query = $query->where('status', $request->filter);
        } else {
            $query = $query->whereIn('status', [1, 2, 3]);
        }

        // Filter Time
        switch ($request->filterByTime) {
            case "date":
                if ($request->filterTimeEnd == $request->filterTime) {
                    $query = $query->whereDate('issue_date', $request->filterTime);
                } else {
                    $query = $query->whereBetween('issue_date', [$request->filterTime.' 00:00:00', $request->filterTimeEnd.' 23:59:59']);
                }
                break;
            case "month":
                $query = $query->whereYear('issue_date', substr($request->filterTime,0,4))->whereMonth('issue_date', substr($request->filterTime,5,2));
                break;
            case "year":
                $query = $query->whereYear('issue_date', $request->filterTime);
                break;
            default:
                $query = $query;
                break;
        }

        // Search Keyword
        if ($keyword!=null) {
            $query = $query->where(function ($query) use ($keyword) {
                $query->where('code', $keyword)
                    ->orWhereHas('employeeIssuedBy', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%' . $keyword . '%')
                            ->orwhere('nik', 'LIKE', '%' . $keyword . '%');
                    });
            });
        }

         // Order By
         if ($this->orderBy!=null) {
            if ($this->orderBy=='issued_by_name') {
                $query = $query->with(['employeeIssuedBy' => function ($query) use ($sortBy) {
                    $query->orderBy('name', $sortBy);
                }]);
            } else {
                $query = $query->orderBy($this->orderBy, $this->sortBy);
            }
        }

        $data = $query
            ->get();


        return StockAdjustmentResource::collection($data);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'updated_at';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';
    }
}
