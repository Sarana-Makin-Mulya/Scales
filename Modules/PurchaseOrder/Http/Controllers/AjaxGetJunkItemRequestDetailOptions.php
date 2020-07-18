<?php

namespace Modules\PurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PurchaseOrder\Entities\JunkItemRequest;
use Modules\PurchaseOrder\Transformers\JunkItemRequestDetailOptionsResource;

class AjaxGetJunkItemRequestDetailOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $requests = JunkItemRequest::query()
            ->where('status', 1)
            ->get();

        return JunkItemRequestDetailOptionsResource::collection($requests);
    }
}
