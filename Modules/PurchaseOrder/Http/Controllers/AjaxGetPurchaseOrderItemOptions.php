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
            ->where('purchasing_purchase_order_code', $request->code)
            ->where('status', '<', 5)
            ->get();

        return PurchaseOrderItemOptionsResource::collection($requests);
    }
}
