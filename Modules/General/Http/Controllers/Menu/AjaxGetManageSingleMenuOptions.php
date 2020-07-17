<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\GeneralMenu;
use Modules\General\Transformers\Menu\GeneralChildManageMenuResource;

class AjaxGetManageSingleMenuOptions extends Controller
{
    public function __invoke(Request $request)
    {
            $GeneralMenu = GeneralMenu::query()
            ->where('level', 0)
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->get();

        return GeneralChildManageMenuResource::collection($GeneralMenu);
    }
}
