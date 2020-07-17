<?php

namespace Modules\HumanResource\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\HREmployee;
use Modules\HumanResource\Entities\HREmployeeAuthorizationDetail;
use Modules\HumanResource\Transformers\Employee\FindHREmployeeResource;
use Modules\HumanResource\Transformers\Employee\HREmployeeOptionsResource;

class AjaxGetHREmployeeOptions extends Controller
{

    public function __invoke(Request $request)
    {
        if ($request->act=="find-employee") {
            $employees = HREmployee::query()
                ->where('company_id', 1)
                ->where('is_active', 1)
                ->orderBy('name', 'ASC')
                ->take(15)
                ->get();
            return FindHREmployeeResource::collection($employees);
        } else {
            $query = HREmployee::query();
            if (!empty($request->department_id)) {
                if ($request->department_id>0) {
                    $query = $query->where('department_id', $request->department_id);
                } elseif ($request->department_id=='KPI') {
                        $query = $query->whereIn('department_id', ['16', '23', '30']);
                } else {
                    $authorization = getHREmployeeAuthorization($request->department_id);
                    $query = $query->whereIn('nik', $authorization);
                }
            }
            $employees = $query
                ->where('company_id', 1)
                ->where('is_active', 1)
                ->orderBy('name', 'ASC')
                ->get();

            return HREmployeeOptionsResource::collection($employees);
        }
    }
}
