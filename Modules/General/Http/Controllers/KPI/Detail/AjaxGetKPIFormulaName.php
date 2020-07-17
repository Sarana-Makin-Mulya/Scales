<?php

namespace Modules\General\Http\Controllers\KPI\Detail;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\KpiFormula;

class AjaxGetKPIFormulaName extends Controller
{
    public function __invoke(Request $request, $code)
    {
        $data = KpiFormula::where('formula_code', $code)->first();
        return (!empty($data)) ? ['data' => $data->assessment_point] : null;
    }
}
