<?php

namespace Modules\Supplier\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Supplier\Entities\Supplier;
use Modules\Supplier\Transformers\SupplierOptionsResource;

class AjaxGetSupplierOptions extends Controller
{
    public function __invoke(Request $request)
    {
            $suppliers = Supplier::query()
            ->orderBy('name', 'ASC')
            ->get();

        return SupplierOptionsResource::collection($suppliers);
    }
}
