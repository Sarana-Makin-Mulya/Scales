<?php

namespace Modules\Supplier\Http\Controllers\SupplierCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Supplier\Entities\SupplierCategory;

class AjaxCheckPropertyExistController extends Controller
{
    public function isUniqueCode(Request $request)
    {
        if ($category = SupplierCategory::where('code', $request->value)->first()) {
            if ($request->filled('id') && $category->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }

    public function isUniqueName(Request $request)
    {
        if ($category = SupplierCategory::where('name', $request->value)->first()) {
            if ($request->filled('id') && $category->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
