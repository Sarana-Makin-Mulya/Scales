<?php

namespace Modules\Stock\Http\Controllers\TransactionCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockTransactionCategory;
use Modules\Stock\Transformers\TransactionCategory\StockTransactionCategoryResource;

class AjaxGetStockTransactionCategory extends Controller
{
    public function __invoke(Request $request)
    {
        $transactions = StockTransactionCategory::query()
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy('name', 'ASC')
            ->paginate($request->per_page);

        return StockTransactionCategoryResource::collection($transactions);
    }
}
