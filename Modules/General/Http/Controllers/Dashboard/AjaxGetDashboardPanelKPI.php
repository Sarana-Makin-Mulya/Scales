<?php

namespace Modules\General\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AjaxGetDashboardPanelKPI extends Controller
{
    public function __invoke(Request $request)
    {
        return getKPIDashboard();
    }
}
