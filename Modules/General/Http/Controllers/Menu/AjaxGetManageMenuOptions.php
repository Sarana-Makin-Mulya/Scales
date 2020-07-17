<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\GeneralMenu;
use Modules\General\Transformers\Menu\GeneralManageMenuResource;

class AjaxGetManageMenuOptions extends Controller
{
    public function __invoke(Request $request)
    {
            $GeneralMenu = GeneralMenu::query()
            ->where('level', 1)
            ->where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->get();

        return GeneralManageMenuResource::collection($GeneralMenu);
    }
}
