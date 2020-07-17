<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\GeneralMenu;

class AjaxCheckPropertyExist extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($generalMenu = GeneralMenu::where('name', $request->value)->first()) {
            if ($request->filled('id') && $generalMenu->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }

    public function isUniqueUrl(Request $request)
    {
        if ($generalMenu = GeneralMenu::where('url', $request->value)->first()) {
            if ($generalMenu->filled('id') && $generalMenu->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
