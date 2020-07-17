<?php

namespace Modules\General\Http\Controllers\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Unit;
use Modules\General\Transformers\Unit\UnitOptionsResource;

class AjaxGetUnitOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $units = Unit::query()
            ->where('is_active', 1)
            ->get();

        return UnitOptionsResource::collection($units);
    }
}
