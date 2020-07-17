<?php

use Modules\Auth\Entities\UserMenu;
use Modules\General\Entities\GeneralMenu;

if (! function_exists('getUserMenu')) {
    function getUserMenu($user_group_id = null)
    {
        if (getAuthGroupSlug(Auth::user()->user_group_id)=="super-admin") {
            $userMenu = UserMenu::where('user_group_id', $user_group_id)->first();
            $employee_nik = (!empty($userMenu)) ? $userMenu->employee_nik : null;
            $UserGroupMenu = UserMenu::query()
                ->whereHas('generalMenu', function ($query) {
                    $query->whereIn('level', ['0', '1'])->orderBy('menu_number', 'ASC');
                })
                ->where('employee_nik', $employee_nik)
                ->where('user_group_id', $user_group_id)
                ->where('is_active', 1)
                ->orderBy('menu_number', 'ASC')
                ->get();
        } else {
            $UserGroupMenu = UserMenu::query()
                ->whereHas('generalMenu', function ($query) {
                    $query->whereIn('level', ['0', '1'])->orderBy('menu_number', 'ASC');
                })
                ->where('employee_nik', Auth::user()->employee_nik)
                ->where('is_active', 1)
                ->orderBy('menu_number', 'ASC')
                ->get();
        }
        return ($UserGroupMenu);
    }
}

if (! function_exists('getMenu')) {
    function getMenu($level, $user_group_id = null)
    {
        if (getAuthGroupSlug(Auth::user()->user_group_id)=="super-admin") {
            $userMenu = UserMenu::where('user_group_id', $user_group_id)->first();
            $UserGroupMenu = UserMenu::query()
                ->whereHas('generalMenu', function ($query) use ($level) {
                    $query->where('level', $level)->orderBy('menu_number', 'ASC');
                })
                ->where('employee_nik', $userMenu->employee_nik)
                ->where('user_group_id', $user_group_id)
                ->where('is_active', 1)
                ->orderBy('menu_number', 'ASC')
                ->get();
        } else {
            $UserGroupMenu = UserMenu::query()
                ->whereHas('generalMenu', function ($query) use ($level) {
                    $query->where('level', $level)->orderBy('menu_number', 'ASC');
                })
                ->where('employee_nik', Auth::user()->employee_nik)
                ->where('is_active', 1)
                ->orderBy('menu_number', 'ASC')
                ->get();
        }
        return $UserGroupMenu;
    }
}

if (! function_exists('getChildMenu')) {
    function getChildMenu($parent_id, $user_group_id = null, $level = 2)
    {
        if (getAuthGroupSlug(Auth::user()->user_group_id)=="super-admin") {
            $userMenu = UserMenu::where('user_group_id', $user_group_id)->first();
            $UserGroupMenu = UserMenu::query()
                ->whereHas('generalMenu', function ($query) use ($parent_id, $level) {
                    $query->where('parent_id', $parent_id)->where('level', $level)->orderBy('menu_number', 'ASC');
                })
                ->where('employee_nik', $userMenu->employee_nik)
                ->where('user_group_id', $user_group_id)
                ->orderBy('menu_number', 'ASC')
                ->where('is_active', 1)
                ->get();
        } else {
            $UserGroupMenu = UserMenu::query()
                ->whereHas('generalMenu', function ($query) use ($parent_id, $level) {
                    $query->where('parent_id', $parent_id)->where('level', $level)->orderBy('menu_number', 'ASC');
                })
                ->where('employee_nik', Auth::user()->employee_nik)
                ->orderBy('menu_number', 'ASC')
                ->where('is_active', 1)
                ->get();
        }
        return $UserGroupMenu;
    }
}

if (! function_exists('checkChildMenu')) {
    function checkChildMenu($parent_id, $user_group_id = null, $level = 2)
    {
        if (getAuthGroupSlug(Auth::user()->user_group_id)=="super-admin") {
            $userMenu = UserMenu::where('user_group_id', $user_group_id)->first();
            $UserGroupMenu = UserMenu::query()
                ->whereHas('generalMenu', function ($query) use ($parent_id, $level) {
                    $query->where('parent_id', $parent_id)->where('level', $level)->orderBy('menu_number', 'ASC');
                })
                ->where('employee_nik', $userMenu->employee_nik)
                ->where('user_group_id', $user_group_id)
                ->orderBy('menu_number', 'ASC')
                ->where('is_active', 1)
                ->get();
        } else {
            $UserGroupMenu = UserMenu::query()
                ->whereHas('generalMenu', function ($query) use ($parent_id, $level) {
                    $query->where('parent_id', $parent_id)->where('level', $level)->orderBy('menu_number', 'ASC');
                })
                ->where('employee_nik', Auth::user()->employee_nik)
                ->orderBy('menu_number', 'ASC')
                ->where('is_active', 1)
                ->get();
        }
        return $UserGroupMenu->count();
    }
}

if (! function_exists('getChildUrlMenu')) {
    function getChildUrlMenu($parent_id, $level)
    {
        $urlMenus = [];
        $UserGroupMenu = UserMenu::query()
            ->whereHas('generalMenu', function ($query) use ($parent_id, $level) {
                if ($level==1) {
                    $query->where('parent_id', $parent_id)->where('level', 2)->orderBy('menu_number', 'ASC');
                } else {
                    $query->where('parent_id', $parent_id)->where('level', 3)->orderBy('menu_number', 'ASC');
                }
            })
            ->where('employee_nik', Auth::user()->employee_nik)
            ->orderBy('menu_number', 'ASC')
            ->where('is_active', 1)
            ->get();

        foreach ($UserGroupMenu as $menu) {
            $urlMenus[] = $menu['generalMenu']['url'];
            if ($level==1) {
                $parent_sub    = $menu['generalMenu']['id'];
                $UserGroupSubMenu = UserMenu::query()
                    ->whereHas('generalMenu', function ($query) use ($parent_sub) {
                            $query->where('parent_id', $parent_sub)->where('level', 3)->orderBy('menu_number', 'ASC');
                    })
                    ->where('employee_nik', Auth::user()->employee_nik)
                    ->orderBy('menu_number', 'ASC')
                    ->where('is_active', 1)
                    ->get();
                foreach ($UserGroupSubMenu as $submenu) {
                    $urlMenus[] = $submenu['generalMenu']['url'];
                }
            }
        }

        return $urlMenus;
    }
}

if (! function_exists('getMenuName')) {
    function getMenuName($id)
    {
        $data  = GeneralMenu::where('id', $id)->first();
        return !empty($data) ? $data->name : null;
    }
}

if (! function_exists('getMenuParentId')) {
    function getMenuParentId($id)
    {
        $data  = GeneralMenu::where('id', $id)->first();
        return !empty($data) ? $data->parent_id : null;
    }
}
