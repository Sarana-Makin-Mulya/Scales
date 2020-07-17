<?php

namespace Modules\Auth\Transformers\Group;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Auth\Entities\UserGroupMenu;
use Modules\General\Entities\GeneralMenu;

class UserGroupResource extends Resource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => (boolean) $this->is_active,
            'group_menus' => $this->getMenu($this->id),
            'url_edit' => route('user.group.update', [$this->id]),
            'url_status_update' => route('user.group.update.status', [$this->id]),
            'url_delete' => route('ajax.user.destroy.group', [$this->id]),
            'url_store_menu_group' => route('general.menu.store.menu.group', [$this->id]),
        ];
    }


    public function getMenu($id)
    {
        $menus = [];
        $GeneralMenu = UserGroupMenu::query()
            ->whereHas('generalMenu', function ($query) {
                $query->whereIn('level', [0, 1, 2, 3]);
            })
            ->where('user_group_id', $id)
            ->where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->get();

            foreach ($GeneralMenu as $menu) {
                $menus[] = $menu['menu_id'];
            }

        return $menus;
    }
}
