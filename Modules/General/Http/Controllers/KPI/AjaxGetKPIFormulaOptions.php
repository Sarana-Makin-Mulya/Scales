<?php

namespace Modules\General\Http\Controllers\KPI;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiFormula;
use Modules\General\Transformers\KPI\KpiFormulaResource;

class AjaxGetKPIFormulaOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $query = KpiFormula::query();
        $items = $query
            ->select('*')
            ->addSelect(DB::raw("'$request->id' as kpi_employee_id"))
            ->get();

        return KpiFormulaResource::collection($items);
    }
}
