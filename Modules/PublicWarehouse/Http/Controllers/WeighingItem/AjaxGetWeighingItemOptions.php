<?php

namespace Modules\PublicWarehouse\Http\Controllers\WeighingItem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\WeighingItems;
use Modules\PublicWarehouse\Transformers\Weighing\WeighingItemOptionsResource;

class AjaxGetWeighingItemOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $items = WeighingItems::query()
            ->orderBy('name', 'ASC')
            ->get();

        return WeighingItemOptionsResource::collection($items);
    }
}
