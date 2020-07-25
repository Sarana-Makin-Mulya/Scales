<?php

namespace Modules\PurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AjaxGetUserData extends Controller
{
    public function __invoke(Request $request)
    {
        return ['user' => getEmployeeFullName(Auth::user()->employee_nik), 'date' => date('Y-m-d H:i:s')];
    }
}
