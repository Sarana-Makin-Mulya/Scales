<?php

namespace Modules\Supplier\Http\Controllers\SupplierCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Supplier\Entities\SupplierCategory;
use Modules\Supplier\Transformers\SupplierCategory\SupplierCategoryResource;

class AjaxGetSupplierCategory extends Controller
{
    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $supplierCategory = SupplierCategory::query()
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return SupplierCategoryResource::collection($supplierCategory);
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
