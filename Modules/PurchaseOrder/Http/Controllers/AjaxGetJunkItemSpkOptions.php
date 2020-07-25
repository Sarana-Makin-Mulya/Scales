<?php

namespace Modules\PurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PurchaseOrder\Entities\JunkItemSpk;
use Modules\PurchaseOrder\Transformers\JunkItemSpkOptionsResource;

class AjaxGetJunkItemSpkOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $requests = JunkItemSpk::query()
            ->where('status', 1)
            ->orderBy('issue_date', 'DESC')
            ->get();

        return JunkItemSpkOptionsResource::collection($requests);
    }
}
