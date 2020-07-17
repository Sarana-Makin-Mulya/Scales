<?php

namespace Modules\General\Http\Controllers\Bank;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\Bank;

class AjaxCheckPropertyExist extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($bank = Bank::where('name', $request->value)->first()) {
            if ($request->filled('id') && $bank->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
