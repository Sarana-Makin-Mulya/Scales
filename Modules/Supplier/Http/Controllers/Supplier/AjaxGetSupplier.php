<?php

namespace Modules\Supplier\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Supplier\Entities\Supplier;
use Modules\Supplier\Transformers\SupplierResource;

class AjaxGetSupplier extends Controller
{
    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $suppliers = Supplier::with('supplierCategory','contacts','addresses')
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return SupplierResource::collection($suppliers);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'updated_at';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
