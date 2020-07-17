<?php

namespace Modules\General\Http\Controllers\Machine;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Machine;
use Modules\General\Transformers\Machine\MachineResource;

class AjaxGetMachine extends Controller
{
    protected $orderBy;
    protected $sortBy = 'DESC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $units = Machine::query()
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('serial_number', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('capacity', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return MachineResource::collection($units);
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
