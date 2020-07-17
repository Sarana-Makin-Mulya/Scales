<?php

use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Entities\HRDepartment;
use Modules\HumanResource\Entities\HREmployee;
use Modules\HumanResource\Entities\HREmployeeAuthorizationDetail;

if (! function_exists('getHREmployeeAuthorization')) {
    function getHREmployeeAuthorization($code)
    {
        $arr = [];
        $data  = HREmployeeAuthorizationDetail::query()
            ->whereHas('authorization', function ($query) use ($code) {
                $query->where('code', $code);
            })
            ->where('is_active', 1)
            ->get();

        if ($data->count()>0) {
            foreach ($data as $dt) {
                $arr[] = $dt->employee_nik;
            }
        }

        return $arr;
    }
}

if (! function_exists('getHRDepartmentCode')) {
    function getHRDepartmentCode($id)
    {
        $data  = HRDepartment::where('id', $id)->first();
        return !empty($data) ? $data->code : '';
    }
}

if (! function_exists('getHRDepartmentName')) {
    function getHRDepartmentName($id)
    {
        $data  = HRDepartment::where('id', $id)->first();
        return !empty($data) ? $data->name : '';
    }
}


if (! function_exists('getEmployeeFullName')) {
    function getEmployeeFullName($nik)
    {
        $data  = HREmployee::where('nik',$nik)->first();
        return !empty($data) ? $data->name : '';
    }
}

if (! function_exists('getHREmployeeDepartmentName')) {
    function getHREmployeeDepartmentName($nik)
    {
        $employee  = HREmployee::where('nik', $nik)->first();
        if (!empty($employee)) {
            $data  = HRDepartment::where('id', $employee->department_id)->first();
            $department_name = $data->name;
        } else {
            $department_name = null;
        }


        return $department_name;
    }
}

if (! function_exists('getEmployeeDescription')) {
    function getEmployeeDescription($nik)
    {
        $employee = null;
        if (!empty($nik)) {
            $employee = $nik;
            $name     = getEmployeeFullName($nik);
            if (!empty($name)) {
                $employee .=", ".$name;
            }
            $department     = getHREmployeeDepartmentName($nik);
            if (!empty($department)) {
                $employee .=" (".$department.")";
            }

        }
        return $employee;
        //return $nik.' - '.getEmployeeFullName($nik).' - '.getHREmployeeDepartmentName($nik);
    }
}
