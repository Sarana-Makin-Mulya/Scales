<?php

namespace Modules\Regional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Regional\Entities\Village;
use Modules\Regional\Transformers\VillageResource;

class AjaxGetVillageController extends Controller
{
    public function getVillagesByDistrict(Request $request)
    {
        $districtId = ($request->filled('district_id')) ? $request->district_id : null;

        $villages = Village::query()
            ->where('district_id', $districtId)
            ->get();

        return VillageResource::collection($villages);
    }
}
