<?php

namespace Modules\Stock\Http\Controllers\StockQuarantine;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockQuarantine;

class AjaxGetFilterDeadStock extends Controller
{
    public function __invoke(Request $request)
    {

        $filter    = $request->filter;
        $query = StockQuarantine::query();

        if ($filter=='pending') {
            $query = $query
                ->where('status', StockQuarantine::PENDING)
                ->where('approvals', StockQuarantine::NOT_APPROVALS);
        } elseif ($filter=='waiting_approvals') {
            $query = $query
                ->where('status', StockQuarantine::APPROVALS)
                ->where('approvals', StockQuarantine::WAITING_APPROVALS);
        } elseif ($filter=='waiting_return_to_stock') {
            $query = $query
                ->where('status', StockQuarantine::APPROVALS)
                ->where('approvals', StockQuarantine::REJECTED);
        }

        $data = $query
            ->where('action', 2)
            ->get();

        return ['row' => $data->count()];
    }

}
