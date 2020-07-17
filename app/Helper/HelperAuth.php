<?php

use Illuminate\Support\Facades\DB;
use Modules\Auth\Entities\User;

if (! function_exists('getAuthorization')) {
    function getAuthorization($login, $level)
    {
        return ($login=="super-admin") ? getAuthLevelName($level) : $login;
    }
}

if (! function_exists('getAuthDescription')) {
    function getAuthDescription($data)
    {
        $employee = getEmployeeFullName($data->employee_nik);
        $group = getAuthGroup($data->user_group_id);
        return "<b>".$employee."</b> login sebagai <b>".$group."</b>";
    }
}

if (! function_exists('getUserGroup')) {
    function getUserGroup()
    {
        $userGroup = DB::table('user_groups')
            ->select('user_groups.id', 'user_groups.name')
            ->Join('users', 'user_groups.id', '=', 'users.user_group_id')
            ->where('user_groups.slug','<>','super-admin')
            ->orderBy('user_groups.id','ASC')
            ->groupBy('user_groups.id')
            ->get();

        return $userGroup;
    }
}

if (! function_exists('getAuthGroup')) {
    function getAuthGroup($id)
    {
        $model = new Modules\Auth\Entities\UserGroup;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getAuthGroupSlug')) {
    function getAuthGroupSlug($id)
    {
        $model = new Modules\Auth\Entities\UserGroup;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->slug : '';
    }
}

if (! function_exists('getAuthLevel')) {
    function getAuthLevel()
    {
        $model = new Modules\Auth\Entities\UserGroup;
        $data  = $model::where('id',Auth::user()->user_group_id)->first();
        return !empty($data) ? $data->slug : '';
    }
}

if (! function_exists('getNotificationGroup')) {
    function getNotificationGroup($usergroup)
    {
        if ($usergroup=="kepala-gudang-umum" or $usergroup=="operator-gudang-umum" or $usergroup=="admin-gudang-umum") {
            $notification_group_id = 2;
        } elseif ($usergroup=="pembelian" or $usergroup=="pembelian-langsung") {
            $notification_group_id = 3;
        } elseif ($usergroup=="direksi") {
            $notification_group_id = 4;
        } elseif ($usergroup=="inventory-control") {
            $notification_group_id = 5;
        } else {
            $notification_group_id = 1;
        }

        return $notification_group_id ;
    }
}

if (! function_exists('getAuthLevelByUserId')) {
    function getAuthLevelByUserId($id)
    {
        $data  = User::where('id',$id)->first();
        return !empty($data) ? getAuthGroupName($data->user_group_id) : '-';
    }
}


if (! function_exists('getAuthLevelName')) {
    function getAuthLevelName($id)
    {
        $model = new Modules\Auth\Entities\UserGroup;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->slug : '';
    }
}

if (! function_exists('getAuthGroupName')) {
    function getAuthGroupName($id)
    {
        $model = new Modules\Auth\Entities\UserGroup;
        $data  = $model::where('id',$id)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getAuthEmployeeNik')) {
    function getAuthEmployeeNik($id)
    {
        $data  = User::where('id',$id)->first();
        return !empty($data) ? $data->employee_nik : '';
    }
}
