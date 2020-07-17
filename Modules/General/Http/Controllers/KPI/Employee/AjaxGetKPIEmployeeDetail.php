<?php

namespace Modules\General\Http\Controllers\KPI\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiEmployee;
use Modules\General\Transformers\KPI\Employee\KPIEmployeeResource;

class AjaxGetKPIEmployeeDetail extends Controller
{
    public function __invoke(Request $request)
    {
        $filterDate = (!empty($request->date)) ? $request->date : date('Y-m-d');
        $data = KpiEmployee::query()
            ->where('id', $request->code)
            ->where('is_active',1)
            ->first();

        if (!empty($data)) {
            return New KPIEmployeeResource($data);
        } else {
            return "404";
        }
    }
}
