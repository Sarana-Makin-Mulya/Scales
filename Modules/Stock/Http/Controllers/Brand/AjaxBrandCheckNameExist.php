<?php

namespace Modules\Stock\Http\Controllers\Brand;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Brand;

class AjaxBrandCheckNameExist extends Controller
{
    public function __invoke(Request $request)
    {
        if ($brand = Brand::withTrashed()->where('name', $request->value)->first()) {
            if ($request->filled('id') && $brand->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
