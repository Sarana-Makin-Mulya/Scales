<?php

namespace Modules\PublicWarehouse\Http\Controllers\WeighingCategory;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\WeighingCategory;

class AjaxCheckPropertyExistController extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($weighingCategory = WeighingCategory::where('name', $request->value)->first()) {
            if ($request->filled('id') && $weighingCategory->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
