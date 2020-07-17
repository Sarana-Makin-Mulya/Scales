<?php

namespace Modules\Auth\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\User;

class AjaxCheckPropertyExistController extends Controller
{
    public function isUniqueUserName(Request $request)
    {
        if ($user = User::withTrashed()->where('username', $request->value)->first()) {
            if ($request->filled('id') && $user->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }

    public function isUniqueEmail(Request $request)
    {
        if ($user = User::withTrashed()->where('email', $request->value)->first()) {
            if ($request->filled('id') && $user->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
