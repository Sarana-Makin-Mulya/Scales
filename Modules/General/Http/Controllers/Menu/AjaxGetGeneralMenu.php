<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\General\Entities\GeneralMenu;
use Modules\General\Transformers\Menu\GeneralMenuResource;

class AjaxGetGeneralMenu extends Controller
{
    protected $orderBy;
    protected $sortBy = 'ASC';

    public function __invoke(Request $request)
    {
        $this->createSortOrder($request);

        $menus = GeneralMenu::query()
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->Orwhere('route', 'LIKE', '%' . $request->keyword . '%')
            ->Orwhere('url', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy($this->orderBy, $this->sortBy)
            ->paginate($request->per_page);

        return GeneralMenuResource::collection($menus);
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'updated_at';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'DESC'
            : $this->sortBy = 'ASC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
