<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Unit;

class AjaxGetUnitName extends Controller
{
    public function __invoke(Request $request)
    {
        $unit = Unit::where('id',$request->id)->first();
        return (!empty($unit)) ? $unit->symbol.' ('.$unit->name.')' : '';
    }
}
