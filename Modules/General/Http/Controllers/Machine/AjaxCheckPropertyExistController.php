<?php

namespace Modules\General\Http\Controllers\Machine;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Machine;

class AjaxCheckPropertyExistController extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($machine = Machine::withTrashed()->where('name', $request->value)->first()) {
            if ($request->filled('id') && $machine->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
