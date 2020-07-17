<?php

namespace Modules\Regional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Regional\Entities\Province;
use Modules\Regional\Transformers\ProvinceResource;

class AjaxGetProvinceController extends Controller
{
    /**
     * Get provinces data by name
     *
     * @param Illuminate\Http\Request $request
     * @return
     */
    public function getProvinces(Request $request)
    {
        $provinces = Province::query()
            ->where('name', 'LIKE', '%' . $request->name . '%')
            ->get();

        return ProvinceResource::collection($provinces);
    }
}
