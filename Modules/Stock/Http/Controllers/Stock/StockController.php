<?php

namespace Modules\Stock\Http\Controllers\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class StockController extends Controller
{
    public function index()
    {
        return view('stock::stock.index');
    }

    public function BufferStock()
    {
        $pageName = "Buffer Stock";
        $filterName = "buffer";
        return view('stock::stock.warning_stock', compact('pageName', 'filterName'));
    }

    public function Quarantine()
    {
        $pageName = "Karantina";
        $filterName = "quarantine";
        return view('stock::stock.warning_stock', compact('pageName', 'filterName'));
    }

    public function DeadStock()
    {
        $pageName = "Dead Stock";
        $filterName = "deadstock";
        return view('stock::stock.warning_stock', compact('pageName', 'filterName'));
    }

}
