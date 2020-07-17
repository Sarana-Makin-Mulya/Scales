<?php

namespace Modules\Regional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Regional\Entities\District;
use Modules\Regional\Transformers\DistrictResource;

class AjaxGetDistrictController extends Controller
{
    public function getDistrictsByRegency(Request $request)
    {
        $regencyId = ($request->filled('regency_id')) ? $request->regency_id : null;

        $districts = District::query()
            ->where('regency_id', $regencyId)
            ->get();

        return DistrictResource::collection($districts);
    }
}
