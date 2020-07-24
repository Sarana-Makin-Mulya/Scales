<?php

namespace Modules\Stock\Http\Controllers\Category;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\ItemCategory;

class AjaxCheckPropertyExistController extends Controller
{
    public function isUniqueCode(Request $request)
    {
        if ($category = ItemCategory::where('code', $request->value)->first()) {
            if ($request->filled('id') && $category->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }

    public function isUniqueName(Request $request)
    {
        if ($category = ItemCategory::where('name', $request->value)->first()) {
            if ($request->filled('id') && $category->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
