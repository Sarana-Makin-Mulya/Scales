<?php

namespace Modules\General\Http\Controllers\KPI\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiEmployee;
use Modules\General\Entities\KpiEmployeeResult;
use Modules\General\Transformers\KPI\Employee\KPIEmployeeResultResource;

class AjaxGetKPIEmployeeResult extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $items = KpiEmployee::query()
            ->select('*')
            ->addSelect(DB::raw("'$request->filterDate' as filterDate"))
            ->where('is_active',1)
            ->paginate($request->per_page);

        return KPIEmployeeResultResource::collection($items);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'employee_nik';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
