<?php

namespace Modules\General\Http\Controllers\Unit;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Unit;

class AjaxCheckPropertyExistController extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($unit = Unit::withTrashed()->where('name', $request->value)->first()) {
            if ($request->filled('id') && $unit->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }

    public function isUniqueSymbol(Request $request)
    {
        if ($unit = Unit::withTrashed()->where('symbol', $request->value)->first()) {
            if ($request->filled('id') && $unit->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
