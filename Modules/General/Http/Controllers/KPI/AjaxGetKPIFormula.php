<?php

namespace Modules\General\Http\Controllers\KPI;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\KpiFormula;
use Modules\General\Transformers\KPI\KpiFormulaResource;

class AjaxGetKPIFormula extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $keyword = $request->keyword;
        $query = KpiFormula::query();
        $items = $query
            ->where(function ($query) use ($keyword) {
                $query->where('assessment_point', 'LIKE', '%'.$keyword.'%')
                      ->orWhere('unit', 'LIKE', '%'.$keyword.'%')
                      ->orWhere('target', 'LIKE', '%'.$keyword.'%')
                      ->orWhere('formula_a', 'LIKE', '%'.$keyword.'%')
                      ->orWhere('formula_b', 'LIKE', '%'.$keyword.'%');
            })
            ->paginate($request->per_page);

        return KpiFormulaResource::collection($items);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'assessment_point';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        // if ($request->order_by == 'status') {
        //     $this->orderBy = 'is_active';
        // }
    }
}
