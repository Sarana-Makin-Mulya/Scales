<?php

namespace Modules\Stock\Http\Controllers\Brand;

use Illuminate\Http\Request;
use Modules\Stock\Entities\Brand;
use Illuminate\Routing\Controller;
use Modules\Stock\Transformers\Brand\BrandResource;

class AjaxGetBrand extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $brands = Brand::query()
            ->where('name', 'LIKE', '%' . $request->keyword . '')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return BrandResource::collection($brands);
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
