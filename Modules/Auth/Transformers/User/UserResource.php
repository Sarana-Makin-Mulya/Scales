<?php

namespace Modules\Auth\Transformers\User;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Auth\Entities\UserMenu;
use Modules\HumanResource\Transformers\Employee\FindHREmployeeResource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $null = "tidak ditemukan";
        $employee_null = [
            'departement' => '',
            'departement_code' => '',
            'identity' => '',
            'name' => '',
            'nik' => '',
        ];

        $department_id = (!empty($this->employee)) ? (!empty($this->employee->department_id)) ? $this->employee->department_id : $null : $null;
        $group_name = (!empty($this->group)) ? $this->group->name : $null;
        $employee = (!empty($this->employee)) ? new FindHREmployeeResource($this->employee) : $employee_null;

        return [
            'id' => $this->id,
            'employee_nik' => $this->employee_nik,
            'employee' => $employee,
            'first_name' => getEmployeeFullName($this->employee_nik),
            'last_name' => null,
            'full_name' => getEmployeeFullName($this->employee_nik),
            'departement_name' => getHRDepartmentName($department_id),
            'user_group_id' => $this->user_group_id,
            'group' => $group_name,
            'username' => $this->username,
            'user_menus' => $this->getMenu($this->employee_nik),
            'email' => $this->email,
            'avatar' => $this->avatar,
            'status' => $this->account_status,
            'url_edit' => route('user.manage.update', [$this->id]),
            'url_status_update' => route('user.manage.update.status', [$this->id]),
            'url_delete' => route('ajax.user.destroy.manage', [$this->id]),
            'url_store_user_menu' => route('general.menu.store.menu.user', [$this->employee_nik]),
        ];
    }

    public function getMenu($employee_nik)
    {
        $menus = [];
        $GeneralMenu = UserMenu::query()
            ->whereHas('generalMenu', function ($query) {
                $query->whereIn('level', [0, 1, 2, 3]);
            })
            ->where('employee_nik', $employee_nik)
            ->where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->get();

            foreach ($GeneralMenu as $menu) {
                $menus[] = $menu['menu_id'];
            }

        return $menus;
    }
}
