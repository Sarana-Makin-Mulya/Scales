<?php

namespace Modules\PublicWarehouse\Http\Controllers\Weighing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\Weighing;
use Modules\PublicWarehouse\Transformers\Weighing\DetailWeighingResource;

class AjaxGetDetailWeighing extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $weighing = Weighing::query()
            ->where('id', $id)->first();
        if (!empty($weighing)) {
            return new DetailWeighingResource($weighing);
        } else {
            return "404";
        }
    }
}
