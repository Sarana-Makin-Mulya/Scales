<?php

namespace Modules\General\Transformers\Menu;

use Illuminate\Http\Resources\Json\Resource;

class GeneralMenuResource extends Resource
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
            'menu_number' => $this->menu_number,
            'parent_id' => ($this->level>2) ?  getMenuParentId($this->parent_id) : $this->parent_id,
            'parent_sub_id' => ($this->level>2) ? $this->parent_id : null,
            'parent_name' => getMenuName($this->parent_id),
            'name' => $this->name,
            'url' => $this->url,
            'route' => $this->route,
            'icon' => $this->icon,
            'level' => ($this->level>=2) ? 2 : $this->level,
            'level_sub' => ($this->level>1) ? $this->level : null,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('general.menu.update', [$this->id]),
            'url_delete' => route('ajax.general.destroy.menu', [$this->id]),
            'url_status_update' => route('general.menu.update.status', [$this->id]),
        ];
    }
}
