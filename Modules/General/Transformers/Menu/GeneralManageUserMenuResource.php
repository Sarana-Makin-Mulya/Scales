<?php

namespace Modules\General\Transformers\Menu;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Auth\Entities\UserGroupMenu;
use Modules\General\Entities\GeneralMenu;

class GeneralManageUserMenuResource extends Resource
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
            'user_group_id' => $this->user_group_id,
            'menu_id' => $this->generalMenu->id,
            'parent_id' => $this->generalMenu->parent_id,
            'parent_name' => getMenuName($this->generalMenu->parent_id),
            'name' => $this->generalMenu->name,
            'url' => $this->generalMenu->url,
            'route' => $this->generalMenu->route,
            'icon' => $this->generalMenu->icon,
            'level' => $this->generalMenu->level,
            'status' => (boolean) $this->generalMenu->is_active,
            'child' => GeneralChildManageUserMenuResource::collection($this->getChildMenu($this->generalMenu->id, $this->user_group_id)),
        ];
    }

    public function getChildMenu($id, $user_group_id)
    {
        $UserGroupMenu = UserGroupMenu::query()
        ->whereHas('generalMenu', function ($query) use ($id) {
            $query->where('parent_id', $id)->orderBy('id', 'ASC');
        })
        ->where('user_group_id', $user_group_id)
        ->where('is_active',1)
        ->get();

        return $UserGroupMenu;

    }
}
