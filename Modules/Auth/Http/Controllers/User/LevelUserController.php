<?php

namespace Modules\Auth\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Entities\UserGroup;

class LevelUserController extends Controller
{

    public function setLevel(Request $request)
    {
        $userGroup = DB::table('user_groups')
                ->select('user_groups.id', 'user_groups.name')
                ->Join('users', 'user_groups.id', '=', 'users.user_group_id')
                ->where('user_groups.slug','<>','super-admin')
                ->orderBy('user_groups.id','ASC')
                ->first();
        $level = (!empty($request->level)) ? $request->level : $userGroup->id;
        $request->session()->put('user_level', $level);
        $user_level = $request->session()->get('user_level');
        return $user_level;
    }
}
