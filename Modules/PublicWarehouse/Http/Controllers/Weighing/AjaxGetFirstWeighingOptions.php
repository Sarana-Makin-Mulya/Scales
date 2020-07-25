<?php

namespace Modules\PublicWarehouse\Http\Controllers\Weighing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\Weighing;
use Modules\PublicWarehouse\Transformers\Weighing\WeighingFirstOptionsResource;

class AjaxGetFirstWeighingOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $requests = Weighing::query()
            ->where('stage', Weighing::WEIGHING_FIRST)
            ->where('status', Weighing::PROCESS)
            ->orderBy('issue_date', 'DESC')
            ->get();

        return WeighingFirstOptionsResource::collection($requests);
    }
}
