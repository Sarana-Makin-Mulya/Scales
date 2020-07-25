<?php

namespace Modules\PurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PurchaseOrder\Entities\JunkItemSpkDetail;
use Modules\PurchaseOrder\Transformers\JunkItemSpkDetailOptionsResource;

class AjaxGetJunkItemSpkDetailOptions extends Controller
{
    public function __invoke(Request $request)
    {

        $requests = JunkItemSpkDetail::query()
            ->where('junk_item_spk_code', $request->code)
            ->where('status', 1)
            ->get();

        return JunkItemSpkDetailOptionsResource::collection($requests);
    }
}
