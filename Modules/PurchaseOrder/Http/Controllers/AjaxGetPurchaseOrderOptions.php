<?php

namespace Modules\PurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Transformers\PurchaseOrderOptionsResource;

class AjaxGetPurchaseOrderOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $requests = PurchaseOrder::query()
            ->where('po_type', '!=', PurchaseOrder::PO_COAL)
            ->where('status', '<', 3)
            ->get();

        return PurchaseOrderOptionsResource::collection($requests);
    }
}
