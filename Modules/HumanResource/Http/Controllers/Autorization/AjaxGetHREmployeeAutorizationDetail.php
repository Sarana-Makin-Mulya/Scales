<?php

namespace Modules\HumanResource\Http\Controllers\Autorization;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\HREmployeeAuthorization;
use Modules\HumanResource\Transformers\Autorization\HREmployeeAutorizationResource;

class AjaxGetHREmployeeAutorizationDetail extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $Authorization = HREmployeeAuthorization::where('id', $id)->first();
        if (!empty($Authorization)) {
             return new HREmployeeAutorizationResource($Authorization);
        } else {
            return 404;
        }
    }
}
