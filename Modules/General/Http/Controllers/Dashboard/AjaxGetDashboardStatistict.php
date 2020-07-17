<?php

namespace Modules\General\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AjaxGetDashboardStatistict extends Controller
{
    public function __invoke(Request $request)
    {

        $statistict = [];
        if ($request->group=="gudang-umum") {
            $statistict = getStatistictGudangUmum();
        } elseif ($request->group=="purchasing") {
            $statistict = getStatistictPurchasing();
        }

        return $statistict;
    }
}
