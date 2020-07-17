<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\UserGroupMenu;
use Modules\General\Transformers\Menu\GeneralManageUserMenuResource;

class AjaxGetManageUserMenuOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $UserGroupMenu = UserGroupMenu::query()
        ->whereHas('generalMenu', function ($query) {
            $query->where('level', 1)->orderBy('id', 'ASC');
        })
        ->where('user_group_id', $request->user_group_id)
        ->where('is_active', 1)
        ->get();

        return GeneralManageUserMenuResource::collection($UserGroupMenu);
    }
}
