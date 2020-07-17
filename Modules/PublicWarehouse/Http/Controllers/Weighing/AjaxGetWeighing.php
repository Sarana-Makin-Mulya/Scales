<?php

namespace Modules\PublicWarehouse\Http\Controllers\Weighing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\Weighing;
use Modules\PublicWarehouse\Transformers\Weighing\WeighingResource;

class AjaxGetWeighing extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);
        $keyword = $request->keyword;
        $items = Weighing::query()
            ->where(function ($query) use ($keyword) {
                $query->whereHas('weighingCategory', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%' . $keyword . '%');
                })
                ->orWhere('supplier_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('driver_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('do_code', 'LIKE', '%' . $keyword . '%')
                ->orWhere('first_number_plate', 'LIKE', '%' . $keyword . '%')
                ->orWhere('second_number_plate', 'LIKE', '%' . $keyword . '%');
            })
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return WeighingResource::collection($items);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'updated_at';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : '';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
