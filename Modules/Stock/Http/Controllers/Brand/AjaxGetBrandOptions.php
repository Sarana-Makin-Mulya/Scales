<?php

namespace Modules\Stock\Http\Controllers\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Brand;
use Modules\Stock\Transformers\Brand\BrandResource;

class AjaxGetBrandOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        return BrandResource::collection($brands);
    }
}
