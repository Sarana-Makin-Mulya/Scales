<?php

namespace Modules\General\Transformers\Menu;

use Illuminate\Http\Resources\Json\Resource;
use Modules\General\Entities\GeneralMenu;

class GeneralManageMenuResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'parent_name' => getMenuName($this->parent_id),
            'name' => $this->name,
            'url' => $this->url,
            'route' => $this->route,
            'icon' => $this->icon,
            'level' => $this->level,
            'status' => (boolean) $this->is_active,
            'child' => GeneralChildManageMenuResource::collection($this->getChildMenu($this->id)),
        ];
    }

    public function getChildMenu($id)
    {
        return GeneralMenu::where('parent_id', $id)->where('is_active',1)->get();
    }
}
