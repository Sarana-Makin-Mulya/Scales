<?php

namespace Modules\Auth\Http\Controllers\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\UserGroup;

class AjaxCheckPropertyExistController extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($unit = UserGroup::withTrashed()->where('name', $request->value)->first()) {
            if ($request->filled('id') && $unit->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
