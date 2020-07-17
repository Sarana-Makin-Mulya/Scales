<?php

namespace Modules\Supplier\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Supplier\Entities\Supplier;
use Modules\Supplier\Transformers\DetailSupplierResource;

class AjaxGetDetailSupplier extends Controller
{
    public function __invoke(Request $request, $code)
    {
        $suppliers = Supplier::with('supplierCategory','contacts','addresses')
            ->where('code', $code)
            ->first();

        if (!empty($suppliers)) {
            return new DetailSupplierResource($suppliers);
        } else {
            return "404";
        }
    }
}
