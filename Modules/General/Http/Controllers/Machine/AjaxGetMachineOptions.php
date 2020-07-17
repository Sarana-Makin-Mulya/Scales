<?php

namespace Modules\General\Http\Controllers\Machine;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Machine;
use Modules\General\Transformers\Machine\MachineOptionsResource;

class AjaxGetMachineOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $units = Machine::query()
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        return MachineOptionsResource::collection($units);
    }
}
