<?php

namespace Modules\HumanResource\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\HREmployee;
use Modules\HumanResource\Transformers\Employee\FindHREmployeeResource;

class AjaxGetHREmployeeDetail extends Controller
{
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $nik = $request->nik;
        $employees = HREmployee::with('identity')
            ->where('nik',$nik)
            ->first();


        if (!empty($employees)) {
            return new FindHREmployeeResource($employees);
        }
    }
}
