<?php

namespace Modules\General\Http\Controllers\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Transformers\Log\LogActivityResource;
use Spatie\Activitylog\Models\Activity;

class AjaxGetLogActivityExcel extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $query = Activity::query();

        // Filter Time
        switch ($request->filterByTime) {
            case "date":
                $query = $query->whereDate('created_at', $request->filterTime);
                break;
            case "month":
                $query = $query->whereYear('created_at', substr($request->filterTime,0,4))->whereMonth('created_at', substr($request->filterTime,5,2));
                break;
            case "year":
                $query = $query->whereYear('created_at', $request->filterTime);
                break;
            default:
                $query = $query;
                break;
        }

        if ($request->filterCauser) {
            $query = $query->where('causer_id', $request->filterCauser);
        }

        if ($request->filterStatus) {
            $query = $query->where('description', $request->filterStatus);
        }

        $items = $query
            ->where('log_name', 'LIKE', '%' . $request->keyword . '%')
            ->get();

        return LogActivityResource::collection($items);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'code';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
