<?php

namespace Modules\General\Http\Controllers\Unit;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Unit;
use Modules\General\Transformers\UnitResource;

class AjaxGetUnit extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $units = Unit::query()
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orwhere('symbol', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return UnitResource::collection($units);
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
