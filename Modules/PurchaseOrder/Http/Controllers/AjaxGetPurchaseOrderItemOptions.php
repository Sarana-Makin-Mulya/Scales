<?php

namespace Modules\PurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PurchaseOrder\Entities\PurchaseOrderItems;
use Modules\PurchaseOrder\Transformers\PurchaseOrderItemOptionsResource;

class AjaxGetPurchaseOrderItemOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $requests = PurchaseOrderItems::query()
            ->where('status', '<', 5)
            ->get();

        return PurchaseOrderItemOptionsResource::collection($requests);
    }
}
