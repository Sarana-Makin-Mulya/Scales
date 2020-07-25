<?php

namespace Modules\PurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\PurchaseOrder\Entities\JunkItemSpk;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\Supplier\Entities\Supplier;

class AjaxGetSupplier extends Controller
{
    public function __invoke(Request $request)
    {
        $supplier_code = null;
        $supplier_name = null;

        if ($request->type=='spk') {
            $spk  = JunkItemSpk::where('code', $request->code)->first();
            if (!empty($spk)) {
               $supplier_code = ($spk->buyer_type==1) ? $spk->buyer_nik : $spk->buyer_code;
               $supplier_name = ($spk->buyer_type==1) ? getEmployeeFullName($spk->buyer_nik) : $spk->buyer_name;;
            }
        } elseif ($request->type=='po') {
            $po  = PurchaseOrder::where('code', $request->code)->first();
            if (!empty($po)) {
                $supplier_code = $po->supplier_code;
                $supplier_name = $po->supplier_name;
            }
        }

        return ['supplier_code' => $supplier_code, 'supplier_name' => $supplier_name];
    }
}
