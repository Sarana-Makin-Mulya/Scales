<?php

if (! function_exists('getResourceEmployeeFullName')) {
    function getResourceEmployeeFullName($employee)
    {
        if (!empty($employee)) {
            if (!empty($employee->identity)) {
                $full_name = $employee->identity->first_name." ".$employee->identity->last_name;
            } else {
                $full_name = "Tidak ditemukan";
            }
        } else {
            $full_name = "Tidak ditemukan";
        }

        return $full_name;
    }
}
