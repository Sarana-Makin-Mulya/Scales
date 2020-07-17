<?php

namespace Modules\General\Http\Controllers\KPI;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiCategory;
use Modules\General\Transformers\KPI\KpiFormulaResource;

class AjaxGetKPICategoryOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $query = KpiCategory::query();
        $items = $query
            ->get();

        return KpiCategoryResource::collection($items);
    }
}
