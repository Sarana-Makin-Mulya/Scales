<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\GeneralMenu;
use Modules\General\Transformers\Menu\GeneralAllManageMenuResource;

class AjaxGetAllManageMenuOptions extends Controller
{
    public function __invoke(Request $request)
    {
        $menus = [];
        $GeneralMenu = GeneralMenu::query()
            ->whereIn('level', [0, 1, 2, 3])
            ->orderBy('id', 'ASC')
            ->where('is_active', 1)
            ->get();

            foreach ($GeneralMenu as $menu) {
                $menus[] = $menu['id'];
            }

        return ['menu' => $menus];
    }
}
