<?php

namespace Modules\HumanResource\Http\Controllers\Department;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\HRDepartment;
use Modules\HumanResource\Transformers\Department\HRDepartmentResource;

class AjaxGetHRDepartmentOptions extends Controller
{

    public function __invoke(Request $request)
    {

        $data = HRDepartment::query()
            ->orderBy('name', 'ASC')
            ->get();
        return HRDepartmentResource::collection($data);

    }
}
