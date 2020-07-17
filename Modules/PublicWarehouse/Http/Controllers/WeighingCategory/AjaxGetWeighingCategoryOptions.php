<?php

namespace Modules\PublicWarehouse\Http\Controllers\WeighingCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\WeighingCategory;
use Modules\PublicWarehouse\Transformers\Weighing\WeighingCategoryOptionsResource;

class AjaxGetWeighingCategoryOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $items = WeighingCategory::query()
            ->orderBy('id', 'ASC')
            ->get();

        return WeighingCategoryOptionsResource::collection($items);
    }
}
