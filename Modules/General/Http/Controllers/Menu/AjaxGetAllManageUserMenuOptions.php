<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\UserGroupMenu;
use Modules\General\Entities\GeneralMenu;

class AjaxGetAllManageUserMenuOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $menus = [];
        $UserGroupMenu = UserGroupMenu::query()
            ->whereHas('generalMenu', function ($query) {
                $query->whereIn('level', [0, 1, 2, 3])->orderBy('id', 'ASC');
            })
            ->where('user_group_id', $request->user_group_id)
            ->where('is_active', 1)
            ->get();


            foreach ($UserGroupMenu as $menu) {
                $menus[] = $menu['menu_id'];
            }

        return ['menu' => $menus];
    }
}
