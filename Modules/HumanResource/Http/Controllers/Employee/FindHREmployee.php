<?php

namespace Modules\HumanResource\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\HREmployee;
use Modules\HumanResource\Transformers\Employee\FindHREmployeeResource;

class FindHREmployee extends Controller
{
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $param = $request->keyword;
        $employees = HREmployee::query()
            ->where('company_id', 1)
            ->where('is_active', 1)
            ->where(function ($query) use ($param) {
                $query->where('nik', 'LIKE', '%' . $param . '%')
                ->orwhere('name', 'LIKE', '%' . $param . '%');
            })
            ->orderby('name', 'ASC')
            ->take(15)
            ->get();

        return FindHREmployeeResource::collection($employees);
    }
}
