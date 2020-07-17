<?php

namespace Modules\General\Http\Controllers\Currency;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Currency;

class AjaxCheckPropertyExist extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($currency = Currency::where('name', strtoupper($request->value))->first()) {
            if ($request->filled('id') && $currency->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }

    public function isUniqueSymbol(Request $request)
    {
        if ($currency = Currency::where('symbol', $request->value)->first()) {
            if ($request->filled('id') && $currency->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
