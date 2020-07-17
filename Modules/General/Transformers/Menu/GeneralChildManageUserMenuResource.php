<?php

namespace Modules\General\Transformers\Menu;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Auth\Entities\UserGroupMenu;
use Modules\General\Entities\GeneralMenu;

class GeneralChildManageUserMenuResource extends Resource
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
            'menu_id' => $this->generalMenu->id,
            'parent_id' => $this->generalMenu->parent_id,
            'parent_name' => getMenuName($this->generalMenu->parent_id),
            'name' => $this->generalMenu->name,
            'url' => $this->generalMenu->url,
            'route' => $this->generalMenu->route,
            'icon' => $this->generalMenu->icon,
            'level' => $this->generalMenu->level,
            'status' => (boolean) $this->generalMenu->is_active,
            'check' => 'N',
            'parent_sub' => $this->checkParentSub($this->generalMenu->id, $this->user_group_id),
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

    public function checkParentSub($id, $user_group_id)
    {
        $UserGroupMenu = UserGroupMenu::query()
        ->whereHas('generalMenu', function ($query) use ($id) {
            $query->where('parent_id', $id)->orderBy('id', 'ASC');
        })
        ->where('user_group_id', $user_group_id)
        ->where('is_active',1)
        ->get();

        return ($UserGroupMenu->count()>0) ? 1 : 0;
    }
}
