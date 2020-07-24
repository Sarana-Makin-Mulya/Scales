<?php

namespace Modules\Stock\Http\Controllers\TransactionCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockTransactionCategory;

class AjaxCheckPropertyExistController extends Controller
{
    public function isUniqueName(Request $request)
    {
        if ($transactioncategory = StockTransactionCategory::where('name', $request->value)->first()) {
            if ($request->filled('id') && $transactioncategory->id == $request->id) {
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 422);
        }

        return response()->json(['status' => true], 200);
    }
}
