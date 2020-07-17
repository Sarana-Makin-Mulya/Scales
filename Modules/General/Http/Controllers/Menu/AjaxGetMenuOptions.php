<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\GeneralMenu;
use Modules\General\Transformers\Menu\GeneralMenuResource;

class AjaxGetMenuOptions extends Controller
{
    public function __invoke(Request $request)
    {


        $query = GeneralMenu::query();

        if ($request->parent_id>0) {
           $query = $query->where('parent_id', $request->parent_id);
        }

        if (!empty($request->level)) {
           $query = $query->where('level', $request->level);
        }

        $GeneralMenu = $query
            ->orderBy('name', 'ASC')
            ->get();

        return GeneralMenuResource::collection($GeneralMenu);
    }
}
