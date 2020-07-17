<?php

namespace Modules\Regional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Regional\Entities\Regency;
use Modules\Regional\Transformers\RegencyResource;

class AjaxGetRegencyController extends Controller
{
    public function getRegenciesByProvince(Request $request)
    {
        $provinceId = ($request->filled('province_id')) ? $request->province_id : null;

        $regencies = Regency::query()
            ->where('province_id', $provinceId)
            ->get();

        return RegencyResource::collection($regencies);
    }
}
