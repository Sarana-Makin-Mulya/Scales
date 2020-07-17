<?php

namespace Modules\HumanResource\Http\Controllers\Autorization;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\HREmployeeAuthorization;

class AjaxCheckPropertyExist extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($authorization = HREmployeeAuthorization::where('name', strtoupper($request->value))->first()) {
            if ($request->filled('id') && $authorization->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }

    public function isUniqueCode(Request $request)
    {
        if ($authorization = HREmployeeAuthorization::where('code', $request->value)->first()) {
            if ($request->filled('id') && $authorization->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
